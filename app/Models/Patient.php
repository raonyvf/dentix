<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Models\User;
use App\Models\Person;

class Patient extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'user_id',
        'person_id',
        'menor_idade',
        'responsavel_nome',
        'responsavel_nome_meio',
        'responsavel_ultimo_nome',
        'responsavel_cpf',
    ];

    protected $casts = [
        'menor_idade' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
