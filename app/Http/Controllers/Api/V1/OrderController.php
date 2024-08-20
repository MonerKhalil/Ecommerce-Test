<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Interfaces\IOrderRepository;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private IOrderRepository $IOrderRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function index()
    {
        $orders = $this->IOrderRepository->get();

        return $this->responseSuccess(compact("orders"));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function show(Order $order)
    {
        $order = $this->IOrderRepository->find($order->id,function ($q){
            return $q->with('products');
        });

        return $this->responseSuccess(compact("order"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function destroy(Order $order)
    {
        $this->IOrderRepository->delete($order->id);

        return $this->responseSuccess();
    }

    /**
     * delete multi ids Records Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function multiDestroy(Request $request){
        $result = $this->IOrderRepository->multiDestroy($request);

        return $this->responseSuccess();
    }
}
