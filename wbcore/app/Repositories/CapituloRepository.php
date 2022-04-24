<?php

namespace App\Repositories;

use App\Models\Capitulo;
use App\Repositories\BaseRepository;
use DB;

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

    public function adicionarVisualizacao($capituloId) {

        try {
            Capitulo::where('id', $capituloId)
                ->update([
                    'quantidade_visualizacao' => DB::raw('quantidade_visualizacao + 1')
                ]);
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Falha ao atualizar a contagem. Por favor contatar o suporte.',
                'code' => 500,
                'data' => []
            ];
        }

        return [
            'success' => true,
            'message' => 'Contagem atualizada com sucesso.',
            'code' => 200,
            'data' => []
        ];

    }
}
