<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\Permission;

class PatientProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profile = Profile::firstOrCreate([
            'nome' => 'Paciente',
            'organization_id' => null,
        ]);

        $modules = [
            'Portal',
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
    }
}
