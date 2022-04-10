<?php

namespace App\Models;

use Eloquent as Model;



/**
 * @SWG\Definition(
 *      definition="PublicoAlvo",
 *      required={"publico", "idade_min", "idade_max", "data_criacao", "data_atualizacao"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="publico",
 *          description="publico",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="idade_min",
 *          description="idade_min",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="idade_max",
 *          description="idade_max",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="data_criacao",
 *          description="data_criacao",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class PublicoAlvo extends Model
{


    public $table = 'publico_alvo';
    
    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = 'data_atualizacao';




    public $fillable = [
        'publico',
        'idade_min',
        'idade_max'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'publico' => 'string',
        'idade_min' => 'integer',
        'idade_max' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'publico' => 'required|string|max:150',
        'idade_min' => 'required|integer',
        'idade_max' => 'required|integer',
        'data_criacao' => 'required',
        'data_atualizacao' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function historia()
    {
        return $this->hasMany(\App\Models\Historium::class, 'publico_alvo_id');
    }
}
