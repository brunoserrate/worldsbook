<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDireitosAutoraisAPIRequest;
use App\Http\Requests\API\UpdateDireitosAutoraisAPIRequest;
use App\Models\DireitosAutorais;
use App\Repositories\DireitosAutoraisRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class DireitosAutoraisController
 * @package App\Http\Controllers\API
 */

class DireitosAutoraisAPIController extends AppBaseController
{
    /** @var  DireitosAutoraisRepository */
    private $direitosAutoraisRepository;

    public function __construct(DireitosAutoraisRepository $direitosAutoraisRepo)
    {
        $this->direitosAutoraisRepository = $direitosAutoraisRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/direitosAutorais",
     *      summary="Get a listing of the DireitosAutorais.",
     *      tags={"DireitosAutorais"},
     *      description="Get all DireitosAutorais",
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
     *                  @SWG\Items(ref="#/definitions/DireitosAutorais")
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
        $direitosAutorais = $this->direitosAutoraisRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($direitosAutorais->toArray(), 'Direitos Autorais retrieved successfully');
    }

    /**
     * @param CreateDireitosAutoraisAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/direitosAutorais",
     *      summary="Store a newly created DireitosAutorais in storage",
     *      tags={"DireitosAutorais"},
     *      description="Store DireitosAutorais",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="DireitosAutorais that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/DireitosAutorais")
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
     *                  ref="#/definitions/DireitosAutorais"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateDireitosAutoraisAPIRequest $request)
    {
        $input = $request->all();

        $direitosAutorais = $this->direitosAutoraisRepository->create($input);

        return $this->sendResponse($direitosAutorais->toArray(), 'Direitos Autorais saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/direitosAutorais/{id}",
     *      summary="Display the specified DireitosAutorais",
     *      tags={"DireitosAutorais"},
     *      description="Get DireitosAutorais",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of DireitosAutorais",
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
     *                  ref="#/definitions/DireitosAutorais"
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
        /** @var DireitosAutorais $direitosAutorais */
        $direitosAutorais = $this->direitosAutoraisRepository->find($id);

        if (empty($direitosAutorais)) {
            return $this->sendError('Direitos Autorais not found');
        }

        return $this->sendResponse($direitosAutorais->toArray(), 'Direitos Autorais retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateDireitosAutoraisAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/direitosAutorais/{id}",
     *      summary="Update the specified DireitosAutorais in storage",
     *      tags={"DireitosAutorais"},
     *      description="Update DireitosAutorais",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of DireitosAutorais",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="DireitosAutorais that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/DireitosAutorais")
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
     *                  ref="#/definitions/DireitosAutorais"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateDireitosAutoraisAPIRequest $request)
    {
        $input = $request->all();

        /** @var DireitosAutorais $direitosAutorais */
        $direitosAutorais = $this->direitosAutoraisRepository->find($id);

        if (empty($direitosAutorais)) {
            return $this->sendError('Direitos Autorais not found');
        }

        $direitosAutorais = $this->direitosAutoraisRepository->update($input, $id);

        return $this->sendResponse($direitosAutorais->toArray(), 'DireitosAutorais updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/direitosAutorais/{id}",
     *      summary="Remove the specified DireitosAutorais from storage",
     *      tags={"DireitosAutorais"},
     *      description="Delete DireitosAutorais",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of DireitosAutorais",
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
        /** @var DireitosAutorais $direitosAutorais */
        $direitosAutorais = $this->direitosAutoraisRepository->find($id);

        if (empty($direitosAutorais)) {
            return $this->sendError('Direitos Autorais not found');
        }

        $direitosAutorais->delete();

        return $this->sendSuccess('Direitos Autorais deleted successfully');
    }
}
