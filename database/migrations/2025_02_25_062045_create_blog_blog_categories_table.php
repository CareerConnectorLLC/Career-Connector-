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
        Schema::create('blog_blog_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blog_category_id');
            $table->unsignedBigInteger('blog_id');
            $table->timestamps();

            $table->foreign('blog_category_id')->references('id')->on('blog_categories');
            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_blog_categories');
    }
};
