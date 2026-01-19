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
    Schema::create('media_files', function (Blueprint $table) {
        $table->id();
        $table->foreignId('media_category_id')->constrained()->onDelete('cascade');
        $table->string('title')->nullable();
        $table->string('path'); // Caminho do arquivo ou pasta (tour)
        $table->string('thumbnail_path')->nullable();
        $table->enum('file_type', ['image', 'video', 'youtube', 'vimeo', 'pano_folder']);
        $table->text('description')->nullable();
        $table->boolean('is_featured')->default(false); // Destaque na home?
        $table->integer('sort_order')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
