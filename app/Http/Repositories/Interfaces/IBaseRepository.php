<?php

namespace App\Http\Repositories\Interfaces;

interface IBaseRepository
{
    public function all(callable $callback = null,$order = "desc",$columnOrder = null);
    public function get(bool $isAll = null, callable $callback = null,?string $nameDateFilter = null);
    public function create($data , bool $showMessage = true): mixed;
    public function update($data,int $idOldModel, bool $showMessage = true): mixed;
    public function find($value, callable $callback = null, string $key = "id",bool $withFail = true): mixed;
    public function delete(int $idModel, bool $showMessage = true): bool;
    public function multiDestroy($request, bool $showMessage = true,$callbackWhere = null): bool;
}
