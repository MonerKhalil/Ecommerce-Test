<?php

namespace App\HelperClasses;

use App\HelperClasses\ClassesStatic\JsonHandle;
use App\HelperClasses\ClassesStatic\MessagesFlash;
use App\HelperClasses\ClassesStatic\ResponseCodeTypes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class ResponseProcess
{
    private function urlIsApi(bool $withAjax = true): mixed
    {
        $withAjax = $withAjax && request()->ajax();
        return request()->is('api/*') || request()->is('api') || $withAjax;
    }

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
    ): Response|JsonResponse|RedirectResponse|null
    {
        if ($this->urlIsApi() || (is_null($viewName) && is_null($routeName) && is_null($urlTo) && !$isBack)){
            return JsonHandle::dataHandle($dataResponse);
        }
        if (!is_null($viewName)){
            return response()->view($viewName,$dataResponse??[]);
        }
        if ($isBack){
            return redirect()->back();
        }
        if (!is_null($routeName)){
            return redirect()->route($routeName,$parametersRouteName);
        }
        if (!is_null($urlTo)){
            return redirect()->to($urlTo);
        }
        return null;
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
     * @return JsonResponse|RedirectResponse|Response|null
     */
    public function responseError($error, $exception, int $code = ResponseCodeTypes::CODE_ERROR_BAD_REQUEST, bool $isBack = false
        , string $ViewName = null, array $dataView = [], string $RouteName = null, array $dataRoute = []): Response|JsonResponse|RedirectResponse|null
    {
        MessagesFlash::setMsgError($error);
        if ($this->urlIsApi() || (is_null($ViewName) && is_null($RouteName) && !$isBack)){
            return JsonHandle::errorHandle($exception,$code);
        }
        if ($isBack){
            return redirect()->back();
        }
        if (!is_null($ViewName)){
            return response()->view($ViewName,$dataView);
        }
        if (!is_null($RouteName)){
            return redirect()->route($RouteName,$dataRoute);
        }
        return null;
    }
}
