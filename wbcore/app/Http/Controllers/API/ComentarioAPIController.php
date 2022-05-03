<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateComentarioAPIRequest;
use App\Http\Requests\API\UpdateComentarioAPIRequest;
use App\Models\Comentario;
use App\Repositories\ComentarioRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class ComentarioController
 * @package App\Http\Controllers\API
 */

class ComentarioAPIController extends AppBaseController
{
    /** @var  ComentarioRepository */
    private $comentarioRepository;

    public function __construct(ComentarioRepository $comentarioRepo)
    {
        $this->comentarioRepository = $comentarioRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/comentarios",
     *      summary="Get a listing of the Comentarios.",
     *      tags={"Comentario"},
     *      description="Get all Comentarios",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Comentario")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $comentarios = $this->comentarioRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($comentarios->toArray(), 'Comentarios retrieved successfully');
    }

    /**
     * @param CreateComentarioAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/comentarios",
     *      summary="Store a newly created Comentario in storage",
     *      tags={"Comentario"},
     *      description="Store Comentario",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Comentario that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Comentario")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Comentario"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateComentarioAPIRequest $request)
    {
        $input = $request->all();

        $comentario = $this->comentarioRepository->create($input);

        return $this->sendResponse($comentario, 'Comentario saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/comentarios/{id}",
     *      summary="Display the specified Comentario",
     *      tags={"Comentario"},
     *      description="Get Comentario",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Comentario",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Comentario"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Comentario $comentario */
        $comentario = $this->comentarioRepository->find($id);

        if (empty($comentario)) {
            return $this->sendError('Comentario not found');
        }

        return $this->sendResponse($comentario->toArray(), 'Comentario retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateComentarioAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/comentarios/{id}",
     *      summary="Update the specified Comentario in storage",
     *      tags={"Comentario"},
     *      description="Update Comentario",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Comentario",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Comentario that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Comentario")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Comentario"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateComentarioAPIRequest $request)
    {
        $input = $request->all();

        /** @var Comentario $comentario */
        $comentario = $this->comentarioRepository->find($id);

        if (empty($comentario)) {
            return $this->sendError('Comentario not found');
        }

        $comentario = $this->comentarioRepository->update($input, $id);

        return $this->sendResponse($comentario->toArray(), 'Comentario updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/comentarios/{id}",
     *      summary="Remove the specified Comentario from storage",
     *      tags={"Comentario"},
     *      description="Delete Comentario",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Comentario",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Comentario $comentario */
        $comentario = $this->comentarioRepository->find($id);

        if (empty($comentario)) {
            return $this->sendError('Comentario not found');
        }

        $comentario->delete();

        return $this->sendSuccess('Comentario deleted successfully');
    }

    public function carregarComentarios(Request $request) {

        $input = $request->all();

        $comentarios = $this->comentarioRepository->carregarComentarios($input);

        return $this->sendResponse($comentarios, 'Comentariso carregados com sucesso!');
    }
}
