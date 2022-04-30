<?php

namespace App\Repositories;

use App\Models\Capitulo;
use App\Models\Comentario;
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

    /**
     * Encontrar o registro da capítulo selecionada
     *
     * @param int $id ID da capítulo
     * @param array $columns
     *
     * @return array $result Retorna um array com o resultado da função,
     *                       seja o retorno positivo ou negativo
     */
    public function find($id, $columns = ['*']) {

        // Busca a informações do registro da tabela capítulo
        $capitulo = Capitulo::where('capitulos.id', $id)
                        ->join('historia', 'historia.id', '=', 'capitulos.historia_id')
                        ->join('users', 'users.id', '=', 'historia.usuario_id')
                        ->select(
                            'capitulos.*',
                            'users.name as nome_usuario',
                            'users.apelido as apelido_usuario',
                            'users.usar_apelido',
                            'users.foto_perfil'
                        )
                        ->first();

        // Caso não exista nenhum valor, retorna com erro
        if(empty($capitulo)){
            return [
                'success' => false,
                'message' => 'Capítulo não localizado',
                'code' => 404,
                'data' => []
            ];
        }

        // Transforma o retorno em array para facilitar as próximas etapas
        $capitulo = $capitulo->toArray();

        // Altera o tipo de dado de int para boolean (por conta do mysql)
        $capitulo['usar_apelido'] = (bool) $capitulo['usar_apelido'];

        // Cria o index de comentários
        $capitulo['comentarios'] = [];

        // Busca as informações de comentários
        $comentarios = Comentario::where('comentarios.capitulo_id', $id)
                        ->join('users', 'users.id', '=', 'comentarios.usuario_id')
                        ->select(
                            'comentarios.*',
                            'users.name as nome_usuario',
                            'users.apelido as apelido_usuario',
                            'users.usar_apelido',
                            'users.foto_perfil'
                        )->get()->toArray();


        if(!empty($comentarios)){
            // quantidade máxima de comentários
            $maxIndex = count($comentarios);

            for ($i = 0; $i < $maxIndex; $i++) {
                // Altera o tipo de dado de int para boolean (por conta do mysql)
                $comentarios[$i]['usar_apelido'] = (bool) $comentarios[$i]['usar_apelido'];
            }

            $capitulo['comentarios'] = $comentarios;
        }

        return [
            'success' => true,
            'message' => 'Capítulo retornado com sucesso',
            'code' => 200,
            'data' => $capitulo
        ];

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
