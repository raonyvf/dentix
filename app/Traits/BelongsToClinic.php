<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait BelongsToClinic
{
    protected static function bootBelongsToClinic()
    {
        $clinicId = app()->bound('clinic_id') ? app('clinic_id') : null;
        $user = auth()->user();
        if (! $user || is_null($clinicId) || $user->isOrganizationAdmin() || $user->isSuperAdmin()) {
            return;
        }

        $instance = new static;
        $column = null;

        if (Schema::hasColumn($instance->getTable(), 'clinica_id')) {
            $column = 'clinica_id';
        } elseif (Schema::hasColumn($instance->getTable(), 'clinic_id')) {
            $column = 'clinic_id';
        }

        if ($column) {
            static::addGlobalScope('clinic', function (Builder $builder) use ($instance, $clinicId, $column) {
                $builder->where($instance->getTable() . '.' . $column, $clinicId);
            });
        }
    }

    public function initializeBelongsToClinic()
    {
        if (Schema::hasColumn($this->getTable(), 'clinica_id')) {
            $this->fillable[] = 'clinica_id';
        } elseif (Schema::hasColumn($this->getTable(), 'clinic_id')) {
            $this->fillable[] = 'clinic_id';
        }
    }
}
