<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class OrderRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "orders" => ["required","array",],
            "orders.*" => ["required","array"],
            "orders.*.product_id" => ["required","numeric",Rule::exists("products","id")],
            "orders.*.quantity" => ["required","numeric","min:1"],
        ];
    }
}
