<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'idade', 'genero', 'quantidade_veiculos'];
    
    public function veiculos()
    {
        return $this->hasMany(Veiculo::class);
    }
}
