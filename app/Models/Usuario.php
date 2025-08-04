<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\BelongsToOrganization;
use App\Models\Perfil;
use App\Models\Organization;
use App\Models\ClinicUser;
use App\Models\Permission;
use App\Models\Patient;
use App\Models\Pessoa;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, BelongsToOrganization;

    protected $table = 'usuarios';

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
        'pessoa_id',
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
        return $this->belongsToMany(Clinic::class, 'clinic_user', 'usuario_id', 'clinic_id')
            ->using(ClinicUser::class)
            ->withPivot('perfil_id')
            ->withTimestamps();
    }

    public function perfis()
    {
        return $this->belongsToMany(Perfil::class, 'clinic_user', 'usuario_id', 'perfil_id')
            ->using(ClinicUser::class)
            ->withPivot('clinic_id')
            ->withTimestamps();
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }


    public function isSuperAdmin(): bool
    {
        return $this->perfis()->where('nome', 'Super Administrador')->exists();
    }

    public function isOrganizationAdmin(): bool
    {
        return $this->perfis()
            ->where('nome', 'Administrador')
            ->exists();
    }

    public function hasAnyModulePermission(string $module): bool
    {

        $perfilQuery = $this->perfis();

        if (! $this->isOrganizationAdmin()) {
            $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;

            if ($clinicId) {
                $perfilQuery->where(function ($query) use ($clinicId) {
                    $query->where('clinic_user.clinic_id', $clinicId)
                        ->orWhereNull('clinic_user.clinic_id');
                });
            }
        }

        $perfilIds = $perfilQuery->pluck('perfis.id');

        if ($perfilIds->isEmpty()) {
            return false;
        }

        return Permission::whereIn('perfil_id', $perfilIds)
            ->where('modulo', $module)
            ->where(function ($q) {
                $q->where('leitura', true)
                    ->orWhere('escrita', true)
                    ->orWhere('atualizacao', true)
                    ->orWhere('exclusao', true);
            })->exists();
    }
}