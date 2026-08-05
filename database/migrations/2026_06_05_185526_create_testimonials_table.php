<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('client_location')->nullable();
            $table->string('client_image')->nullable();
            $table->enum('type', ['text', 'video'])->default('text');
            $table->text('content')->nullable();
            $table->string('video_file')->nullable(); // Direct video upload
            $table->string('video_thumbnail')->nullable();
            $table->integer('rating')->default(5);
            $table->string('product_name')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};