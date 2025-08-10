<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;
use Illuminate\Support\Str;

class Pessoa extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organizacao_id',
        'primeiro_nome',
        'nome_meio',
        'ultimo_nome',
        'data_nascimento',
        'sexo',
        'naturalidade',
        'nacionalidade',
        'cpf',
        'rg',
        'phone',
        'whatsapp',
        'email',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'photo_path',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    protected static function booted()
    {
        static::saving(function ($pessoa) {
            $name = trim($pessoa->primeiro_nome . ' ' . ($pessoa->nome_meio ? $pessoa->nome_meio . ' ' : '') . $pessoa->ultimo_nome);
            $pessoa->normalized_name = Str::of($name)->ascii()->lower();
            $pessoa->digits_phone = preg_replace('/\D/', '', $pessoa->phone ?? '');
            $pessoa->digits_whatsapp = preg_replace('/\D/', '', $pessoa->whatsapp ?? '');
            $pessoa->digits_cpf = preg_replace('/\D/', '', $pessoa->cpf ?? '');
        });
    }

    public function getFullNameAttribute(): string
    {
        $middle = $this->nome_meio ? $this->nome_meio . ' ' : '';
        return trim($this->primeiro_nome . ' ' . $middle . $this->ultimo_nome);
    }
}
