<?php

namespace App\Http\Repositories\Eloquent;

use App\Exceptions\MainException;
use App\HelperClasses\ClassesStatic\MessagesFlash;
use App\HelperClasses\Traits\TLogMain;
use App\HelperClasses\Traits\TPaginationData;
use App\Http\Repositories\Interfaces\IBaseRepository;
use App\Models\BaseModel;
use Exception;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class BaseRepository implements IBaseRepository
{
    use TLogMain,TPaginationData;

    /**
     * @var App
     */
    private $app;

    /**
     * @var string
     */
    public string $nameTable = "";

    /**
     * @var
     */
    public $model;

    public abstract function model();

    public abstract function queryModel();

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
        $this->nameTable = $this->model->getTable();
    }

    public function getInstance()
    {
        return new $this->model;
    }

    public function makeModel(){
        try {
            $model = $this->app->make($this->model());
            return $this->model = $model;
        } catch (Exception $e) {
            throw new MainException($e->getMessage());
        }
    }

    /**
     * without search filter data
     * @param callable|null $callback
     * @author moner khalil
     */
    public function all(callable $callback = null ,$order = "desc",$columnOrder = null)
    {
        $queryBuilder = $this->queryModel();
        if (!is_null($callback)){
            $queryBuilder =  $callback($queryBuilder);
        }
        return $queryBuilder->orderBy($columnOrder ?? "updated_at",$order)->get();
    }

    /**
     * within search filter data
     * @param bool|null $isAll
     * @param callable|null $callback
     * @author moner khalil
     */
    public function get(bool $isAll = null, callable $callback = null,?string $nameDateFilter = null)
    {
        $queryBuilder = $this->queryModel();
        $tableName = $this->nameTable;
        $keysSearch = ($this->model instanceof BaseModel) ? $this->model->fieldsSearch : [];
        $keysNullable = ($this->model instanceof BaseModel) ? $this->model->fieldsIsNullable : [];
        $keysLike = ($this->model instanceof BaseModel) ? $this->model->fieldsLike : [];

        foreach (filterDataRequest() as $key => $value){
            if (in_array($key,$keysSearch)&&(in_array($key,$keysNullable) || !is_null($value))){
                if (in_array($key,$keysLike)){
                    $queryBuilder = $queryBuilder->where("{$tableName}.{$key}" , "LIKE", $value."%");
                }elseif (is_array($value)){
                    $queryBuilder = $queryBuilder->whereIn("{$tableName}.{$key}",[]);
                }else{
                    $queryBuilder = $queryBuilder->where("{$tableName}.{$key}",$value);
                }
            }
        }
        return $this->dataPaginate($queryBuilder);
    }

    /**
     * @param $data
     * @param bool $showMessage
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function create($data , bool $showMessage = true): mixed{
        try {
            DB::beginTransaction();
            $process = "create";
            $item = $this->queryModel()->create($data);
            $this->logProcess($process,$item);
            if ($showMessage){
                MessagesFlash::setMsgSuccess(null,$process);
            }
            DB::commit();
            return $item;
        }catch (Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    /**
     * @param $data
     * @param int $idOldModel
     * @param bool $showMessage
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function update($data,int $idOldModel, bool $showMessage = true): mixed{
        try {
            DB::beginTransaction();
            $process = "update";
            $oldModel = $this->find($idOldModel);
            $oldModel->update($data);
            $this->logProcess($process,$oldModel);
            if ($showMessage){
                MessagesFlash::setMsgSuccess(null,$process);
            }
            $newModel = $this->find($idOldModel);
            DB::commit();
            return $newModel;
        }catch (Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    /**
     * @param $value
     * @param callable|null $callback
     * @param string $key
     * @param bool $withFail
     * @param bool $withActive
     * @return mixed
     * @author moner khalil
     */
    public function find($value, callable $callback = null, string $key = "id",bool $withFail = true): mixed{
        $query = $this->queryModel();
        $query = $query->where($key,$value);
        if (!is_null($callback)){
            $query = $callback($query);
        }
        return $withFail ? $query->firstOrFail() : $query->first();
    }

    /**
     * @param int $idModel
     * @param bool $showMessage
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function delete(int $idModel, bool $showMessage = true): bool{
        try {
            DB::beginTransaction();
            $process = "delete";
            $oldModel = $this->find($idModel);
            $oldModel->delete();
            $this->logProcess($process,$oldModel);
            if ($showMessage){
                MessagesFlash::setMsgSuccess(null,$process);
            }
            DB::commit();
            return true;
        }catch (Exception $exception){
            DB::rollBack();
            if ($exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException){
                throw new ModelNotFoundException();
            }
            throw new MainException($exception->getMessage());
        }
    }

    /**
     * @param $request
     * @param bool $showMessage
     * @param null $callbackWhere
     * @return mixed
     * @throws MainException
     * @author moner khalil
     */
    public function multiDestroy($request, bool $showMessage = true,$callbackWhere = null): bool{
        $request->validate([
            "ids" => ["required","array"],
            "ids.*" => ["required",Rule::exists($this->nameTable,"id")],
        ]);
        try {
            DB::beginTransaction();
            $process = "delete";
            $oldModel = $this->queryModel()->whereIn("id",$request->ids);
            if (!is_null($callbackWhere)){
                $oldModel = $callbackWhere($oldModel);
            }
            $oldModel->delete();
            $this->logProcess($process,["table"=>$this->nameTable]);
            if ($showMessage){
                MessagesFlash::setMsgSuccess(null,$process);
            }
            DB::commit();
            return true;
        }catch (Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }
}
