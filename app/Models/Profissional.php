<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;

class Profissional extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'nome',
        'nome_meio',
        'ultimo_nome',
        'data_nascimento',
        'sexo',
        'naturalidade',
        'nacionalidade',
        'foto_path',
        'cpf',
        'rg',
        'email',
        'telefone',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
