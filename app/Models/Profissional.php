<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Models\Person;
use App\Models\User;

class Profissional extends Model
{
    use BelongsToOrganization;

    /**
     * The table associated with the model.
     */
    protected $table = 'profissionais';

    protected $fillable = [
        'organization_id',
        'person_id',
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
