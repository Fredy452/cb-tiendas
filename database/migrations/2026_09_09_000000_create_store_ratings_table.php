<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->string('visitor_hash', 64);
            $table->timestamps();

            $table->unique(['store_id', 'visitor_hash']);
            $table->index(['store_id', 'rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_ratings');
    }
};
