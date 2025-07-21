<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClinicUser extends Pivot
{
    protected $table = 'clinic_user';

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function employeeContracts()
    {
        return $this->hasMany(EmployeeContract::class);
    }
}
