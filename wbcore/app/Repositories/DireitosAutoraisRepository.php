<?php

namespace App\Repositories;

use App\Models\DireitosAutorais;
use App\Repositories\BaseRepository;

/**
 * Class DireitosAutoraisRepository
 * @package App\Repositories
 * @version April 10, 2022, 12:10 am UTC
*/

class DireitosAutoraisRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tipo_autoral'
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
        return DireitosAutorais::class;
    }
}
