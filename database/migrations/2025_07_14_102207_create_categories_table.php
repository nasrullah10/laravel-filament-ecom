<?php
// database/migrations/xxxx_create_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            // ===== SIZE CHART FIELDS =====
            $table->boolean('has_size_chart')->default(false); // Size chart enable/disable
            $table->json('size_options')->nullable(); // ["XS", "S", "M", "L", "XL"]
            $table->json('size_chart')->nullable(); // JSON: [{size: "S", length: "30", shoulder: "14"}]
            $table->text('size_guide_text')->nullable(); // "All measurements are in inches..."
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};