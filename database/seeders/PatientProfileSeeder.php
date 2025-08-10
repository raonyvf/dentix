<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perfil;
use App\Models\Permissao;
use App\Models\Organization;

class PatientProfileSeeder extends Seeder
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

        $perfil = Perfil::firstOrCreate([
            'nome' => 'Paciente',
            'organizacao_id' => $organization->id,
        ]);

        $modules = [
            'Portal',
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
    }
}
