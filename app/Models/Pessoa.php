<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;

class Pessoa extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organizacao_id',
        'primeiro_nome',
        'nome_meio',
        'ultimo_nome',
        'data_nascimento',
        'sexo',
        'naturalidade',
        'nacionalidade',
        'cpf',
        'rg',
        'phone',
        'whatsapp',
        'email',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'photo_path',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    public function getFullNameAttribute(): string
    {
        $middle = $this->nome_meio ? $this->nome_meio . ' ' : '';
        return trim($this->primeiro_nome . ' ' . $middle . $this->ultimo_nome);
    }
}
