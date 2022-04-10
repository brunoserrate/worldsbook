<?php

namespace App\Repositories;

use App\Models\Comentario;
use App\Repositories\BaseRepository;

/**
 * Class ComentarioRepository
 * @package App\Repositories
 * @version April 10, 2022, 12:10 am UTC
*/

class ComentarioRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'capitulo_id',
        'comentario',
        'usuario_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Comentario::class;
    }
}
