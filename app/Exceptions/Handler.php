<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use App\Constants\Constants;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
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

        $status = Constants::NOT_FOUND;

        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            $description = trans('models.exception.not.found.description');
            $message = trans('models.exception.not.found.message');
            return $this->render_response($description, $message, $status);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $description = trans('models.exception.method.notallowed.http.exception.description');
            $message = trans('models.exception.method.notallowed.http.exception.message');
            $status = Constants::METHOD_NOT_ALLOWED;
            return $this->render_response($description, $message, $status);
        }

        if ($exception instanceof FatalThrowableError) {
            $description = trans('models.exception.fatal.throwable.error.description');
            $message = trans('models.exception.fatal.throwable.error.message');
            return $this->render_response($description, $message, $status);
        }


        return parent::render($request, $exception);
    }

    /**
     * Helper para renderizar response error
     */
    private function render_response($description, $message = null, $status = null)
    {

        $response = [
            "error" => $message,
            "message" => $description
        ];
        return response()->json($response, $status);
    }
}
