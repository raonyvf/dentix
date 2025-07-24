<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use App\Models\Permission;
use App\Models\Person;
use App\Models\Organization;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::firstOrCreate(
            ['cnpj' => '00000000000000'],
            ['nome_fantasia' => 'Default Organization']
        );

        $person = Person::firstOrCreate(
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
                'person_id' => $person->id,
            ]
        );

        $profile = Profile::firstOrCreate([
            'nome' => 'Super Administrador',
            'organization_id' => null,
        ]);

        $modules = [
            'Backend',
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

        $user->profiles()->syncWithoutDetaching([$profile->id => ['clinic_id' => null]]);
    }
}
