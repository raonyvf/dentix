<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perfil;
use App\Models\Permission;

class PatientProfileSeeder extends Seeder
{
    public function run(): void
    {
        $perfil = Perfil::firstOrCreate([
            'nome' => 'Paciente',
            'organizacao_id' => null,
        ]);

        $modules = [
            'Portal',
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
    }
}
