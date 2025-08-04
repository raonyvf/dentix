<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait BelongsToOrganization
{
    protected static function bootBelongsToOrganization()
    {
        if (! auth()->check() || is_null(auth()->user()->organization_id) || auth()->user()->isSuperAdmin()) {
            return;
        }

        $instance = new static;
        $column = null;

        if (Schema::hasColumn($instance->getTable(), 'organizacao_id')) {
            $column = 'organizacao_id';
        } elseif (Schema::hasColumn($instance->getTable(), 'organization_id')) {
            $column = 'organization_id';
        }

        if ($column) {
            static::addGlobalScope('organization', function (Builder $builder) use ($instance, $column) {
                $builder->where($instance->getTable() . '.' . $column, auth()->user()->organization_id);
            });

            static::creating(function ($model) use ($column) {
                if (is_null($model->$column)) {
                    $model->$column = auth()->user()->organization_id;
                }
            });
        }
    }

    public function initializeBelongsToOrganization()
    {
        if (Schema::hasColumn($this->getTable(), 'organizacao_id')) {
            $this->fillable[] = 'organizacao_id';
        } elseif (Schema::hasColumn($this->getTable(), 'organization_id')) {
            $this->fillable[] = 'organization_id';
        }
    }
}
