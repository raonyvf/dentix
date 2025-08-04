<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Perfil;
use App\Models\Permissao;
use App\Models\Pessoa;
use App\Models\Organization;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::firstOrCreate(
            ['cnpj' => '00000000000000'],
            [
                'nome_fantasia' => 'Default Organization',
                'timezone' => config('app.timezone'),
            ]
        );

        $pessoa = Pessoa::firstOrCreate(
            [
                'email' => 'admin@example.com',
                'organizacao_id' => $organization->id,
            ],
            [
                'primeiro_nome' => 'Admin',
                'ultimo_nome' => 'Usuario',
            ]
        );

        $usuario = Usuario::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'password' => Hash::make('password'),
                'organizacao_id' => $organization->id,
                'pessoa_id' => $pessoa->id,
            ]
        );

        $perfil = Perfil::firstOrCreate(
            [
                'nome' => 'Super Administrador',
                'organizacao_id' => $organization->id,
            ]
        );

        $modules = [
            'Backend',
        ];

        foreach ($modules as $module) {
            Permissao::updateOrCreate(
                [
                    'perfil_id' => $perfil->id,
                    'modulo' => $module,
                ],
                [
                    'leitura' => true,
                    'escrita' => true,
                    'atualizacao' => true,
                    'exclusao' => true,
                ]
            );
        }

        // Ensure the admin user only has the Super Administrador perfil
        $usuario->perfis()->sync([$perfil->id => ['clinica_id' => null]]);
    }
}
