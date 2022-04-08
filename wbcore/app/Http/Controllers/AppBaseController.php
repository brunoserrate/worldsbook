<?php

namespace App\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;
use Response;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{

    public function sendResponse($result, $message)
    {
    	$response = ResponseUtil::makeResponse($message, $result);
    	$response['response_time'] =  date('Y-m-d H:i-s');
        return Response::json($response);
    }

    public function sendError($error, $code = 404, $data = [])
    {

    	$response = ResponseUtil::makeError($error,$data);
    	$response['response_time'] =  date('Y-m-d H:i-s');
    	return Response::json($response, $code);

    }
}
