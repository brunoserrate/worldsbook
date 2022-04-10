<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePublicoAlvoAPIRequest;
use App\Http\Requests\API\UpdatePublicoAlvoAPIRequest;
use App\Models\PublicoAlvo;
use App\Repositories\PublicoAlvoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class PublicoAlvoController
 * @package App\Http\Controllers\API
 */

class PublicoAlvoAPIController extends AppBaseController
{
    /** @var  PublicoAlvoRepository */
    private $publicoAlvoRepository;

    public function __construct(PublicoAlvoRepository $publicoAlvoRepo)
    {
        $this->publicoAlvoRepository = $publicoAlvoRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/publicoAlvos",
     *      summary="Get a listing of the PublicoAlvos.",
     *      tags={"PublicoAlvo"},
     *      description="Get all PublicoAlvos",
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
     *                  @SWG\Items(ref="#/definitions/PublicoAlvo")
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
        $publicoAlvos = $this->publicoAlvoRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($publicoAlvos->toArray(), 'Publico Alvos retrieved successfully');
    }

    /**
     * @param CreatePublicoAlvoAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/publicoAlvos",
     *      summary="Store a newly created PublicoAlvo in storage",
     *      tags={"PublicoAlvo"},
     *      description="Store PublicoAlvo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="PublicoAlvo that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/PublicoAlvo")
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
     *                  ref="#/definitions/PublicoAlvo"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatePublicoAlvoAPIRequest $request)
    {
        $input = $request->all();

        $publicoAlvo = $this->publicoAlvoRepository->create($input);

        return $this->sendResponse($publicoAlvo->toArray(), 'Publico Alvo saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/publicoAlvos/{id}",
     *      summary="Display the specified PublicoAlvo",
     *      tags={"PublicoAlvo"},
     *      description="Get PublicoAlvo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PublicoAlvo",
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
     *                  ref="#/definitions/PublicoAlvo"
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
        /** @var PublicoAlvo $publicoAlvo */
        $publicoAlvo = $this->publicoAlvoRepository->find($id);

        if (empty($publicoAlvo)) {
            return $this->sendError('Publico Alvo not found');
        }

        return $this->sendResponse($publicoAlvo->toArray(), 'Publico Alvo retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatePublicoAlvoAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/publicoAlvos/{id}",
     *      summary="Update the specified PublicoAlvo in storage",
     *      tags={"PublicoAlvo"},
     *      description="Update PublicoAlvo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PublicoAlvo",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="PublicoAlvo that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/PublicoAlvo")
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
     *                  ref="#/definitions/PublicoAlvo"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatePublicoAlvoAPIRequest $request)
    {
        $input = $request->all();

        /** @var PublicoAlvo $publicoAlvo */
        $publicoAlvo = $this->publicoAlvoRepository->find($id);

        if (empty($publicoAlvo)) {
            return $this->sendError('Publico Alvo not found');
        }

        $publicoAlvo = $this->publicoAlvoRepository->update($input, $id);

        return $this->sendResponse($publicoAlvo->toArray(), 'PublicoAlvo updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/publicoAlvos/{id}",
     *      summary="Remove the specified PublicoAlvo from storage",
     *      tags={"PublicoAlvo"},
     *      description="Delete PublicoAlvo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PublicoAlvo",
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
        /** @var PublicoAlvo $publicoAlvo */
        $publicoAlvo = $this->publicoAlvoRepository->find($id);

        if (empty($publicoAlvo)) {
            return $this->sendError('Publico Alvo not found');
        }

        $publicoAlvo->delete();

        return $this->sendSuccess('Publico Alvo deleted successfully');
    }
}
