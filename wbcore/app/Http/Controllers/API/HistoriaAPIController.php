<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateHistoriaAPIRequest;
use App\Http\Requests\API\UpdateHistoriaAPIRequest;
use App\Models\Historia;
use App\Repositories\HistoriaRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class HistoriaController
 * @package App\Http\Controllers\API
 */

class HistoriaAPIController extends AppBaseController
{
    /** @var  HistoriaRepository */
    private $historiaRepository;

    public function __construct(HistoriaRepository $historiaRepo)
    {
        $this->historiaRepository = $historiaRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/historias",
     *      summary="Get a listing of the Historias.",
     *      tags={"Historia"},
     *      description="Get all Historias",
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
     *                  @SWG\Items(ref="#/definitions/Historia")
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
        $historias = $this->historiaRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($historias->toArray(), 'Historias retrieved successfully');
    }

    /**
     * @param CreateHistoriaAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/historias",
     *      summary="Store a newly created Historia in storage",
     *      tags={"Historia"},
     *      description="Store Historia",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Historia that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Historia")
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
     *                  ref="#/definitions/Historia"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateHistoriaAPIRequest $request)
    {
        $input = $request->all();

        $historia = $this->historiaRepository->create($input);

        // Caso tenha falha
        if(!$historia['success']){
            return $this->sendError($historia['message'], $historia['code'], $historia['data']);
        }

        return $this->sendResponse($historia['data'], 'História grava com sucesso');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/historias/{id}",
     *      summary="Display the specified Historia",
     *      tags={"Historia"},
     *      description="Get Historia",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Historia",
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
     *                  ref="#/definitions/Historia"
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
        /** @var Historia $historia */
        $historia = $this->historiaRepository->buscarHistoria($id);

        // Caso tenha falha
        if(!$historia['success']){
            return $this->sendError($historia['message'], $historia['code'], $historia['data']);
        }

        return $this->sendResponse($historia['data'], $historia['message']);
    }

    /**
     * @param int $id
     * @param UpdateHistoriaAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/historias/{id}",
     *      summary="Update the specified Historia in storage",
     *      tags={"Historia"},
     *      description="Update Historia",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Historia",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Historia that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Historia")
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
     *                  ref="#/definitions/Historia"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateHistoriaAPIRequest $request)
    {
        $input = $request->all();

        /** @var Historia $historia */
        $historia = $this->historiaRepository->find($id);

        if (empty($historia)) {
            return $this->sendError('História não localizada');
        }

        $result = $this->historiaRepository->update($input, $id);

        if(!$result['success']){
            return $this->sendError($result['message'], $result['code'], $result['data']);
        }

        return $this->sendResponse($result['data'], $result['message']);
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/historias/{id}",
     *      summary="Remove the specified Historia from storage",
     *      tags={"Historia"},
     *      description="Delete Historia",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Historia",
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
        /** @var Historia $historia */
        $historia = $this->historiaRepository->find($id);

        if (empty($historia)) {
            return $this->sendError('Historia not found');
        }

        $result = $this->historiaRepository->deletarHistoria($id);

        if(!$result['success']){
            return $this->sendError($result['message'], $result['code'], $result['data']);
        }

        return $this->sendSuccess('História deletada com sucesso');
    }


    public function uploadCapaHistoria(Request $request) {

        $result = $this->historiaRepository->uploadCapaHistoria($request);

        // Caso tenha falha
        if(!$result['success']){
            return $this->sendError($result['message'], $result['code'], $result['data']);
        }

        return $this->sendResponse($result['data'], $result['message']);
    }

    public function pesquisarHistoria(Request $request) {

        $input = $request->all();

        $result = $this->historiaRepository->pesquisarHistoria($input);

        return $this->sendResponse($result['data'], $result['message']);
    }

    public function pesquisarHistoriaCategoria(Request $request) {

        $input = $request->all();

        $result = $this->historiaRepository->pesquisarHistoriaCategoria($input);

        return $this->sendResponse($result['data'], $result['message']);
    }
}
