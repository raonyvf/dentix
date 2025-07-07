<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToClinic
{
    protected static function bootBelongsToClinic()
    {
        if (! auth()->check()) {
            return;
        }

        $instance = new static;

        if (\Schema::hasColumn($instance->getTable(), 'clinic_id')) {
            static::addGlobalScope('clinic', function (Builder $builder) use ($instance) {
                $builder->where($instance->getTable() . '.clinic_id', auth()->user()->clinic_id);
            });
        }
    }

    public function initializeBelongsToClinic()
    {
        if (\Schema::hasColumn($this->getTable(), 'clinic_id')) {
            $this->fillable[] = 'clinic_id';
        }
    }
}
