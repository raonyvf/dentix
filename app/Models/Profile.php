<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToClinic;

class Profile extends Model
{
    use BelongsToClinic;

    protected $fillable = [
        'clinic_id',
        'nome',
    ];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
