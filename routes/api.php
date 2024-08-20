<?php

use App\HelperClasses\MyApp;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\OrderUserController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

Route::middleware(["guest:api"])->group(function (){
    Route::post("register",[AuthController::class,"register"]);
    Route::post("login",[AuthController::class,"login"]);
});
Route::middleware(["auth:api"])->group(function (){
    Route::prefix("auth")->group(function (){
        Route::delete("logout", [AuthController::class, 'logout']);
        Route::prefix("profile")->controller(UserController::class)->group(function (){
            Route::get("show","showProfileUser");
            Route::put("edit","editProfileUser");
        });
    });

    Route::prefix("dashboard")->group(function (){
        Route::middleware(["role_user:".Role::SUPER_ADMIN])->prefix("admin")->group(function (){
            #USER
            MyApp::Classes()->mainRoutes("user",UserController::class,false);
            Route::post("users/reset/password", [UserController::class, "resetPassword"]);
            #end
            #Product
            MyApp::Classes()->mainRoutes("product", ProductController::class);
            #end
        });
        #Order
            Route::prefix("order")->controller(OrderController::class)->group(function (){
                Route::get("all","index");
                Route::get("{order}/show","show");
                Route::delete("{order}/destroy","destroy");
                Route::delete("delete/multi-orders","multiDestroy");
            });
            Route::post("order/create/multi-products",[OrderUserController::class,"createOrderProducts"])
                ->middleware(["role_user:".Role::USER]);
        #end
    });
});



