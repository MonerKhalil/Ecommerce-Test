<?php

namespace App\HelperClasses;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Route;

class MyApp
{
    public const DEFAULT_PAGES_Count = 10;

    /**
     * @var MyApp|null
     * @author moner khalil
     */
    private static MyApp|null $app = null;

    public ?ResponseProcess $responseProcess = null;

    private function __construct()
    {
        $this->responseProcess = new ResponseProcess();
    }

    /**
     * @return MyApp
     * @author moner khalil
     */
    public static function Classes(): MyApp
    {
        if (is_null(self::$app)){
            self::$app = new static();
        }
        return self::$app;
    }

    /**
     * @return Authenticatable|null
     */
    public function getUser(): ?Authenticatable
    {
        return \auth()->user();
    }

    public function mainRoutes($name,$controller,$isApi = true){
        if ($isApi){
            Route::apiResource($name,$controller);
        }else{
            Route::resource($name,$controller);
        }
        Route::prefix($name)
            ->controller($controller)
            ->group(function (){
                Route::delete("delete/multi","multiDestroy");
            });
    }

}
