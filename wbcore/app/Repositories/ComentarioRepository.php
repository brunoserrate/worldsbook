<?php

namespace App\Repositories;

use App\Models\Comentario;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class ComentarioRepository
 * @package App\Repositories
 * @version April 10, 2022, 12:10 am UTC
*/

class ComentarioRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'capitulo_id',
        'comentario',
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
        return Comentario::class;
    }

    /**
     * Função para gravar o comentário do usuário
     *
     * A função servirá para gravar o comentário feito em um capítulo
     * Obs.: A função substitui a função da classe em que se extende (BaseRepository)
     *
     * @param array {
     *              capitulo_id: int,
     *              comentario: string,
     *        } $input
     *
     *        @type string $comentario Comentário feito pelo usuário
     *        @type integer $capitulo_id ID do capítulo a qual o comentário se refere
     *
     * @return array $result Retorna um array com a model mais as informações do usuário
     *                          dessa forma é possível adicionar o comentário no frontend
     *                          sem necessidade de mais uma requisição.
     **/
    public function create($input) {

        // Busca o usuário que fez a requisição
        $input['usuario_id'] = Auth::user()->id;

        // Cria uma nova instância da model com as informações de input
        // e logo em seguida salva a mesma, gravando em banco de dados
        $model = $this->model->newInstance($input);
        $model->save();

        // Busca o comentário através do id da model (registro que foi gravado)
        // É necessário realizar essa ação para buscar as informações do usuário
        $result = Comentario::where('comentarios.id', $model->id)
                    ->select(
                        'comentarios.*',
                        'users.name as nome_usuario',
                        'users.apelido as apelido_usuario',
                        'users.usar_apelido',
                        'users.foto_perfil'
                    )
                    ->join('users', 'users.id', '=', 'comentarios.usuario_id')
                    ->first();

        return $result->toArray();
    }

    /**
     * Função para carregar mais comentários
     *
     * A função servirá para gravar o comentário feito em um capítulo
     * Obs.: A função substitui a função da classe em que se extende (BaseRepository)
     *
     * @param array {
     *              capitulo_id: int,
     *              qtd_comentarios: int,
     *        } $input
     *
     *        @type integer $capitulo_id ID do capítulo a qual o comentário se refere
     *        @type integer $qtd_comentarios Quantidade total de comentários existentes no momento.
     *        @type integer $max_take (opcional) Quantidade máxima que será retornado. De preferência sempre utilizar a mesma quantidade (exemplo, de 5 em 5 registros).
     *
     * @return array $result Retorna um array com os comentários mais as informações do usuário.
     *
     **/
    public function carregarComentarios($input) {

        // Coloca os valores recebidos em variáveis separadas para facilitar o uso
        $skip = $input['qtd_comentarios'];
        $capituloId = $input['capitulo_id'];
        $maxTake = 5;

        // Verifica se o parâmetro opcional foi passado
        if(!empty($input['max_take'])){
            $maxTake = (int) $input['max_take'];
        }

        // Busca os comentários conforme a parâmetrização passada
        $comentarios = Comentario::where('comentarios.capitulo_id', $capituloId)
                    ->select(
                        'comentarios.*',
                        'users.name as nome_usuario',
                        'users.apelido as apelido_usuario',
                        'users.usar_apelido',
                        'users.foto_perfil'
                    )
                    ->join('users', 'users.id', '=', 'comentarios.usuario_id')
                    ->orderBy('comentarios.id', 'ASC')
                    ->skip($skip)->take($maxTake)
                    ->get()
                    ->toArray();

        return $comentarios;

    }


}
