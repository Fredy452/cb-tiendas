<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TiendaController extends Controller
{
    public function home(): View
    {
        $featuredStores = Store::query()
            ->publicVisible()
            ->with('categories')
            ->orderByDesc('is_featured')
            ->latest()
            ->take(6)
            ->get();

        $categories = $this->publicCategoriesWithCounts()->take(6);
        $storesCount = Store::query()->publicVisible()->count();

        return view('welcome', compact('featuredStores', 'categories', 'storesCount'));
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $selectedCategoryIds = $this->resolveSelectedCategoryIds($request);
        $selectedScope = in_array($request->input('scope'), ['featured', 'new'], true)
            ? $request->input('scope')
            : 'all';

        $stores = Store::query()
            ->publicVisible()
            ->with('categories')
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $searchQuery) use ($search) {
                    $searchQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhereHas('categories', function (Builder $categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($selectedCategoryIds !== [], function (Builder $query) use ($selectedCategoryIds) {
                $query->whereHas('categories', function (Builder $categoryQuery) use ($selectedCategoryIds) {
                    $categoryQuery->whereIn('categories.id', $selectedCategoryIds);
                });
            })
            ->when($selectedScope === 'featured', function (Builder $query) {
                $query->where('is_featured', true);
            })
            ->when($selectedScope === 'new', function (Builder $query) {
                $query->where('created_at', '>=', now()->subDays(30));
            })
            ->orderByDesc('is_featured')
            ->latest()
            ->paginate(6)
            ->withQueryString();

        // Mostrar solo las categorías con uno o mas negocios públicos visibles
        $categories = $this->publicCategoriesWithCounts()->filter(fn (Category $category) => $category->public_stores_count > 0);

        Log::info('Mostrando listado de tiendas', [
            'search' => $search,
            'selected_category_ids' => $selectedCategoryIds,
            'selected_scope' => $selectedScope,
            'result_count' => $stores->total(),
        ]);

        return view('tienda', compact('stores', 'categories', 'search', 'selectedCategoryIds', 'selectedScope'));
    }

    public function categorias(): View
    {
        $categories = Category::query()
            ->active()
            ->withCount([
                'stores as public_stores_count' => fn (Builder $query) => $query->publicVisible(),
            ])
            ->having('public_stores_count', '>=', 1)
            ->orderBy('public_stores_count', 'desc')
            ->orderBy('name')
            ->paginate(6)
            ->withQueryString();

        return view('categorias', compact('categories'));
    }

    public function create(): View
    {
        $categories = Category::query()
            ->active()
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('emprendimientos.create', compact('categories'));
    }

    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $store = Store::query()->create([
            'name' => $validated['name'],
            'slug' => $this->generateStoreSlug($validated['name']),
            'description' => $validated['description'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'website' => $validated['website'] ?? null,
            'facebook_url' => $validated['facebook_url'] ?? null,
            'instagram_url' => $validated['instagram_url'] ?? null,
            'tiktok_url' => $validated['tiktok_url'] ?? null,
            'address' => $validated['address'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'logo_path' => $this->storeRegistrationImage($request, 'logo', 'store-logos'),
            'img_path' => $this->storeRegistrationImage($request, 'cover_image', 'store-images'),
            'status' => 'pending',
        ]);

        $store->categories()->attach($validated['category_id']);

        return to_route('emprendimientos.create')->with(
            'status',
            '¡Listo! Tu emprendimiento fue enviado para revisión. Te avisaremos cuando esté aprobado.'
        );
    }

    /**
     * Funcion para mostrar los detalles de una tienda
     *
     * @param string $store
     * @return View
     */
    public function show(string $store): View
    {
        $store = Store::query()
            ->publicVisible()
            ->with('categories')
            ->where(function (Builder $query) use ($store) {
                $query->where('slug', $store);

                if (ctype_digit($store)) {
                    $query->orWhereKey((int) $store);
                }
            })
            ->firstOrFail();
            // Evita el spam de visitas usando una clave única en caché por IP
            $cacheKey = 'viewed_store_' . $store->getKey() . '_' . request()->ip();

            if (! cache()->has($cacheKey)) {
                $store->increment('views_count');
                // Guarda en caché que este usuario ya visitó la tienda durante los próximos 60 minutos
                cache()->put($cacheKey, true, now()->addHours(1));
            }
        Log::info('Mostrando tienda al público', ['store_id' => $store->getKey(), 'store_name' => $store->name]);
        $relatedStoresQuery = Store::query()
            ->publicVisible()
            ->with('categories')
            ->whereKeyNot($store->getKey());

        if ($store->categories->isNotEmpty()) {
            $relatedStoresQuery->whereHas('categories', function (Builder $query) use ($store) {
                $query->whereIn('categories.id', $store->categories->modelKeys());
            });
        }

        // Normalizamos phone
        $waPhone = null;
        if ($store->phone) {
            $waPhone = preg_replace('/\D+/', '', $store->phone);
            if (!str_starts_with($waPhone, '595')) {
                $waPhone = '595' . ltrim($waPhone, '0');
            }
        }


        // Mensajes para contacto en WhatsApp y correo
        $socialMessages = [
            'waMessage' => "¡Hola! {$store->name} descubrí este negocio desde la plataforma Coronel Bogado Tiendas, estoy interesado en sus productos/servicios. ¿Podrías darme más información? Gracias.",
            'emailSubject' => "Consulta sobre {$store->name}",
            'emailBody' => "Hola, descubrí tu negocio desde la plataforma Coronel Bogado Tiendas y estoy interesado en tus productos/servicios. ¿Podrías darme más información? Gracias.",
        ];

        $waMessage = $socialMessages['waMessage'];
        $emailSubject = $socialMessages['emailSubject'];
        $emailBody = $socialMessages['emailBody'];

            $socialIcons = [
                'facebook'  => 'fa-brands fa-facebook',
                'instagram' => 'fa-brands fa-instagram',
                'tiktok'    => 'fa-brands fa-tiktok',
            ];


        $relatedStores = $relatedStoresQuery
            ->orderByDesc('is_featured')
            ->latest()
            ->take(8)
            ->get();

        return view('tiendas.show', compact('store', 'relatedStores', 'waPhone', 'waMessage', 'emailSubject', 'emailBody', 'socialIcons'));
    }

    public function about(): View
    {
        return view('sobre-nosotros');
    }

    public function searchSuggestions(Request $request): \Illuminate\Http\JsonResponse
    {
        $q = trim((string) $request->input('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $stores = Store::query()
            ->publicVisible()
            ->with('categories:id,name')
            ->where(function (Builder $query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            })
            ->select(['id', 'name', 'slug', 'description', 'logo_path', 'img_path'])
            ->orderByDesc('is_featured')
            ->latest()
            ->limit(6)
            ->get()
            ->map(fn (Store $store) => [
                'name' => $store->name,
                'url' => route('tiendas.show', $store->slug ?: $store->getKey()),
                'description' => $store->description
                    ? Str::limit(trim(html_entity_decode(strip_tags($store->description), ENT_QUOTES, 'UTF-8')), 80)
                    : null,
                'category' => $store->categories->first()?->name,
                'thumbnail' => $store->logo_url ?? $store->cover_url,
            ]);

        return response()->json($stores);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function publicCategoriesWithCounts(): Collection
    {
        return Category::query()
            ->active()
            ->withCount([
                'stores as public_stores_count' => fn (Builder $query) => $query->publicVisible(),
            ])
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
    }

    private function resolveSelectedCategoryIds(Request $request): array
    {
        $filters = collect(Arr::wrap($request->input('categories', [])))
            ->merge($request->filled('category') ? [$request->input('category')] : [])
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => (string) $value)
            ->values();

        if ($filters->isEmpty()) {
            return [];
        }

        $numericIds = $filters
            ->filter(fn (string $value) => ctype_digit($value))
            ->map(fn (string $value) => (int) $value)
            ->all();

        $slugs = $filters
            ->reject(fn (string $value) => ctype_digit($value))
            ->all();

        return Category::query()
            ->active()
            ->where(function (Builder $query) use ($numericIds, $slugs) {
                if ($numericIds !== []) {
                    $query->whereIn('id', $numericIds);
                }

                if ($slugs !== []) {
                    if ($numericIds !== []) {
                        $query->orWhereIn('slug', $slugs);
                    } else {
                        $query->whereIn('slug', $slugs);
                    }
                }
            })
            ->pluck('id')
            ->all();
    }

    private function generateStoreSlug(string $name): string
    {
        $baseSlug = Str::slug($name) ?: 'negocio-local';
        $slug = $baseSlug;
        $suffix = 2;

        while (Store::query()->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function storeRegistrationImage(Request $request, string $field, string $directory): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        return $request->file($field)->store($directory, 'public');
    }
}
