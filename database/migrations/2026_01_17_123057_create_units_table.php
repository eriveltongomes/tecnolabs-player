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
    Schema::create('units', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_id')->constrained()->onDelete('cascade');
        $table->string('block')->nullable(); // Torre A, Quadra 1
        $table->string('unit_number'); // 101, Lote 05
        $table->integer('floor')->nullable(); // Andar
        $table->string('typology')->nullable(); // 3 Quartos, Garden
        $table->decimal('area', 8, 2)->nullable();
        $table->decimal('price', 12, 2)->nullable();
        // Status simples para pintar o mapa
        $table->enum('status', ['available', 'reserved', 'sold', 'blocked'])->default('available');
        $table->string('floorplan_image')->nullable(); // Caminho da planta específica
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
