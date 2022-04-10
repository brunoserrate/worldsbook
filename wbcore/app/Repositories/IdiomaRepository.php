<?php

namespace App\Repositories;

use App\Models\Idioma;
use App\Repositories\BaseRepository;

/**
 * Class IdiomaRepository
 * @package App\Repositories
 * @version April 10, 2022, 12:16 am UTC
*/

class IdiomaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idioma'
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
        return Idioma::class;
    }
}
