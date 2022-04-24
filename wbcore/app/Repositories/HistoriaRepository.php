<?php

namespace App\Repositories;

use App\Models\Historia;
use App\Models\Tags;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;

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

    /**
     * Função para gravar a história do usuário
     *
     * A função servirá para gravar as informações iniciais da história,
     * gravando as tags na tabela principal de tags,
     * além de gravar novas tags para futuros usuários utilizarem.
     * Obs.: A função substitui a função da classe em que se extende (BaseRepository)
     *
     * @param array {
     *              titulo: string,
     *              descricao: string,
     *              categoria_id: int,
     *              publico_alvo_id: int,
     *              idioma_id: int,
     *              direitos_autorais_id: int,
     *              conteudo_adulto: boolean,
     *              caminho_capa: string,
     *              usuario_id: bigint,
     *              tags: array,
     *        } $input
     *
     *        @type string $titulo Título da história
     *        @type string $descricao Descrição da história
     *        @type integer $categoria_id ID da categoria da história
     *        @type integer $publico_alvo_id ID do público alvo da história
     *        @type integer $idioma_id ID do idioma da história
     *        @type integer $direitos_autorais_id ID do tipo de direito autoral da história
     *        @type boolean $conteudo_adulto Se a história tem conteúdo adulto ou não
     *        @type string $caminho_capa Caminho ou URL da capa que será utilizada
     *        @type integer $usuario_id ID do usuário que cadastrou a história
     *        @type array $tags Lista de tags cadastradas
     *
     * @return array $result Retorna um array com o resultado da função,
     *                       seja o retorno positivo ou negativo
     **/
    public function create($input) {
        // Utiliza a própria classe de validação do framework
        // para validar os dados recebidos
        $validador = Validator::make($input, [
            'titulo' => 'required|max:200',
            'descricao' => 'required|string',
            'categoria_id' => 'required|numeric',
            'publico_alvo_id' => 'required|numeric',
            'idioma_id' => 'required|numeric',
            'direitos_autorais_id' => 'required|numeric',
            'conteudo_adulto' => 'required|string',
            'caminho_capa' => 'required|string',
            'usuario_id' => 'sometimes|required|numeric',
            'tags' => 'required',
        ]);

        // Em caso de falha, retorna uma estrutura padrão
        // para que o Controller consiga retornar de forma correta
        if ($validador->fails()) {
            return [
                'result' => false,
                'message' => 'Falha na operação! Formulário com informações inregulares.',
                'code' => 400,
                'data' => $input
            ];
        }

        // Adiciona o index de usuario_id caso o mesmo não exista
        // o campo é opcional já que o mesmo pode ser passado através de api
        // mas não será passado através do frontend web
        if(empty($input['usuario_id'])){
            $input['usuario_id'] = Auth::user()->id;
        }

        // Atribui o array das tags a uma nova variavel
        // remove o index 'tags' da variavel input
        // dessa forma o framework consegue inserir as novas informacoes sem erro
        $tags = $input['tags'];
        unset($input['tags']);

        // Cria uma instãncia da model Historia
        // e grava as informações do input
        // Caso haja falha, retorna ao usuário
        try {
            $model = $this->model->newInstance($input);
            $model->save();
        } catch (Exception $e) {
            return [
                'result' => false,
                'message' => 'Falha na operação! Falha ao gravar a história, verifique as informações enviadas.',
                'code' => 400,
                'data' => $input
            ];
        }

        // Variavel para inserir as informações na tabela auxiliar
        $insertHistoriaTag = [];

        // Verifica se existe as tags enviadas pelo usuário
        // Caso não exista a tag, faz a gravação da mesma
        // Caso já exista, utiliza o ID para gravar na tabela auxiliar
        foreach ($tags as $tag) {
            // Tag buscada
            $tagSearch = null;
            $tagSearch = Tags::where('nome', substr($tag, 100))->first();

            // inicialização da tag ID
            $tagId = 0;

            // Caso não exista a tag, insere a mesma
            if(empty($tagSearch)) {
                // Reseta a tag ID inserida
                $tagId = 0;
                DB::table('tags')->insertGetId([
                    'nome' => $tag,
                    'data_criacao' => date('Y-m-d H:i:s'),
                    'data_atualizacao' => date('Y-m-d H:i:s')
                ]);
            }
            else {
                // Recebe a ID da tag localizada
                $tagId = $tagSearch->id;
            }

            // Popula as informações de tags para tabela auxiliar (historia_tag)
            $insertHistoriaTag[] = [
                'tag_id' => $tagId,
                'historia_id' => $model->id,
                'data_criacao' => date('Y-m-d H:i:s'),
                'data_atualizacao' => date('Y-m-d H:i:s')
            ];
        // fim das tags
        }

        // Grava as tagas na tabela auxiliar
        DB::table('historia_tag')->insert($insertHistoriaTag);

        return [
            'result' => true,
            'message' => 'História grava com sucesso',
            'code' => 200,
            'data' => []
        ];
    }

    /**
     * Encontrar o registro da história selecionada
     *
     * @param int $id ID da história
     *
     * @return array $result Retorna um array com o resultado da função,
     *                       seja o retorno positivo ou negativo
     */
    public function find($id) {

        /**
         * Informações a serem retornadas:
         * Historia:
         *  titulo
         *  descrição
         *  caminho_capa
         *  nome_usuario
         *  direitos_autorais
         * Capitulos
         *  qtd_capitulos
         *  qtd_visualizações
         *  qtd_votos
         *  titulo capitulo
         *  capitulos
         * Tags
         *  tags da historia
         * */

        /**
         *  1º encontrar a história
         *  2º
         * */
    }

}
