<?php

namespace Tests\Feature;

use App\Filament\Resources\ActivityLog\ActivityLogResource;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ActivityLogAccessTest extends TestCase
{
    use RefreshDatabase;

    public static function roles(): array
    {
        return [
            'super admin' => ['super_admin', true],
            'administrator with audit permissions' => ['admin', false],
            'entrepreneur with audit permissions' => ['emprendedor', false],
            'user without a role' => [null, false],
        ];
    }

    #[DataProvider('roles')]
    public function test_only_super_admin_can_access_audit_navigation_and_pages(?string $roleName, bool $allowed): void
    {
        $user = User::factory()->create(['email' => 'audit@cb-tiendas.com.py']);

        if ($roleName !== null) {
            $user->assignRole(Role::findOrCreate($roleName, 'web'));
        }

        foreach (['ViewAny:Activity', 'View:Activity'] as $permission) {
            $user->givePermissionTo(Permission::findOrCreate($permission, 'web'));
        }

        $activity = Activity::query()->firstOrFail();
        $this->actingAs($user);
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $this->assertSame($allowed, ActivityLogResource::canAccess());
        $navigationLabels = collect(Filament::getNavigation())
            ->flatMap(fn ($group) => $group->getItems())
            ->map(fn ($item) => $item->getLabel());
        $this->assertSame($allowed, $navigationLabels->contains('Auditoría'));

        $this->get(ActivityLogResource::getUrl('index'))
            ->assertStatus($allowed ? 200 : 403);
        $this->get(ActivityLogResource::getUrl('view', ['record' => $activity]))
            ->assertStatus($allowed ? 200 : 403);
    }
}
