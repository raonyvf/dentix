<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Perfil;
use App\Models\Permission;
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
                'organization_id' => $organization->id,
            ],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'password' => Hash::make('password'),
                'organization_id' => $organization->id,
                'pessoa_id' => $pessoa->id,
            ]
        );

        $perfil = Perfil::firstOrCreate([
            'nome' => 'Super Administrador',
            'organization_id' => null,
        ]);

        $modules = [
            'Backend',
        ];

        foreach ($modules as $module) {
            Permission::updateOrCreate(
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
        $user->perfis()->sync([$perfil->id => ['clinic_id' => null]]);
    }
}
