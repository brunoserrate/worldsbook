<?php

namespace App\Models;

use Eloquent as Model;



/**
 * @SWG\Definition(
 *      definition="Idioma",
 *      required={"idioma", "data_criacao", "data_atualizacao"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="idioma",
 *          description="idioma",
 *          type="string"
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
class Idioma extends Model
{


    public $table = 'idioma';
    
    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = 'data_atualizacao';




    public $fillable = [
        'idioma'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'idioma' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idioma' => 'required|string|max:100',
        'data_criacao' => 'required',
        'data_atualizacao' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function historia()
    {
        return $this->hasMany(\App\Models\Historium::class, 'idioma_id');
    }
}
