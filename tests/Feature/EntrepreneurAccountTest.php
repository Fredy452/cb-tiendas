<?php

namespace Tests\Feature;

use App\Filament\Resources\Stores\Pages\EditStore;
use App\Mail\StoreCreated;
use App\Models\Category;
use App\Models\Store;
use App\Models\User;
use App\Notifications\VerifyEntrepreneurEmail;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EntrepreneurAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_the_custom_authentication_pages(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSeeText('Iniciar sesión');

        $this->get(route('register'))
            ->assertOk()
            ->assertSeeText('Crear cuenta');

        $this->get(route('filament.admin.auth.login'))
            ->assertRedirect(route('login'));

        $this->assertInstanceOf(
            VerifyEntrepreneurEmail::class,
            app(\Filament\Auth\Notifications\VerifyEmail::class),
        );
    }

    public function test_registration_creates_an_entrepreneur_and_claims_stores_with_the_same_email(): void
    {
        Notification::fake();

        $store = Store::query()->create([
            'name' => 'Negocio existente',
            'email' => 'duena@example.test',
            'status' => 'pending',
        ]);

        $response = $this->post(route('register.store'), [
            'name' => 'María Emprendedora',
            'email' => 'DUENA@example.test',
            'password' => 'password-seguro',
            'password_confirmation' => 'password-seguro',
        ]);

        $user = User::query()->where('email', 'duena@example.test')->firstOrFail();

        $response->assertRedirect(route('verification.notice'));
        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->hasRole('emprendedor'));
        $this->assertTrue($store->fresh()->user->is($user));
        Notification::assertSentTo($user, VerifyEntrepreneurEmail::class, function (VerifyEntrepreneurEmail $notification) use ($user): bool {
            $mail = $notification->toMail($user);

            return $mail->subject === 'Activá tu cuenta de CB Tiendas'
                && str_contains($mail->render(), 'Verificar mi cuenta');
        });
    }

    public function test_registration_does_not_depend_on_a_queue_worker_to_send_verification_email(): void
    {
        Queue::fake();

        $response = $this->post(route('register.store'), [
            'name' => 'María Emprendedora',
            'email' => 'email-verificacion@example.test',
            'password' => 'password-seguro',
            'password_confirmation' => 'password-seguro',
        ]);

        $response->assertRedirect(route('verification.notice'));
        Queue::assertNothingPushed();
    }

    public function test_unverified_entrepreneur_cannot_enter_the_panel(): void
    {
        $user = User::factory()->unverified()->create();
        $user->assignRole(Role::findOrCreate('emprendedor', 'web'));

        $this->actingAs($user)
            ->get(route('filament.admin.resources.stores.index'))
            ->assertRedirect(route('filament.admin.auth.email-verification.prompt'));
    }

    public function test_signed_link_verifies_the_account_and_opens_the_store_panel(): void
    {
        $user = User::factory()->unverified()->create();
        $user->assignRole(Role::findOrCreate('emprendedor', 'web'));

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())],
        );

        $this->actingAs($user)
            ->get($verificationUrl)
            ->assertRedirect(route('filament.admin.resources.stores.index'));

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_entrepreneur_store_list_does_not_expose_another_owners_store(): void
    {
        /** @var User $owner */
        $owner = User::factory()->create();
        /** @var User $otherOwner */
        $otherOwner = User::factory()->create();
        $role = Role::findOrCreate('emprendedor', 'web');
        $owner->assignRole($role);
        $otherOwner->assignRole($role);

        $ownStore = Store::query()->create([
            'user_id' => $owner->getKey(),
            'name' => 'Tienda propia visible',
            'status' => 'pending',
        ]);
        $otherStore = Store::query()->create([
            'user_id' => $otherOwner->getKey(),
            'name' => 'Tienda ajena secreta',
            'status' => 'pending',
        ]);

        $this->actingAs($owner)
            ->get(route('filament.admin.resources.stores.index'))
            ->assertOk()
            ->assertSeeText($ownStore->name)
            ->assertDontSeeText($otherStore->name);

        $this->actingAs($owner)
            ->get(route('filament.admin.resources.stores.edit', $otherStore))
            ->assertNotFound();
    }

    public function test_entrepreneur_changes_return_an_approved_store_to_review(): void
    {
        Mail::fake();

        /** @var User $owner */
        $owner = User::factory()->create(['email' => 'propietaria@example.test']);
        $approver = User::factory()->create();
        $owner->assignRole(Role::findOrCreate('emprendedor', 'web'));

        $category = Category::query()->create([
            'name' => 'Servicios',
            'slug' => 'servicios',
            'is_active' => true,
        ]);
        $store = Store::query()->create([
            'user_id' => $owner->getKey(),
            'name' => 'Tienda aprobada',
            'slug' => 'tienda-aprobada',
            'description' => 'Descripción original de la tienda.',
            'email' => 'contacto@example.test',
            'status' => 'approved',
            'approval_date' => now(),
            'approval_user_id' => $approver->getKey(),
        ]);
        $store->categories()->attach($category);

        $this->actingAs($owner);
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(EditStore::class, ['record' => $store->getRouteKey()])
            ->fillForm([
                'name' => 'Tienda con cambios',
                'slug' => 'tienda-con-cambios',
                'description' => 'Descripción actualizada por la propietaria.',
                'email' => 'nuevo-contacto@example.test',
                'categories' => [$category->getKey()],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $store->refresh();

        $this->assertSame('pending', $store->status);
        $this->assertNull($store->approval_date);
        $this->assertNull($store->approval_user_id);
        $this->assertFalse(Store::query()->publicVisible()->whereKey($store)->exists());

        Mail::assertSent(StoreCreated::class, function (StoreCreated $mail) use ($owner): bool {
            return $mail->hasTo($owner->email)
                && $mail->changesSubmitted
                && $mail->envelope()->subject === 'Cambios en revisión: Tienda con cambios';
        });

        Mail::fake();
        $store->update(['description' => 'Una segunda modificación pendiente.']);

        Mail::assertSent(StoreCreated::class, function (StoreCreated $mail) use ($owner): bool {
            return $mail->hasTo($owner->email) && $mail->changesSubmitted;
        });
    }
}
