<?php

namespace App\Models;

use Eloquent as Model;



/**
 * @SWG\Definition(
 *      definition="Capitulo",
 *      required={"titulo", "capitulo", "historia_id", "caminho_capa", "quantidade_visualizacao", "votacao", "data_criacao", "data_atualizacao"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="titulo",
 *          description="titulo",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="capitulo",
 *          description="capitulo",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="historia_id",
 *          description="historia_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="caminho_capa",
 *          description="caminho_capa",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="quantidade_visualizacao",
 *          description="quantidade_visualizacao",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="votacao",
 *          description="votacao",
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
class Capitulo extends Model
{


    public $table = 'capitulos';
    
    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = 'data_atualizacao';




    public $fillable = [
        'titulo',
        'capitulo',
        'historia_id',
        'caminho_capa',
        'quantidade_visualizacao',
        'votacao'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'titulo' => 'string',
        'capitulo' => 'string',
        'historia_id' => 'integer',
        'caminho_capa' => 'string',
        'quantidade_visualizacao' => 'integer',
        'votacao' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'titulo' => 'required|string|max:200',
        'capitulo' => 'required|string',
        'historia_id' => 'required',
        'caminho_capa' => 'required|string',
        'quantidade_visualizacao' => 'required',
        'votacao' => 'required',
        'data_criacao' => 'required',
        'data_atualizacao' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function historia()
    {
        return $this->belongsTo(\App\Models\Historium::class, 'historia_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function comentarios()
    {
        return $this->hasMany(\App\Models\Comentario::class, 'capitulo_id');
    }
}
