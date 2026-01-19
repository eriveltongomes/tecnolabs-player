<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            
            // Relacionamentos
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_category_id')->constrained()->cascadeOnDelete();
            
            // Dados do Arquivo
            $table->string('file_type'); // image, video, 360
            $table->string('path'); // caminho do arquivo
            $table->string('description')->nullable(); // link externo ou descrição
            
            // Pinos de Mapa (Opcional, mas estava no Model)
            $table->string('map_x')->nullable();
            $table->string('map_y')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};