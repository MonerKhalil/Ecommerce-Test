<?php

namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\Interfaces\IOrderProductRepository;
use App\Models\OrderProduct;

class OrderProductRepository extends BaseRepository implements IOrderProductRepository
{
    /**
     * @inheritDoc
     */
    function model()
    {
        return OrderProduct::class;
    }

    /**
     * @inheritDoc
     */
    function queryModel()
    {
        return OrderProduct::query();
    }
}
