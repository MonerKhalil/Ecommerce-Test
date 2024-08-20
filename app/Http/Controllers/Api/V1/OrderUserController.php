<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService;

class OrderUserController extends Controller
{
    public function createOrderProducts(OrderRequest $request,OrderService $service){
        $dataOrder = $request->orders;
        $order = $service->mainProcessOrderCreate($dataOrder);
        return $this->responseSuccess(compact("order"));
    }
}
