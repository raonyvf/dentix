<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Models\User;
use App\Models\Person;

class Patient extends Model
{
    use BelongsToOrganization;

    protected $table = 'pacientes';

    protected $fillable = [
        'organization_id',
        'user_id',
        'person_id',
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
