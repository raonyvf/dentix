<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\BelongsToOrganization;
use App\Models\Profile;
use App\Models\Organization;
use App\Models\ClinicUser;

class User extends Authenticatable
{
    use HasFactory, Notifiable, BelongsToOrganization;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'photo_path',
        'password',
        'organization_id',
        'must_change_password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'must_change_password' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function clinics()
    {
        return $this->belongsToMany(Clinic::class)
            ->using(ClinicUser::class)
            ->withPivot('profile_id')
            ->withTimestamps();
    }

    public function profiles()
    {
        return $this->belongsToMany(Profile::class, 'clinic_user')
            ->using(ClinicUser::class)
            ->withPivot('clinic_id')
            ->withTimestamps();
    }

    public function isSuperAdmin(): bool
    {
        return $this->profiles()->where('nome', 'Super Administrador')->exists();
    }
}

