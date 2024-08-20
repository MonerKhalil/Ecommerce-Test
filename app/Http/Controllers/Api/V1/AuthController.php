<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\MainException;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Interfaces\IUserRepository;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(private IUserRepository $IUserRepository)
    {
    }
    
    public function register(RegisterRequest $request){
        try {
            $data = [
                'name' => $request->first_name . " " . $request->last_name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'user',
            ];
            DB::beginTransaction();
            $user = $this->IUserRepository->create($data);
            $user->attachRole($user->role);
            $token = auth()->login($user);
            DB::commit();
            return $this->responseSuccess($this->responseToken($user,$token));
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    public function login(LoginRequest $request){
        $user = $this->IUserRepository->find($request->email,null,"email");
        if ($token = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])){
            return $this->responseSuccess($this->responseToken($user,$token));
        }
        throw ValidationException::withMessages([
            'email/password' => __('auth.failed'),
        ]);
    }

    public function logout(){
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
        return $this->responseSuccess(null);
    }

    private function responseToken($user,$token){
        return [
            'user' => $user,
            'access_token' => [
                'token' => $token,
                'type' => 'Bearer',
            ],
        ];
    }
}
