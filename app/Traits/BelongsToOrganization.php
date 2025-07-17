<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait BelongsToOrganization
{
    protected static function bootBelongsToOrganization()
    {
        if (! auth()->check() || is_null(auth()->user()->organization_id)) {
            return;
        }

        $instance = new static;

        if (Schema::hasColumn($instance->getTable(), 'organization_id')) {
            static::addGlobalScope('organization', function (Builder $builder) use ($instance) {
                $builder->where($instance->getTable() . '.organization_id', auth()->user()->organization_id);
            });

            static::creating(function ($model) {
                if (is_null($model->organization_id)) {
                    $model->organization_id = auth()->user()->organization_id;
                }
            });
        }
    }

    public function initializeBelongsToOrganization()
    {
        if (Schema::hasColumn($this->getTable(), 'organization_id')) {
            $this->fillable[] = 'organization_id';
        }
    }
}
