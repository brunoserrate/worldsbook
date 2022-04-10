<?php

namespace App\Repositories;

use App\Models\PublicoAlvo;
use App\Repositories\BaseRepository;

/**
 * Class PublicoAlvoRepository
 * @package App\Repositories
 * @version April 10, 2022, 12:17 am UTC
*/

class PublicoAlvoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'publico',
        'idade_min',
        'idade_max'
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
        return PublicoAlvo::class;
    }
}
