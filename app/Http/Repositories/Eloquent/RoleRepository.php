<?php

namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\Interfaces\IRoleRepository;
use App\Models\Role;

class RoleRepository extends BaseRepository implements IRoleRepository
{
    /**
     * @inheritDoc
     */
    function model()
    {
        return Role::class;
    }

    /**
     * @inheritDoc
     */
    function queryModel()
    {
        return Role::query();
    }
}
