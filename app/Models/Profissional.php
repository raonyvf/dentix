<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Models\Person;

class Profissional extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'person_id',
    ];

    protected $casts = [];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
