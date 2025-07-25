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
use App\Models\Permission;
use App\Models\Patient;
use App\Models\Person;

class User extends Authenticatable
{
    use HasFactory, Notifiable, BelongsToOrganization;

    protected $fillable = [
        'email',
        'password',
        'organization_id',
        'dentista',
        'cro',
        'cargo',
        'especialidade',
        'salario_base',
        'must_change_password',
        'person_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'must_change_password' => 'boolean',
        'dentista' => 'boolean',
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

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }


    public function isSuperAdmin(): bool
    {
        return $this->profiles()->where('nome', 'Super Administrador')->exists();
    }

    public function isOrganizationAdmin(): bool
    {
        return $this->profiles()
            ->where('nome', 'Administrador')
            ->exists();
    }

    public function hasAnyModulePermission(string $module): bool
    {

        $profileQuery = $this->profiles();

        if (! $this->isOrganizationAdmin()) {
            $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;

            if ($clinicId) {
                $profileQuery->where(function ($query) use ($clinicId) {
                    $query->where('clinic_user.clinic_id', $clinicId)
                        ->orWhereNull('clinic_user.clinic_id');
                });
            }
        }

        $profileIds = $profileQuery->pluck('profiles.id');

        if ($profileIds->isEmpty()) {
            return false;
        }

        return Permission::whereIn('profile_id', $profileIds)
            ->where('modulo', $module)
            ->where(function ($q) {
                $q->where('leitura', true)
                    ->orWhere('escrita', true)
                    ->orWhere('atualizacao', true)
                    ->orWhere('exclusao', true);
            })->exists();
    }
}