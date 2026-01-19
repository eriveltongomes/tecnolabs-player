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
    Schema::table('projects', function (Blueprint $table) {
        $table->string('facade_image')->nullable()->after('city'); // Foto do prédio inteiro
    });

    Schema::table('media_categories', function (Blueprint $table) {
        $table->string('custom_path')->nullable()->after('type'); // Caminho do ZIP ou URL externa
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
