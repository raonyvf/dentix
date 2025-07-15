<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use App\Models\Permission;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password')
            ]
        );

        $profile = Profile::firstOrCreate([
            'nome' => 'Administrador',
            'clinic_id' => null,
        ]);

        $modules = [
            'Pacientes',
            'Agenda',
            'Prontuários',
            'Profissionais',
            'Estoque',
            'Financeiro',
            'Clínicas',
            'Cadeiras',
            'Usuários',
            'Perfis',
        ];

        foreach ($modules as $module) {
            Permission::updateOrCreate(
                [
                    'profile_id' => $profile->id,
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

        $user->profile()->associate($profile);
        $user->save();
    }
}
