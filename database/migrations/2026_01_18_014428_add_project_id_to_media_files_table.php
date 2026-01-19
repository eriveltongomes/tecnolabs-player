<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media_files', function (Blueprint $table) {
            // Adiciona a ligação com o Projeto
            $table->foreignId('project_id')->nullable()->after('id')->constrained('projects')->onDelete('cascade');

            // Garante que tenha o tipo de arquivo
            if (!Schema::hasColumn('media_files', 'file_type')) {
                $table->string('file_type')->default('image')->after('media_category_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('media_files', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn(['project_id', 'file_type']);
        });
    }
};