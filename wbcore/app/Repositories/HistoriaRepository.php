<?php

namespace App\Repositories;

use App\Models\Historia;
use App\Repositories\BaseRepository;

/**
 * Class HistoriaRepository
 * @package App\Repositories
 * @version April 9, 2022, 12:31 am UTC
*/

class HistoriaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
        return Historia::class;
    }
}
