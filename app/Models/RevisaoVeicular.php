<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     description="RevisaoVeicular model",
 *     title="RevisaoVeicular",
 *     @OA\Xml(
 *         name="RevisaoVeicular"
 *     )
 * )
 */
class RevisaoVeicular extends Model
{
    use HasFactory;

    /**
     * @OA\Property(
     *     format="int64",
     *     description="ID da revisão veicular",
     *     title="ID",
     * )
     *
     * @property integer $id
     */

    /**
     * @OA\Property(
     *     title="Veículo",
     *     description="Relacionamento com o veículo associado à revisão",
     *     property="veiculo",
     *     ref="#/components/schemas/Veiculo"
     * )
     */
    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }

    /**
     * @OA\Property(
     *     title="Pessoa",
     *     description="Relacionamento com a pessoa associada à revisão",
     *     property="pessoa",
     *     ref="#/components/schemas/Pessoa"
     * )
     */
    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
