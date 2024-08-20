<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\MainException;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Interfaces\IRoleRepository;
use App\Http\Repositories\Interfaces\IUserRepository;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct(private IUserRepository $IUserRepository,private IRoleRepository $IRoleRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function index()
    {
        $users = $this->IUserRepository->get();

        return $this->responseSuccess(compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $roles = $this->IRoleRepository->all();

        return $this->responseSuccess(compact("roles"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $data['name'] = $request->first_name . " " . $request->last_name;
            $result = $this->IUserRepository->create($data);
            $result->attachRole($request->role);
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function show(User $user)
    {
        $user = $this->IUserRepository->find($user->id);

        return $this->responseSuccess(compact("user"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = $this->IRoleRepository->all();

        $user = $this->IUserRepository->find($user->id);

        return $this->responseSuccess(compact("user","roles"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $data['name'] = $request->first_name . " " . $request->last_name;
            $result = $this->IUserRepository->update($data ,$user->id);
            $result->syncRoles([$request->role]);
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function destroy(User $user)
    {
        $this->IUserRepository->delete($user->id);

        return $this->responseSuccess();
    }

    /**
     * delete multi ids Records Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function multiDestroy(Request $request){
        $result = $this->IUserRepository->multiDestroy($request);

        return $this->responseSuccess();
    }

    public function resetPassword(Request $request){
        $request->validate([
            "ids" => ["required","array"],
            "ids.*" => ["required",Rule::exists("users","id")],
        ]);
        $this->IUserRepository->queryModel()
            ->whereIn("id",$request->ids)
            ->update([
                "password" => Hash::make(User::PASSWORD),
            ]);
        return $this->responseSuccess();
    }

    ######################### PROFILE USER #########################

    public function showProfileUser(){
        $user = \user();
        return $this->responseSuccess(compact("user"));
    }

    public function editProfileUser(UserProfileRequest $request){
        $user = \user();

        $data = $request->validated();

        $data['name'] = $request->first_name . " " . $request->last_name;

        $result = $this->IUserRepository->update($data ,$user->id);

        return $this->responseSuccess(compact("result"));
    }

}
