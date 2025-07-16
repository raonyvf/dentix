<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\BelongsToOrganization;
use App\Models\Profile;
use App\Models\Organization;

class User extends Authenticatable
{
    use HasFactory, Notifiable, BelongsToOrganization;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'photo_path',
        'password',
        'clinic_id',
        'organization_id',
        'profile_id',
        'must_change_password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'must_change_password' => 'boolean',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}

