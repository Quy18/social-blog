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
        Schema::create('post_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->string('url');
            $table->enum('type', ['image', 'video'])->default('image');
            // 3 trường BẮT BUỘC phải có
            $table->unsignedSmallInteger('order')->default(0);           // sắp xếp
            $table->boolean('is_featured')->default(false);              // ảnh bìa
            $table->string('thumbnail_url')->nullable();                 // cho video

            $table->string('alt')->nullable();
            $table->string('caption')->nullable();
            $table->timestamps();

            $table->index(['post_id', 'order']);
            $table->index(['post_id', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_media');
    }
};
