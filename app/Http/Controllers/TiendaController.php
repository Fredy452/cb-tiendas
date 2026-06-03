<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
            ->take(3)
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
            ->paginate(9)
            ->withQueryString();

        $categories = $this->publicCategoriesWithCounts();

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
        $categories = $this->publicCategoriesWithCounts();

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

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeRegistrationUrls($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(fn ($query) => $query->where('is_active', true)),
            ],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
            'address' => ['required', 'string', 'max:255', 'not_regex:/[<>]/'],
            'latitude' => ['nullable', 'required_with:longitude', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'required_with:latitude', 'numeric', 'between:-180,180'],
            'description' => ['required', 'string', 'max:1200', 'not_regex:/[<>]/'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cover_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'description.not_regex' => 'La descripción solo puede contener texto plano, sin etiquetas HTML.',
            'address.not_regex' => 'La dirección solo puede contener texto plano, sin etiquetas HTML.',
            'latitude.required_with' => 'La latitud es obligatoria cuando se carga longitud.',
            'longitude.required_with' => 'La longitud es obligatoria cuando se carga latitud.',
            'logo.mimes' => 'El logo debe ser una imagen JPG, PNG o WEBP.',
            'cover_image.mimes' => 'La imagen de portada debe ser JPG, PNG o WEBP.',
        ]);

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

        $relatedStores = $relatedStoresQuery
            ->orderByDesc('is_featured')
            ->latest()
            ->take(3)
            ->get();

        return view('tiendas.show', compact('store', 'relatedStores'));
    }

    public function about(): View
    {
        return view('sobre-nosotros');
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

    private function normalizeRegistrationUrls(Request $request): void
    {
        $urlFields = ['website', 'facebook_url', 'instagram_url', 'tiktok_url'];
        $normalized = [];

        foreach ($urlFields as $field) {
            $value = trim((string) $request->input($field, ''));

            if ($value !== '' && ! Str::startsWith($value, ['http://', 'https://'])) {
                $value = 'https://' . ltrim($value, '/');
            }

            $normalized[$field] = $value === '' ? null : $value;
        }

        $request->merge($normalized);
    }

    private function storeRegistrationImage(Request $request, string $field, string $directory): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        return $request->file($field)->store($directory, 'public');
    }
}
