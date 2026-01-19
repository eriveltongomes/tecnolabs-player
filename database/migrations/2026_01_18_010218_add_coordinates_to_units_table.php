<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            // Guardaremos como string para aceitar "50%" ou decimais se precisar
            $table->string('map_x')->nullable()->after('status'); 
            $table->string('map_y')->nullable()->after('map_x');
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['map_x', 'map_y']);
        });
    }
};