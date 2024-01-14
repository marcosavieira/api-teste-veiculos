<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisaoVeicular extends Model
{
    use HasFactory;

    protected $fillable = ['marca', 'veiculo_id', 'data_manutencao', 'pessoa_id'];

    // Relacionamento com Veiculo
    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }

    // Relacionamento com Pessoa
    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
