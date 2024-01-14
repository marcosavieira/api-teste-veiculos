<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     description="Pessoa model",
 *     title="Pessoa",
 *     @OA\Xml(
 *         name="Pessoa"
 *     )
 * )
 */
class Pessoa extends Model
{
    use HasFactory;

    /**
     * @OA\Property(
     *     format="int64",
     *     description="ID da pessoa",
     *     title="ID",
     * )
     *
     * @property integer $id
     */
    protected $fillable = ['nome', 'idade', 'genero', 'quantidade_veiculos'];

    /**
     * @OA\Property(
     *     title="Veículos",
     *     description="Relacionamento com os veículos da pessoa",
     *     @OA\Items(ref="#/components/schemas/Veiculo")
     * )
     *
     * @var array
     */
    public function veiculos()
    {
        return $this->hasMany(Veiculo::class);
    }
}
