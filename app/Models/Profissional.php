<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Models\Person;
use App\Models\User;
use App\Models\ProfissionalHorario;

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
        'numero_funcionario',
        'email_corporativo',
        'data_admissao',
        'data_demissao',
        'tipo_contrato',
        'data_inicio_contrato',
        'data_fim_contrato',
        'carga_horaria',
        'total_horas_semanais',
        'regime_trabalho',
        'funcao',
        'cargo',
        'cro',
        'cro_uf',
        'salario_fixo',
        'salario_periodo',
        'comissoes',
        'conta',
        'chave_pix',
    ];

    protected $casts = [
        'data_admissao' => 'date',
        'data_demissao' => 'date',
        'data_inicio_contrato' => 'date',
        'data_fim_contrato' => 'date',
        'carga_horaria' => 'integer',
        'total_horas_semanais' => 'integer',
        'salario_fixo' => 'decimal:2',
        'comissoes' => 'array',
        'conta' => 'array',
    ];

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

    public function horariosTrabalho()
    {
        return $this->hasMany(ProfissionalHorario::class);
    }
}
