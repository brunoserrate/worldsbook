<?php

namespace App\Repositories;

use App\Models\Capitulo;
use App\Repositories\BaseRepository;

/**
 * Class CapituloRepository
 * @package App\Repositories
 * @version April 10, 2022, 12:06 am UTC
*/

class CapituloRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'titulo',
        'capitulo',
        'historia_id',
        'caminho_capa',
        'quantidade_visualizacao',
        'votacao'
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
        return Capitulo::class;
    }
}
