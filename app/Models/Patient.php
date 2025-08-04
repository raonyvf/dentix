<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Models\Usuario;
use App\Models\Pessoa;

class Patient extends Model
{
    use BelongsToOrganization;

    protected $table = 'pacientes';

    protected $fillable = [
        'organizacao_id',
        'usuario_id',
        'pessoa_id',
        'menor_idade',
        'responsavel_first_name',
        'responsavel_middle_name',
        'responsavel_last_name',
        'responsavel_cpf',
    ];

    protected $casts = [
        'menor_idade' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organizacao_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
