<?php

namespace App\Http\Requests;

use App\HelperClasses\MyApp;
use Illuminate\Validation\Rule;

class UserProfileRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . user()->id,
        ];
    }
}
