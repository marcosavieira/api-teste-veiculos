<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Veiculo",
 *     title="Veiculo",
 *     description="Objeto de Veiculo",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="pessoa_id", type="integer"),
 *     @OA\Property(property="marca", type="string"),
 *     @OA\Property(property="tipo", type="string"),
 *     @OA\Property(property="modelo", type="string"),
 *     @OA\Property(property="placa", type="string"),
 * )
 */
class Veiculo extends Model
{
    use HasFactory;

    /**
     * @OA\Property(
     *     format="int64",
     *     description="ID do veículo",
     *     title="ID",
     * )
     *
     * @property integer $id
     */
    protected $fillable = ['pessoa_id', 'marca', 'tipo', 'modelo', 'placa'];

    /**
     * @OA\Property(
     *     title="Pessoa",
     *     description="Relacionamento com a pessoa dona do veículo",
     *     property="pessoa",
     *     ref="#/components/schemas/Pessoa"
     * )
     */
    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    /**
     * @OA\Property(
     *     title="Revisões",
     *     description="Relacionamento com as revisões do veículo",
     *     property="revisoes",
     *     ref="#/components/schemas/RevisaoVeicular"
     * )
     */
    public function revisoes()
    {
        return $this->hasMany(RevisaoVeicular::class);
    }
}
