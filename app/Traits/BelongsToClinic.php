<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait BelongsToClinic
{
    protected static function bootBelongsToClinic()
    {
        if (! auth()->check() || is_null(auth()->user()->clinic_id)) {
            return;
        }

        $instance = new static;

        if (Schema::hasColumn($instance->getTable(), 'clinic_id')) {
            static::addGlobalScope('clinic', function (Builder $builder) use ($instance) {
                $builder->where($instance->getTable() . '.clinic_id', auth()->user()->clinic_id);
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
