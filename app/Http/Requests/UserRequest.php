<?php

namespace App\Http\Requests;

class UserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $RulesAll = [
            'role' => 'required|exists:roles,name',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ];
        if ($this->isUpdatedRequest()){
            $user = $this->route('user');
            $user = $user->id ?? $user ?? "";
            $RulesAll['email'] = 'required|email|unique:users,email,' . $user;
        }else{
            $RulesAll['password'] = ['required', self::validationPassword()];
        }
        return $RulesAll;
    }
}
