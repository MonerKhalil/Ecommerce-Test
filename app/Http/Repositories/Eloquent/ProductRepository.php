<?php

namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\Interfaces\IProductRepository;
use App\Models\Product;

class ProductRepository extends BaseRepository implements IProductRepository
{
    /**
     * @inheritDoc
     */
    function model()
    {
        return Product::class;
    }

    /**
     * @inheritDoc
     */
    function queryModel()
    {
        return Product::query();
    }
}
