<?php

namespace App\Models;

use Eloquent as Model;



/**
 * @SWG\Definition(
 *      definition="Historia",
 *      required={"titulo", "descricao", "categoria_id", "publico_alvo_id", "idioma_id", "direitos_autorais_id", "conteudo_adulto", "caminho_capa", "usuario_id", "data_criacao", "data_atualizacao"},
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
 *          property="descricao",
 *          description="descricao",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="categoria_id",
 *          description="categoria_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="publico_alvo_id",
 *          description="publico_alvo_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="idioma_id",
 *          description="idioma_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="direitos_autorais_id",
 *          description="direitos_autorais_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="conteudo_adulto",
 *          description="conteudo_adulto",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="caminho_capa",
 *          description="caminho_capa",
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
class Historia extends Model
{


    public $table = 'historia';
    
    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = 'data_atualizacao';




    public $fillable = [
        'titulo',
        'descricao',
        'categoria_id',
        'publico_alvo_id',
        'idioma_id',
        'direitos_autorais_id',
        'conteudo_adulto',
        'caminho_capa',
        'usuario_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'titulo' => 'string',
        'descricao' => 'string',
        'categoria_id' => 'integer',
        'publico_alvo_id' => 'integer',
        'idioma_id' => 'integer',
        'direitos_autorais_id' => 'integer',
        'conteudo_adulto' => 'boolean',
        'caminho_capa' => 'string',
        'usuario_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'titulo' => 'required|string|max:200',
        'descricao' => 'required|string',
        'categoria_id' => 'required|integer',
        'publico_alvo_id' => 'required|integer',
        'idioma_id' => 'required|integer',
        'direitos_autorais_id' => 'required|integer',
        'conteudo_adulto' => 'required|boolean',
        'caminho_capa' => 'required|string',
        'usuario_id' => 'required',
        'data_criacao' => 'required',
        'data_atualizacao' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function categoria()
    {
        return $this->belongsTo(\App\Models\Categorium::class, 'categoria_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idioma()
    {
        return $this->belongsTo(\App\Models\Idioma::class, 'idioma_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function publicoAlvo()
    {
        return $this->belongsTo(\App\Models\PublicoAlvo::class, 'publico_alvo_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function direitosAutorais()
    {
        return $this->belongsTo(\App\Models\DireitosAutorai::class, 'direitos_autorais_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'usuario_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function capitulos()
    {
        return $this->hasMany(\App\Models\Capitulo::class, 'historia_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function historiaTags()
    {
        return $this->hasMany(\App\Models\HistoriaTag::class, 'historia_id');
    }
}
