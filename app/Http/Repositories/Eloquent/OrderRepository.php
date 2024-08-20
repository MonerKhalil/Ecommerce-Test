<?php

namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\Interfaces\IOrderRepository;
use App\Models\Order;

class OrderRepository extends BaseRepository implements IOrderRepository
{
    /**
     * @inheritDoc
     */
    function model()
    {
        return Order::class;
    }

    /**
     * @inheritDoc
     */
    function queryModel()
    {
        return Order::query();
    }
}
