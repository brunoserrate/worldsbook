<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCapituloAPIRequest;
use App\Http\Requests\API\UpdateCapituloAPIRequest;
use App\Models\Capitulo;
use App\Repositories\CapituloRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class CapituloController
 * @package App\Http\Controllers\API
 */

class CapituloAPIController extends AppBaseController
{
    /** @var  CapituloRepository */
    private $capituloRepository;

    public function __construct(CapituloRepository $capituloRepo)
    {
        $this->capituloRepository = $capituloRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/capitulos",
     *      summary="Get a listing of the Capitulos.",
     *      tags={"Capitulo"},
     *      description="Get all Capitulos",
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
     *                  @SWG\Items(ref="#/definitions/Capitulo")
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
        $capitulos = $this->capituloRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($capitulos->toArray(), 'Capitulos retrieved successfully');
    }

    /**
     * @param CreateCapituloAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/capitulos",
     *      summary="Store a newly created Capitulo in storage",
     *      tags={"Capitulo"},
     *      description="Store Capitulo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Capitulo that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Capitulo")
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
     *                  ref="#/definitions/Capitulo"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateCapituloAPIRequest $request)
    {
        $input = $request->all();

        $capitulo = $this->capituloRepository->create($input);

        return $this->sendResponse($capitulo->toArray(), 'Capitulo saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/capitulos/{id}",
     *      summary="Display the specified Capitulo",
     *      tags={"Capitulo"},
     *      description="Get Capitulo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Capitulo",
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
     *                  ref="#/definitions/Capitulo"
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
        /** @var Capitulo $capitulo */
        $capitulo = $this->capituloRepository->find($id);

        // Caso tenha falha
        if(!$capitulo['success']){
            return $this->sendError($capitulo['message'], $capitulo['code'], $capitulo['data']);
        }

        return $this->sendResponse($capitulo['data'], $capitulo['message']);
    }

    /**
     * @param int $id
     * @param UpdateCapituloAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/capitulos/{id}",
     *      summary="Update the specified Capitulo in storage",
     *      tags={"Capitulo"},
     *      description="Update Capitulo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Capitulo",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Capitulo that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Capitulo")
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
     *                  ref="#/definitions/Capitulo"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateCapituloAPIRequest $request)
    {
        $input = $request->all();

        /** @var Capitulo $capitulo */
        $capitulo = $this->capituloRepository->find($id);

        if (empty($capitulo)) {
            return $this->sendError('Capitulo not found');
        }

        $capitulo = $this->capituloRepository->update($input, $id);

        return $this->sendResponse($capitulo->toArray(), 'Capitulo updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/capitulos/{id}",
     *      summary="Remove the specified Capitulo from storage",
     *      tags={"Capitulo"},
     *      description="Delete Capitulo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Capitulo",
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
        /** @var Capitulo $capitulo */
        $capitulo = $this->capituloRepository->find($id);

        if (empty($capitulo)) {
            return $this->sendError('Capitulo not found');
        }

        $capitulo->delete();

        return $this->sendSuccess('Capitulo deleted successfully');
    }

    public function capituloVisualizado($capituloId) {

        $result = $this->capituloRepository->adicionarVisualizacao($capituloId);

        if(!$result['success']){
            return $this->sendError($result['message'], $result['code'], $result['data']);
        }

        return $this->sendSuccess('Capítulo visualizado');
    }
}
