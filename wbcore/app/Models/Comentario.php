<?php

namespace App\Models;

use Eloquent as Model;



/**
 * @SWG\Definition(
 *      definition="Comentario",
 *      required={"capitulo_id", "comentario", "usuario_id", "data_criacao", "data_atualizacao"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="capitulo_id",
 *          description="capitulo_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="comentario",
 *          description="comentario",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="usuario_id",
 *          description="usuario_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="data_criacao",
 *          description="data_criacao",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="data_atualizacao",
 *          description="data_atualizacao",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Comentario extends Model
{


    public $table = 'comentarios';
    
    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = 'data_atualizacao';




    public $fillable = [
        'capitulo_id',
        'comentario',
        'usuario_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'capitulo_id' => 'integer',
        'comentario' => 'string',
        'usuario_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'capitulo_id' => 'required',
        'comentario' => 'required|string',
        'usuario_id' => 'required',
        'data_criacao' => 'required',
        'data_atualizacao' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function capitulo()
    {
        return $this->belongsTo(\App\Models\Capitulo::class, 'capitulo_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'usuario_id');
    }
}
