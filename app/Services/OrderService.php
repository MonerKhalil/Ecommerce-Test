<?php

namespace App\Services;

use App\Exceptions\MainException;
use App\Http\Repositories\Interfaces\IOrderProductRepository;
use App\Http\Repositories\Interfaces\IOrderRepository;
use App\Http\Repositories\Interfaces\IProductRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private IOrderRepository $IOrderRepository,
                                private IOrderProductRepository $IOrderProductRepository,
                                private IProductRepository $IProductRepository)
    {
    }

    public function mainProcessOrderCreate($dataOrder){
        try {
            DB::beginTransaction();
            $orderUser = $this->createOrder();
            $priceTotal = 0;
            foreach ($dataOrder as $order){
                $productID = $order['product_id'];
                $quantity = $order['quantity'];
                $product = $this->checkCanAddProductQuantity($productID,$quantity);
                $price = $this->createRowInPivot($orderUser,$product,$quantity);
                $priceTotal += $price;
            }
            $this->editOrder($orderUser,$priceTotal);
            DB::commit();
            return $orderUser;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    private function checkCanAddProductQuantity($productId,$quantity){
        $product = $this->IProductRepository->find($productId);
        if ($quantity > $product->quantity){
            $nameProduct = $product->name;
            throw new MainException("This order cannot be created if you exceed the specified quantity for the product, which is $nameProduct");
        }
        $quantity = $product->quantity - $quantity;
        $product->update([
            "quantity" => ($quantity >=0 ? $quantity : 0),
        ]);
        return $product;
    }

    private function createRowInPivot($order,$product,$quantity){
        return $this->IOrderProductRepository->create([
            "order_id" => $order->id,
            "product_id" => $product->id,
            "quantity" => $quantity,
            "price" => ($product->price * $quantity),
        ])->price;
    }

    private function createOrder(){
        $user = user();
        $order_code = "Order-Code-" . uniqid() . '-' .str_replace(" ","-",strtolower($user->name));
        return $this->IOrderRepository->create([
            "user_id" => $user->id,
            "order_code" => uniqueSlug($order_code,$this->IOrderRepository->queryModel(),"order_code"),
        ]);
    }

    private function editOrder($order,$priceFinal){
        $order->update([
            "price_total" => $priceFinal,
        ]);
    }

}
