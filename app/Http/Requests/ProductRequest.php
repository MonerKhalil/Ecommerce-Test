<?php

namespace App\Http\Requests;

class ProductRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name" => $this->textRule(true),
            "description" => $this->editorRule(false),
            "price" => ["required","numeric","min:0"],
            "quantity" => ["required","numeric","min:0"],
        ];
    }
}
