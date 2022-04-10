<?php

namespace App\Repositories;

use App\Models\Tags;
use App\Repositories\BaseRepository;

/**
 * Class TagsRepository
 * @package App\Repositories
 * @version April 10, 2022, 12:17 am UTC
*/

class TagsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome'
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
        return Tags::class;
    }
}
