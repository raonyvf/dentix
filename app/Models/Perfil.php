<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Models\Organization;
use App\Models\Permissao;

class Perfil extends Model
{
    use BelongsToOrganization;

    protected $table = 'perfis';

    protected $fillable = [
        'organizacao_id',
        'nome',
    ];

    public function permissoes()
    {
        return $this->hasMany(Permissao::class);
    }

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'clinica_usuario', 'perfil_id', 'usuario_id')
            ->withPivot('clinica_id')
            ->withTimestamps();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organizacao_id');
    }
}
