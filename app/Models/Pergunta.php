<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    protected $fillable = ['formulario_id','enunciado','tipo','opcoes','ordem'];

    public function formulario()
    {
        return $this->belongsTo(Formulario::class);
    }

    public function respostas()
    {
        return $this->hasMany(Resposta::class);
    }

    public function opcoesArray(): array
    {
        return $this->opcoes ? array_map('trim', explode(',', $this->opcoes)) : [];
    }
}
