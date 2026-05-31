<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'description' => 'Panes, chipas y facturas recien horneadas.',
        ]);

        $response
            ->assertRedirect(route('emprendimientos.create'))
            ->assertSessionHas('status');

        $store = Store::query()->where('slug', 'panaderia-la-abuela')->first();

        $this->assertNotNull($store);

        $this->assertDatabaseHas('stores', [
            'name' => 'Panaderia La Abuela',
            'slug' => 'panaderia-la-abuela',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('category_store', [
            'store_id' => $store->id,
            'category_id' => $category->id,
        ]);
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
            'description' => 'Texto normal <script>alert("xss")</script>',
        ]);

        $response->assertSessionHasErrors('description');

        $this->assertDatabaseMissing('stores', [
            'name' => 'Intento con HTML',
        ]);
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
