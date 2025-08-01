<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;

class Person extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'first_name',
        'middle_name',
        'last_name',
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
        $middle = $this->middle_name ? $this->middle_name . ' ' : '';
        return trim($this->first_name . ' ' . $middle . $this->last_name);
    }
}
