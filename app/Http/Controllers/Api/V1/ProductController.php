<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\MainException;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Interfaces\IProductRepository;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct(private IProductRepository $IProductRepository)
    {
        $this->middleware(['role_user:'.Role::SUPER_ADMIN])->except(["index"]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function index()
    {
        $products = $this->IProductRepository->get();

        return $this->responseSuccess(compact("products"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $result = $this->IProductRepository->create($data);
            DB::commit();
            return $this->responseSuccess(compact("result"));
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function show(Product $product)
    {
        $product = $this->IProductRepository->find($product->id);

        return $this->responseSuccess(compact("product"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $result = $this->IProductRepository->update($data ,$product->id);
            DB::commit();
            return $this->responseSuccess(compact("result"));
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function destroy(Product $product)
    {
        $this->IProductRepository->delete($product->id);

        return $this->responseSuccess();
    }

    /**
     * delete multi ids Records Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function multiDestroy(Request $request){
        $result = $this->IProductRepository->multiDestroy($request);

        return $this->responseSuccess();
    }
}
