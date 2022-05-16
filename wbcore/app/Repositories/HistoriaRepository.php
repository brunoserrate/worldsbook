<?php

namespace App\Repositories;

use App\Http\Controllers\UtilsController;
use App\Repositories\BaseRepository;

use App\Models\Historia;
use App\Models\Capitulo;
use App\Models\Tags;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use File;

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

        // Busca o usuário que fez a requisição
        $input['usuario_id'] = Auth::user()->id;

        // Utiliza a própria classe de validação do framework
        // para validar os dados recebidos
        $validador = Validator::make($input, [
            'titulo' => 'required|max:200',
            'descricao' => 'required|string',
            'categoria_id' => 'required|numeric',
            'publico_alvo_id' => 'required|numeric',
            'idioma_id' => 'required|numeric',
            'direitos_autorais_id' => 'required|numeric',
            'conteudo_adulto' => 'required|boolean',
            'caminho_capa' => 'required|string',
            'tags' => 'required',
        ]);

        // Em caso de falha, retorna uma estrutura padrão
        // para que o Controller consiga retornar de forma correta
        if ($validador->fails()) {
            return [
                'success' => false,
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
                'success' => false,
                'message' => 'Falha na operação! Falha ao gravar a história, verifique as informações enviadas.',
                'code' => 400,
                'data' => $input
            ];
        }

        // Insere as tags na história
        $this->inserirTags($tags, $model->id);

        return [
            'success' => true,
            'message' => 'História grava com sucesso',
            'code' => 200,
            'data' => [
                'historia_id' => $model->id
            ]
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
    public function buscarHistoria($id) {

        // Busca as informações do registro da tabela história
        $historia = Historia::where('historia.id', $id)
                        ->join('users', 'users.id', '=', 'historia.usuario_id')
                        ->join('direitos_autorais', 'direitos_autorais.id', '=', 'historia.direitos_autorais_id')
                        ->leftJoin('categoria', 'categoria.id', '=', 'historia.categoria_id')
                        ->select(
                            'historia.*',
                            'categoria.genero AS categoria_nome',
                            'direitos_autorais.tipo_autoral as direito_autoral',
                            'users.name as nome_usuario',
                            'users.apelido as apelido_usuario',
                            'users.usar_apelido',
                            'users.foto_perfil'
                        )
                        ->first();

        // Caso não exista nenhum valor, retorna com erro
        if(empty($historia)){
            return [
                'success' => false,
                'message' => 'História não localizada',
                'code' => 404,
                'data' => []
            ];
        }

        // Transforma o retorno em array para facilitar as próximas etapas
        $historia = $historia->toArray();

        // Inicializa os indexes de capitulos
        $historia['total_capitulos'] = 0;
        $historia['total_visualizacoes'] = 0;
        $historia['total_votos'] = 0;
        $historia['capitulos'] = [];

        // Busca os capitulos existentes na história
        $capitulos = Capitulo::where('historia_id', $id)
                        ->select(
                            'id',
                            'titulo',
                            'quantidade_visualizacao',
                            'votacao',
                            'caminho_capa'
                        )->get()->toArray();

        // Caso exista registros, faz a contabilização das votações, visualizações e quantidade de capitulos
        // Caso contrário utiliza as informações dos indexes iniciais
        if(!empty($capitulos)){
            foreach ($capitulos as $cap) {
                $historia['total_capitulos']++;
                $historia['total_visualizacoes'] += $cap['quantidade_visualizacao'];
                $historia['total_votos'] += $cap['votacao'];
                $historia['capitulos'][] = $cap;
            }
        }

        // Mesmo processo feito para os capítulos, mas com as informações das tags.
        $historia['tags'] = [];

        $tags = Tags::where('historia_tag.historia_id', $id)
                    ->select('tags.id','tags.nome')
                    ->join('historia_tag', 'historia_tag.tag_id', '=', 'tags.id')
                    ->get()->toArray();

        if(!empty($tags)){
            foreach ($tags as $tag) {
                $historia['tags'][] = $tag['nome'];
            }
        }

        // Limpa as duplicatas
        $historia['tags'] = array_unique( $historia['tags'] );

        return [
            'success' => true,
            'message' => 'História retornada com sucesso',
            'code' => 200,
            'data' => $historia
        ];

    }

    /**
     * Encontrar o registro da história selecionada
     *
     * @param array $input
     * @param int $id ID da história
     *
     * @return array $result Retorna um array com o resultado da função,
     *                       seja o retorno positivo ou negativo
     */
    public function update($input, $id) {

        $usuario_id = Auth::user()->id;

        $query = $this->model->newQuery();
        $model = $query->findOrFail($id);

        if($model->usuario_id != $usuario_id) {
            return [
                'success' => false,
                'message' => 'Sem permissão para alterar a história de outro usuário.',
                'code' => 400,
                'data' => []
            ];
        }

        $model->fill($input);
        $model->data_atualizacao = date('Y-m-d H:i:s');

        $model->save();

        // Atribui o array das tags a uma nova variavel
        // remove o index 'tags' da variavel input
        // dessa forma o framework consegue inserir as novas informacoes sem erro
        $tags = $input['tags'];
        unset($input['tags']);

        // Deleta todas as tags atreladas aquela história p/ recolocar as novas tags
        DB::table('historia_tag')
            ->where('historia_id', $model->id)
            ->delete();

        // Insere as tags na história
        $this->inserirTags($tags, $model->id);

        return [
            'success' => true,
            'message' => 'História atualizada com sucesso',
            'code' => 200,
            'data' => $model->toArray()
        ];
    }

    /**
     * @param int $id
     *
     * @throws \Exception
     *
     * @return bool|mixed|null
     */
    public function deletarHistoria($id) {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        return $model->delete();
    }

    /**
     * Função para fazer upload de imagem para a capa da história
     *
     * @param @type class Request $request binário da imagem através da classe Request
     *
     * @return array $result Retorna um array com o resultado da função,
     *                       seja o retorno positivo ou negativo
     **/
    public function uploadCapaHistoria($request) {

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
            Storage::disk('ftp')->put('worldbooks/spa/upload/historia/' . $data['file_name'], $localFile);
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
                'path' => $envPath . 'historia/',
                'file_name' => $data['file_name'],
                'full_path' => $envPath . 'historia/' . $data['file_name']
            ]
        ];
    }

    /**
     * Função para pesquisar as histórias por título da história e por tag atrelada
     *
     * @param array {
     *              pesquisa: string,
     *        } $input
     *
     * @return array $result Retorna um array com o resultado da função,
     *                       seja o retorno positivo ou negativo
     *
     **/
    public function pesquisarHistoria($input){

        // Instanciando variável de pesquisa
        $pesquisa = '';

        // Caso o input de pesquisa não esteja vazio, atrela a variável de pesquisa
        if( !empty($input['pesquisa'] ) ) {
            $pesquisa = trim( $input['pesquisa']  );
        }

        $historias = [];

        // Query para fazer a busca de histórias
        $histIds = DB::table('historia')
                    ->select(
                        'historia.id',
                    )
                    // Subquery para buscar os ids das histórias que contém a palavra pesquisada
                    // Tanto no título, quanto nos nomes das tags
                    ->whereIn('historia.id', function($query) use ($pesquisa) {
                        $query->from('historia')
                            ->select('historia.id')
                            ->leftJoin('historia_tag', 'historia_tag.historia_id', '=', 'historia.id')
                            ->leftJoin('tags', 'tags.id', '=', 'historia_tag.tag_id')
                            // Encapsulamento do where
                            ->where( function($query2) use ($pesquisa) {
                                $query2->orWhere('historia.titulo', 'LIKE', '%'. $pesquisa . '%')
                                ->orWhere('tags.nome', 'LIKE', '%'. $pesquisa . '%');
                            });
                    })
                    ->get()
                    ->toArray();

        // Buscar as informações de cada história
        foreach ($histIds as $hist) {

            $result = $this->buscarHistoria($hist->id);

            if($result['success']) {
                $historias[] = $result['data'];
            }
        }

        return [
            'success' => true,
            'message' => 'Hístórias retornadas com sucesso',
            'code' => 200,
            'data' => $historias
        ];
    }

    /**
     * Função para pesquisar as histórias através da ID da categoria
     *
     * @param array {
     *              categoria_id: int,
     *        } $input
     *
     * @return array $result Retorna um array com o resultado da função,
     *                       seja o retorno positivo ou negativo
     *
     **/
    public function pesquisarHistoriaCategoria($input){

        // Instanciando variável de pesquisa
        $categoria_id = 0;

        // Caso o input de pesquisa não esteja vazio, atrela a variável de pesquisa
        if( !empty($input['categoria_id'] ) ) {
            $categoria_id = trim( $input['categoria_id']  );
        }

        $historias = [];

        $histIds = Historia::where('categoria_id', $categoria_id)->select('id')
                    ->get()->toArray();

        // Buscar as informações de cada história
        foreach ($histIds as $hist) {
            $result = $this->buscarHistoria($hist['id']);

            if($result['success']) {
                $historias[] = $result['data'];
            }
        }

        return [
            'success' => true,
            'message' => 'Hístórias retornadas com sucesso',
            'code' => 200,
            'data' => $historias
        ];
    }

    /**
     * Procedimento para inserir as tags na história
     *
     * @param array $tags Vetor com as tags a serem adicionadas
     * @param int $historiaId ID da história
     *
     **/
    private function inserirTags($tags, $historiaId){

        // Variavel para inserir as informações na tabela auxiliar
        $insertHistoriaTag = [];

        // Verifica se existe as tags enviadas pelo usuário
        // Caso não exista a tag, faz a gravação da mesma
        // Caso já exista, utiliza o ID para gravar na tabela auxiliar
        foreach ($tags as $tag) {
            // Tag buscada
            $tagSearch = null;
            $tagSearch = Tags::where('nome', '=',substr(trim($tag), 0, 100))->first();

            // inicialização da tag ID
            $tagId = 0;

            // Caso não exista a tag, insere a mesma
            if(empty($tagSearch)) {
                // Reseta a tag ID inserida
                $tagId = 0;
                $tagId = DB::table('tags')->insertGetId([
                    'nome' => trim($tag),
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
                'historia_id' => $historiaId,
                'data_criacao' => date('Y-m-d H:i:s'),
                'data_atualizacao' => date('Y-m-d H:i:s')
            ];
        // fim das tags
        }

        // Grava as tags na tabela auxiliar
        DB::table('historia_tag')->insert($insertHistoriaTag);
    }


}