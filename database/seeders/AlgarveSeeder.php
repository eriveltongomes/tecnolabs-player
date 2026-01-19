<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // <--- Importante adicionar isso

class AlgarveSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar Usuário Admin (Se não existir)
        // Isso garante que você sempre consiga logar após um reset
        if (!User::where('email', 'erivelton@tecnolabs.info')->exists()) {
            User::create([
                'name' => 'Erivelton',
                'email' => 'erivelton@tecnolabs.info',
                'password' => Hash::make('password'), // Senha padrão: password
            ]);
            $this->command->info('Usuário Admin criado: erivelton@tecnolabs.info / password');
        }

        // 2. Criar o Projeto Algarve
        $projectId = DB::table('projects')->insertGetId([
            'name' => 'Algarve Residence',
            'slug' => 'algarve',
            'city' => 'Maceió',
            'theme_config' => json_encode([
                'primary_color' => '#0F2C4C',
                'secondary_color' => '#C5A065',
                'font_family' => 'Montserrat',
                'logo_url' => '/storage/algarve/logo.png',
                // fachada será preenchida via painel depois
            ]),
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Criar Categorias de Mídia (Com a nova Implantação e Tour ZIP)
        $cats = [
            ['name' => 'Implantação', 'type' => 'masterplan', 'sort' => 0],
            ['name' => 'Perspectivas', 'type' => 'image', 'sort' => 1],
            ['name' => 'Acompanhamento de Obra', 'type' => 'image', 'sort' => 2],
            ['name' => 'Tour Virtual 360', 'type' => '360', 'sort' => 3],
            ['name' => 'Vídeo Institucional', 'type' => 'video', 'sort' => 4],
        ];

        foreach ($cats as $cat) {
            DB::table('media_categories')->insert([
                'project_id' => $projectId,
                'name' => $cat['name'],
                'type' => $cat['type'],
                'sort_order' => $cat['sort'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Criar Unidades (Espelho de Vendas Fake)
        $units = [
            // Térreo
            ['101', 'available', 65.50],
            ['102', 'sold', 65.50],
            ['103', 'available', 68.00],
            ['104', 'reserved', 68.00],
            // 1º Andar
            ['201', 'available', 58.20],
            ['202', 'available', 58.20],
            ['203', 'blocked', 60.00],
            ['204', 'available', 60.00],
        ];

        foreach ($units as $u) {
            DB::table('units')->insert([
                'project_id' => $projectId,
                'block' => 'Torre A',
                'unit_number' => $u[0],
                'floor' => ($u[0] > 200) ? 1 : 0,
                'typology' => ($u[0] > 200) ? '2 Quartos' : 'Garden',
                'area' => $u[2],
                'status' => $u[1],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('Projeto Algarve e Usuário Admin criados com sucesso!');
    }
}