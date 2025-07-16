<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['nome'];

    public function clinics()
    {
        return $this->hasMany(Clinic::class);
    }
}
