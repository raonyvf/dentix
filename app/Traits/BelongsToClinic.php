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

        if (Schema::hasColumn($instance->getTable(), 'clinic_id')) {
            static::addGlobalScope('clinic', function (Builder $builder) use ($instance, $clinicId) {
                $builder->where($instance->getTable() . '.clinic_id', $clinicId);
            });
        }
    }

    public function initializeBelongsToClinic()
    {
        if (Schema::hasColumn($this->getTable(), 'clinic_id')) {
            $this->fillable[] = 'clinic_id';
        }
    }
}
