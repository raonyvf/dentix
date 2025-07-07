<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToClinic
{
    protected static function bootBelongsToClinic()
    {
        if (auth()->check()) {
            static::addGlobalScope('clinic', function (Builder $builder) {
                $builder->where('clinic_id', auth()->id() ? auth()->user()->clinic_id : null);
            });
        }
    }

    public function initializeBelongsToClinic()
    {
        $this->fillable[] = 'clinic_id';
    }
}
