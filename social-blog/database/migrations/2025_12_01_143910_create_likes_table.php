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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // field này sẽ quyết định là like này là like bài viết hay like comment (dùng )
            $table->unsignedBigInteger('likeable_id');  
            $table->string('likeable_type');
            // field này dùng để chắc chắn một user không like 2 lần 1 bài viết
            $table->unique(['user_id', 'likeable_id', 'likeable_type']);
            // field này để tăng tốc tìm kiếm
            $table->index(['likeable_id', 'likeable_type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
