<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resposta extends Model
{
    protected $fillable = ['paciente_id','formulario_id','pergunta_id','resposta'];

    public function formulario()
    {
        return $this->belongsTo(Formulario::class);
    }

    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Patient::class, 'paciente_id');
    }
}
