<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {



        if ($this->isHttpException($exception)) {
            if ($exception->getStatusCode() == 404) {
                return response()->view('error500', ['errorcode' => 404], 404);
            }
            if ($exception->getStatusCode() == 403) {
                return response()->view('error500', ['errorcode' => 403, 'errorMessage' => $exception->getMessage()], 403);
            }
            if ($exception->getStatusCode() == 500 || $exception->getStatusCode() == 403 || $exception->getStatusCode() == 419 || $exception->getStatusCode() == 409) {
                return response()->view('error500', ['errorcode' => 500], 500);
            }
        }

        // if($exception){
        //     return response()->view('error500', ['errorcode' => 500], 500);
        // }

        if ($request->ajax()) {

            //custom ajax errors
            switch ($exception->getStatusCode()) {

            //permission denied
            case 403:
                return response()->view('error500', ['errorcode' => 403, 'errorMessage' => 'You dont have Permission' ], 403);
                break;

            //larevel session timeout
            case 419:
                return response()->view('error500', ['errorcode' => 419], 419);
                break;

            //not found
            case 404:
                return response()->view('error500', ['errorcode' => 404], 404);
                break;

            //business logic/generic errors
            case 409:
                return response()->view('error500', ['errorcode' => 409], 409);
                break;

            default:
                return response()->view('error500', ['errorcode' => 500], 500);
                break;
            }

            return response()->json($exception->getStatusCode());
        }
        return parent::render($request, $exception);
    }


    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unauthenticated(
        $request,
        AuthenticationException $exception
    ) {
        if (in_array('web_admin', $exception->guards())) {
            return $request->expectsJson()
                ? response()->json([
                    'message' => $exception->getMessage()
                ], 401)
                : redirect()->guest(route('admin'));
        }
    }
}
