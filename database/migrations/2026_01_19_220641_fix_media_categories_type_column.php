<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media_categories', function (Blueprint $table) {
            // Altera a coluna para STRING (VARCHAR) para aceitar qualquer tipo (gallery, masterplan, etc)
            // O change() requer o pacote doctrine/dbal, se der erro, avise.
            // Se der erro de doctrine, a alternativa é usar DB::statement
            $table->string('type')->change(); 
        });
    }

    public function down(): void
    {
        // Reverte (opcional, mas boa prática)
        Schema::table('media_categories', function (Blueprint $table) {
            $table->string('type')->change();
        });
    }
};