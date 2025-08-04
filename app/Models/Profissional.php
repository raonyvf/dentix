<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use App\Models\Pessoa;
use App\Models\Usuario;
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
        'pessoa_id',
        'usuario_id',
        'numero_funcionario',
        'email_corporativo',
        'data_admissao',
        'data_demissao',
        'tipo_contrato',
        'data_inicio_contrato',
        'data_fim_contrato',
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
        'total_horas_semanais' => 'integer',
        'salario_fixo' => 'decimal:2',
        'comissoes' => 'array',
        'conta' => 'array',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function horariosTrabalho()
    {
        return $this->hasMany(ProfissionalHorario::class);
    }

    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinica_profissional')->withTimestamps();
    }
}
