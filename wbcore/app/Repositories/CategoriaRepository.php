<?php

namespace App\Repositories;

use App\Models\Categoria;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;

/**
 * Class CategoriaRepository
 * @package App\Repositories
 * @version April 10, 2022, 12:07 am UTC
*/

class CategoriaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'genero'
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
        return Categoria::class;
    }

    public function all($search = [], $skip = null, $limit = null, $columns = ['*'])
    {
        $tempo = 28800; // 8 horas

        $result = Cache::remember('categorias', $tempo, function () {
            $categorias = Categoria::get();

            return $categorias;
        });

        return $result;
    }

}
