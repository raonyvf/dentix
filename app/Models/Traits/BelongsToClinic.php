<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait BelongsToClinic
{
    protected static function bootBelongsToClinic()
    {
        static::addGlobalScope('clinic', function (Builder $builder) {
            if ($clinicId = app()->get('clinicId')) {
                $builder->where('clinic_id', $clinicId);
            }
        });

        static::creating(function (Model $model) {
            if ($clinicId = app()->get('clinicId')) {
                $model->clinic_id = $clinicId;
            }
        });
    }
}
