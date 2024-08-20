<?php

namespace App\Exceptions;

use App\HelperClasses\ClassesStatic\ResponseCodeTypes;
use App\HelperClasses\Traits\TResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use TResponse;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     */
    public function register()
    {
        $this->renderable(function (Throwable $e) {
            if ($e instanceof ValidationException) {
                return $this->responseError($e->errors(),"ValidationException");
            }
            if ($e instanceof AuthorizationException){
                return $this->responseError($e->getMessage(),"AuthorizationException",ResponseCodeTypes::CODE_ERROR_NOT_ACCESS);
            }
            if ($e instanceof AuthenticationException) {
                return $this->responseError($e->getMessage(),"AuthenticationException",ResponseCodeTypes::CODE_ERROR_NOT_LOGIN);
            }
            if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException){
                return $this->responseError("Not Found 404 -_-.","NotFoundHttpException",ResponseCodeTypes::CODE_ERROR_NOT_FOUND);
            }
            if ($e instanceof MethodNotAllowedHttpException){
                return $this->responseError($e->getMessage(),"MethodNotAllowedHttpException",ResponseCodeTypes::CODE_ERROR_Method_Not_Allowed);
            }
            if ($e instanceof AccessDeniedHttpException){
                return $this->responseError($e->getMessage(),"AccessDeniedHttpException",ResponseCodeTypes::CODE_ERROR_NOT_ACCESS);
            }
            return $this->responseError($e->getMessage(),"MainException",ResponseCodeTypes::CODE_ERROR_Internal_Server);
        });
    }
}
