<?php

namespace App\Repositories;

use App\Models\Capitulo;
use App\Models\Comentario;
use App\Models\Historia;
use App\Repositories\BaseRepository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use File;

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
        'votacao',
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

        $proximo_capitulo = Capitulo::where('historia_id', $capitulo['historia_id'])
                                        ->where('id', '>', $capitulo['id'])
                                            ->select('id')
                                            ->orderBy('id', 'ASC')
                                            ->first();

        if(!empty($proximo_capitulo)) {
            $proximo_capitulo = $proximo_capitulo->id;
        }

        $capitulo['proximo_capitulo'] = $proximo_capitulo;

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
                        )
                        ->orderBy('comentarios.id', 'DESC')
                        ->get()->toArray();


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

    /**
     * Grava o capítulo
     *
     * @param array $input
     *
     * @return array $result Retorna um array com o resultado da função,
     *                       seja o retorno positivo ou negativo
     */
    public function create($input) {

        $usuario_id = Auth::user()->id;

        if(!empty($input['usuario_id']) ){
            $usuario_id = $input['usuario_id'];
        }

        $historia = Historia::where('id', $input['historia_id'])
                        ->select('usuario_id')
                        ->first();

        if(empty($historia)) {
            return [
                'success' => false,
                'message' => 'História não encontrada! Favor verificar os parâmetros enviados.',
                'code' => 404,
                'data' => []
            ];
        }

        $usuario_historia = $historia->usuario_id;

        if($usuario_historia != $usuario_id) {
            return [
                'success' => false,
                'message' => 'Divergência de usuários! Sem permissão para criar capítulos para essa história.',
                'code' => 400,
                'data' => []
            ];
        }

        $model = $this->model->newInstance($input);
        $model->usuario_id = $usuario_id;
        $model->save();

        return [
            'success' => true,
            'message' => 'Capítulo criado com sucesso',
            'code' => 200,
            'data' => $model->toArray()
        ];
    }


    public function update($input, $id) {

        $usuario_id = Auth::user()->id;

        $query = $this->model->newQuery();
        $model = $query->findOrFail($id);

        if($model->usuario_id != $usuario_id) {
            return [
                'success' => false,
                'message' => 'Sem permissão para alterar o capítulo de outro usuário.',
                'code' => 400,
                'data' => []
            ];
        }

        $model->fill($input);
        $model->data_atualizacao = date('Y-m-d H:i:s');

        $model->save();

        return [
            'success' => true,
            'message' => 'Capítulo atualizado com sucesso',
            'code' => 200,
            'data' => $model->toArray()
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

    /**
     * Função para fazer upload de imagem para a capa do capítulo
     *
     * @param @type class Request $request binário da imagem através da classe Request
     *
     * @return array $result Retorna um array com o resultado da função,
     *                       seja o retorno positivo ou negativo
     **/
    public function uploadCapaCapitulo($request) {

        // Classe auxiliar para fazer upload da imagem localmente
        $result = UtilsController::uploads($request);

        // Em caso de falha, para a rotina imediatamente
        if(!$result['success']) {
            return $result;
        }

        // Caminhos da imagem
        $data = $result['data'];
        $localFile = NULL;

        // Caminho da imagem
        $envPath = env('IMG_PATH');

        // Busca o arquivo no caminho temporário
        $localFile = File::get($data['path_file']);

        // Sobe o arquivo no FTP
        try {
            Storage::disk('ftp')->put('worldbooks/spa/upload/capitulo/' . $data['file_name'], $localFile);
        } catch (Exception $e) {
            \Log::channel('daily')->error( 'Falha ao fazer o upload da imagem: ' . $e->getMessage() );

            return [
                'success' => false,
                'code' => 500,
                'message' => 'Falha ao fazer o upload da imagem',
                'data' => []
            ];
        }

        return [
            'success' => true,
            'code' => 200,
            'message' => 'Upload realizado com sucesso',
            'data' => [
                'path' => $envPath . 'capitulo/',
                'file_name' => $data['file_name'],
                'full_path' => $envPath . 'capitulo/' . $data['file_name']
            ]
        ];

    }

}
