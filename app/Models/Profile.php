<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Models\Organization;

class Profile extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'nome',
    ];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'clinic_user')->withPivot('clinic_id')->withTimestamps();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
