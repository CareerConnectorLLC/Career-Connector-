<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_identifier')->unique()->comment('e.g., home, about, products.show, blog.index');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('keywords')->nullable()->comment('Comma-separated meta keywords (low SEO impact)');
            $table->string('canonical_url')->nullable()->comment('Full canonical URL for the page');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
