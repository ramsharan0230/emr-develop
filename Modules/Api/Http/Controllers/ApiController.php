<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ApiController extends Controller
{
    
    /**
     * constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param $result
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $message = '')
    {

        $response = [
            "success" => true,
            "message" => $message,
            'data'    => $result,
        ];

        return response()->json($response, 200, ['Content-type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 500)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        // dd($response);
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Docs file.
     * @return \Illuminate\View\View
     */
    public function docs()
    {
        return view('api::document-files.doc');
    }

    public function displayJsonData($jsondata = null)
    {
        $data = file_get_contents(public_path('plugins/api-documents/api-response/'. $jsondata.'.json'));
        echo "<pre>";
        echo $data;
        echo "</pre>";
    }
}
