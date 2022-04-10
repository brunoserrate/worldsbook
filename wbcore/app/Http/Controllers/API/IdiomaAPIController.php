<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateIdiomaAPIRequest;
use App\Http\Requests\API\UpdateIdiomaAPIRequest;
use App\Models\Idioma;
use App\Repositories\IdiomaRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class IdiomaController
 * @package App\Http\Controllers\API
 */

class IdiomaAPIController extends AppBaseController
{
    /** @var  IdiomaRepository */
    private $idiomaRepository;

    public function __construct(IdiomaRepository $idiomaRepo)
    {
        $this->idiomaRepository = $idiomaRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/idiomas",
     *      summary="Get a listing of the Idiomas.",
     *      tags={"Idioma"},
     *      description="Get all Idiomas",
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
     *                  @SWG\Items(ref="#/definitions/Idioma")
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
        $idiomas = $this->idiomaRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($idiomas->toArray(), 'Idiomas retrieved successfully');
    }

    /**
     * @param CreateIdiomaAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/idiomas",
     *      summary="Store a newly created Idioma in storage",
     *      tags={"Idioma"},
     *      description="Store Idioma",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Idioma that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Idioma")
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
     *                  ref="#/definitions/Idioma"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateIdiomaAPIRequest $request)
    {
        $input = $request->all();

        $idioma = $this->idiomaRepository->create($input);

        return $this->sendResponse($idioma->toArray(), 'Idioma saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/idiomas/{id}",
     *      summary="Display the specified Idioma",
     *      tags={"Idioma"},
     *      description="Get Idioma",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Idioma",
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
     *                  ref="#/definitions/Idioma"
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
        /** @var Idioma $idioma */
        $idioma = $this->idiomaRepository->find($id);

        if (empty($idioma)) {
            return $this->sendError('Idioma not found');
        }

        return $this->sendResponse($idioma->toArray(), 'Idioma retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateIdiomaAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/idiomas/{id}",
     *      summary="Update the specified Idioma in storage",
     *      tags={"Idioma"},
     *      description="Update Idioma",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Idioma",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Idioma that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Idioma")
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
     *                  ref="#/definitions/Idioma"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateIdiomaAPIRequest $request)
    {
        $input = $request->all();

        /** @var Idioma $idioma */
        $idioma = $this->idiomaRepository->find($id);

        if (empty($idioma)) {
            return $this->sendError('Idioma not found');
        }

        $idioma = $this->idiomaRepository->update($input, $id);

        return $this->sendResponse($idioma->toArray(), 'Idioma updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/idiomas/{id}",
     *      summary="Remove the specified Idioma from storage",
     *      tags={"Idioma"},
     *      description="Delete Idioma",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Idioma",
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
        /** @var Idioma $idioma */
        $idioma = $this->idiomaRepository->find($id);

        if (empty($idioma)) {
            return $this->sendError('Idioma not found');
        }

        $idioma->delete();

        return $this->sendSuccess('Idioma deleted successfully');
    }
}
