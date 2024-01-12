<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revisao extends Model
{
    use HasFactory;
    protected $fillable = ['veiculo_id', 'data_revisao', 'descricao'];
    
    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }
}
