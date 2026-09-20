<?php

namespace Tests\Feature;

use App\Mail\StoreCreated;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_expected_content(): void
    {
        [$category, $store] = $this->seedCatalog();

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeText('CB Tiendas')
            ->assertSeeText($store->name);

        $this->get(route('tiendas.index'))
            ->assertOk()
            ->assertSeeText($store->name)
            ->assertSeeText('Explorar negocios');

        $this->get(route('categorias'))
            ->assertOk()
            ->assertSeeText($category->name)
            ->assertSeeText('Explorar por categorías');

        $this->get(route('tiendas.show', $store->slug))
            ->assertOk()
            ->assertSeeText($store->name)
            ->assertSeeText('Nuestra historia');

        $this->get(route('emprendimientos.create'))
            ->assertOk()
            ->assertSeeText('Completá tus datos');

        $this->get(route('sobre-nosotros'))
            ->assertOk()
            ->assertSeeText('El corazón digital de Coronel Bogado');
    }

    public function test_explore_only_shows_approved_stores(): void
    {
        [$category, $visibleStore] = $this->seedCatalog();

        $hiddenStore = Store::query()->create([
            'name' => 'Pendiente sin publicar',
            'slug' => 'pendiente-sin-publicar',
            'description' => 'No deberia verse en el directorio publico.',
            'status' => 'pending',
        ]);

        $hiddenStore->categories()->attach($category->id);

        $this->get(route('tiendas.index'))
            ->assertOk()
            ->assertSeeText($visibleStore->name)
            ->assertDontSeeText($hiddenStore->name);
    }

    public function test_registration_creates_a_pending_store_and_attaches_the_category(): void
    {
        Mail::fake();
        Storage::fake('public');

        $category = Category::query()->create([
            'name' => 'Gastronomía',
            'slug' => 'gastronomia',
            'description' => 'Sabores y productos locales.',
            'is_active' => true,
            'display_order' => 1,
        ]);

        $response = $this->post(route('emprendimientos.store'), [
            'name' => 'Panaderia La Abuela',
            'category_id' => $category->id,
            'phone' => '0981 000 111',
            'email' => 'contacto@laabuela.test',
            'website' => 'laabuela.test/catalogo',
            'facebook_url' => 'facebook.com/panaderialaabuela',
            'instagram_url' => 'instagram.com/panaderialaabuela',
            'tiktok_url' => 'tiktok.com/@panaderialaabuela',
            'address' => 'Centro, Coronel Bogado',
            'latitude' => '-27.160530',
            'longitude' => '-56.241407',
            'description' => 'Panes, chipas y facturas recien horneadas.',
            'logo' => UploadedFile::fake()->image('logo.png', 400, 400),
            'cover_image' => UploadedFile::fake()->image('portada.jpg', 1200, 675),
        ]);

        $response
            ->assertRedirect(route('emprendimientos.create'))
            ->assertSessionHas('status');

        $store = Store::query()->where('slug', 'panaderia-la-abuela')->first();

        $this->assertNotNull($store);

        $this->assertDatabaseHas('stores', [
            'name' => 'Panaderia La Abuela',
            'slug' => 'panaderia-la-abuela',
            'email' => 'contacto@laabuela.test',
            'website' => 'https://laabuela.test/catalogo',
            'facebook_url' => 'https://facebook.com/panaderialaabuela',
            'instagram_url' => 'https://instagram.com/panaderialaabuela',
            'tiktok_url' => 'https://tiktok.com/@panaderialaabuela',
            'address' => 'Centro, Coronel Bogado',
            'latitude' => '-27.160530',
            'longitude' => '-56.241407',
            'status' => 'pending',
        ]);

        $this->assertNotNull($store->logo_path);
        $this->assertNotNull($store->img_path);
        $this->assertTrue(Storage::disk('public')->exists($store->logo_path));
        $this->assertTrue(Storage::disk('public')->exists($store->img_path));

        $this->assertDatabaseHas('category_store', [
            'store_id' => $store->id,
            'category_id' => $category->id,
        ]);

        Mail::assertSent(StoreCreated::class, function (StoreCreated $mail) use ($store): bool {
            return $mail->hasTo($store->email)
                && $mail->store->is($store);
        });
    }

    public function test_registration_rejects_html_in_description(): void
    {
        $category = Category::query()->create([
            'name' => 'Gastronomía',
            'slug' => 'gastronomia',
            'description' => 'Sabores y productos locales.',
            'is_active' => true,
            'display_order' => 1,
        ]);

        $response = $this->post(route('emprendimientos.store'), [
            'name' => 'Intento con HTML',
            'category_id' => $category->id,
            'phone' => '0981 000 222',
            'address' => 'Centro, Coronel Bogado',
            'description' => 'Texto normal <script>alert("xss")</script>',
        ]);

        $response->assertSessionHasErrors('description');

        $this->assertDatabaseMissing('stores', [
            'name' => 'Intento con HTML',
        ]);
    }

    public function test_guest_can_rate_an_approved_store(): void
    {
        [, $store] = $this->seedCatalog();

        $this->post(route('tiendas.ratings.store', $store->slug), [
            'rating' => 5,
        ])->assertRedirect();

        $this->assertDatabaseHas('store_ratings', [
            'store_id' => $store->id,
            'rating' => 5,
        ]);

        $this->get(route('tiendas.show', $store->slug))
            ->assertOk()
            ->assertSeeText('Calificaciones')
            ->assertSeeText('5.0')
            ->assertSeeText('100% recomienda');
    }

    public function test_guest_can_rate_a_store_only_once_from_the_same_ip(): void
    {
        [, $store] = $this->seedCatalog();

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->post(route('tiendas.ratings.store', $store->slug), ['rating' => 5])
            ->assertRedirect();

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->post(route('tiendas.ratings.store', $store->slug), ['rating' => 1])
            ->assertRedirect()
            ->assertSessionHas('rating_status', 'Ya registramos tu calificación para este negocio.');

        $this->assertDatabaseCount('store_ratings', 1);
        $this->assertDatabaseHas('store_ratings', [
            'store_id' => $store->id,
            'rating' => 5,
        ]);
    }

    public function test_store_rating_route_is_rate_limited_by_ip(): void
    {
        [, $store] = $this->seedCatalog();

        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.20'])
                ->post(route('tiendas.ratings.store', $store->slug), ['rating' => 4])
                ->assertRedirect();
        }

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.20'])
            ->post(route('tiendas.ratings.store', $store->slug), ['rating' => 4])
            ->assertTooManyRequests();
    }

    private function seedCatalog(): array
    {
        $category = Category::query()->create([
            'name' => 'Gastronomía',
            'slug' => 'gastronomia',
            'description' => 'Panaderias, cafeterias y sabores artesanales.',
            'is_active' => true,
            'display_order' => 1,
        ]);

        $store = Store::query()->create([
            'name' => 'Panadería El Sol',
            'slug' => 'panaderia-el-sol',
            'description' => 'Panes artesanales y especialidades horneadas todos los dias.',
            'address' => 'Centro, Coronel Bogado',
            'phone' => '0981 111 222',
            'status' => 'approved',
            'is_featured' => true,
        ]);

        $store->categories()->attach($category->id);

        return [$category, $store];
    }
}
