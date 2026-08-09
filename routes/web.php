<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiendaController;
use App\Models\Category;
use App\Models\Store;

Route::get('/', [TiendaController::class, 'home'])->name('home');
Route::get('/tiendas', [TiendaController::class, 'index'])->name('tiendas.index');
Route::get('/tiendas/{store}', [TiendaController::class, 'show'])->name('tiendas.show');
Route::get('/categorias', [TiendaController::class, 'categorias'])->name('categorias');
Route::get('/emprendimientos/registrar', [TiendaController::class, 'create'])->name('emprendimientos.create');
Route::post('/emprendimientos/registrar', [TiendaController::class, 'store'])->name('emprendimientos.store');
Route::get('/sobre-nosotros', [TiendaController::class, 'about'])->name('sobre-nosotros');

Route::get('/sitemap.xml', function () {
	$urls = collect([
		[
			'loc' => route('home'),
			'lastmod' => now()->toDateString(),
			'changefreq' => 'daily',
			'priority' => '1.0',
		],
		[
			'loc' => route('tiendas.index'),
			'lastmod' => now()->toDateString(),
			'changefreq' => 'daily',
			'priority' => '0.9',
		],
		[
			'loc' => route('categorias'),
			'lastmod' => now()->toDateString(),
			'changefreq' => 'weekly',
			'priority' => '0.8',
		],
		[
			'loc' => route('sobre-nosotros'),
			'lastmod' => now()->toDateString(),
			'changefreq' => 'monthly',
			'priority' => '0.6',
		],
	])
		->merge(
			Store::query()
                ->publicVisible()
				->select(['id', 'name', 'slug', 'updated_at', 'logo_path', 'img_path'])
                ->latest('updated_at')
                ->get()
				->map(function (Store $store) {
					$image = $store->cover_url ?: $store->logo_url;

					return [
						'loc' => route('tiendas.show', $store->slug ?: $store->getKey()),
						'lastmod' => optional($store->updated_at)->toDateString() ?: now()->toDateString(),
						'changefreq' => 'weekly',
						'priority' => '0.7',
						'image' => $image ? url($image) : null,
						'image_title' => $store->name,
					];
				})
		)
		->merge(
			Category::query()
				->active()
				->select(['id', 'slug', 'updated_at'])
				->latest('updated_at')
				->get()
				->map(fn (Category $category) => [
					'loc' => route('tiendas.index', ['category' => $category->slug ?: $category->getKey()]),
					'lastmod' => optional($category->updated_at)->toDateString() ?: now()->toDateString(),
					'changefreq' => 'weekly',
					'priority' => '0.6',
				])
		);

	$xml = view('sitemap', ['urls' => $urls])->render();

	return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
})->name('sitemap');
