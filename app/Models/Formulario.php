<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    protected $fillable = ['nome'];

    public function perguntas()
    {
        return $this->hasMany(Pergunta::class);
    }

    public function respostas()
    {
        return $this->hasMany(Resposta::class);
    }
}
