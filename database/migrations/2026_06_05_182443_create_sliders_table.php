<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('button_text')->default('SHOP NOW');
            $table->string('button_link')->default('/products');
            $table->string('image');           // Desktop image
            $table->string('mobile_image')->nullable();  // Mobile image
            $table->string('overlay_color')->default('#000000');
            $table->integer('overlay_opacity')->default(30); // 0-100
            $table->enum('text_position', ['left', 'center', 'right'])->default('left');
            $table->string('text_color')->default('#FFFFFF');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};