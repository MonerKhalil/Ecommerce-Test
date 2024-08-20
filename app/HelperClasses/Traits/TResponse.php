<?php

namespace App\HelperClasses\Traits;

use App\HelperClasses\ClassesStatic\ResponseCodeTypes;
use App\HelperClasses\MyApp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

trait TResponse
{
    /**
     * @param mixed|null $dataResponse
     * @param string|null $viewName
     * @param string|null $routeName
     * @param array $parametersRouteName
     * @param bool $isBack
     * @param string|null $urlTo
     * @return JsonResponse|RedirectResponse|Response|null
     */
    public function responseSuccess(mixed $dataResponse = null, string $viewName = null,
                                    string $routeName = null, array $parametersRouteName = [],
                                    bool $isBack = false, string $urlTo = null
    ): Response|JsonResponse|RedirectResponse|null{
        return MyApp::Classes()->responseProcess->responseSuccess($dataResponse,$viewName,$routeName,$parametersRouteName,$isBack,$urlTo);
    }

    /**
     * @param $error
     * @param $exception
     * @param int $code
     * @param bool $isBack
     * @param string|null $ViewName
     * @param array $dataView
     * @param string|null $RouteName
     * @param array $dataRoute
     * @return mixed
     */
    public function responseError($error, $exception, $code = ResponseCodeTypes::CODE_ERROR_BAD_REQUEST, bool $isBack = false
        , string $ViewName = null, array $dataView = [], string $RouteName = null, array $dataRoute = []): mixed
    {
        return MyApp::Classes()->responseProcess->responseError($error,$exception,$code,$isBack,$ViewName,$dataView,$RouteName,$dataRoute);
    }
}
