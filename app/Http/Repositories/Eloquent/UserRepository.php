<?php

namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\Interfaces\IUserRepository;
use App\Models\User;

class UserRepository extends BaseRepository implements IUserRepository
{
    /**
     * @inheritDoc
     */
    function model()
    {
        return User::class;
    }

    /**
     * @inheritDoc
     */
    function queryModel()
    {
        return User::query();
    }
}
