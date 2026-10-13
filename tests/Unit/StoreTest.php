<?php

namespace Tests\Unit;

use App\Models\Store;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class StoreTest extends TestCase
{
    public function test_cover_url_is_resolved_once_per_store_instance(): void
    {
        $path = 'store-images/missing-cover.jpg';
        $disk = Mockery::mock(Filesystem::class);

        $disk->shouldReceive('exists')
            ->once()
            ->with($path)
            ->andReturn(true);

        Storage::shouldReceive('disk')
            ->once()
            ->with('public')
            ->andReturn($disk);

        $store = new Store(['img_path' => $path]);

        $this->assertSame($store->cover_url, $store->cover_url);
    }
}
