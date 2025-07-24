<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use App\Models\Permission;
use App\Models\Person;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $person = Person::firstOrCreate(
            [
                'email' => 'admin@example.com',
                'organization_id' => 1,
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
                'organization_id' => $person->organization_id,
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
