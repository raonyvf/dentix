<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Models\Organization;

class Perfil extends Model
{
    use BelongsToOrganization;

    protected $table = 'perfis';

    protected $fillable = [
        'organization_id',
        'nome',
    ];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'clinica_usuario', 'perfil_id', 'usuario_id')
            ->withPivot('clinica_id')
            ->withTimestamps();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
