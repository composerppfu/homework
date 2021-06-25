<?php
/**
 * Created by PhpStorm.
 * User: dongxinyun
 * Date: 2019/5/15
 * Time: 上午10:58
 */

namespace App\Services;


use App\Constants\ErrorCode;
use App\Exceptions\BusinessException;
use App\Models\BaseModel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class BaseService
{

    /**
     * 数据模型对象
     *
     * @var BaseModel $model
     */
    public static $model;

    /**
     * 构建list 查询条件
     *
     * @param null|\Illuminate\Database\Eloquent\Builder $query
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function listWhere($params, $query)
    {
        $tableNmae = app(static::$model)->getTable();
        //可以根据具体需要重新 new query对象
        //比如调整排序字段等
        $query = $query ?? (static::$model)::query();

        //根据parames内容添加查询条件
        if (isset($params['id']) && !empty($params['id'])) {
            $query->where("{$tableNmae}.id", '=', $params['id']);
        }

        //返回查询对象
        return $query;
    }

    /**
     * @param       $params
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAllList($params, $columns = [])
    {
        $tableNmae = app(static::$model)->getTable();
        $query = (static::$model)::orderBy("{$tableNmae}.id", "desc");

        //查询指定列
        if (!empty($columns)) {
            $query->select($columns);
        }

        //构建查询条件
        $query = static::listWhere($params, $query);

        return $query->get();
    }

    /**
     * @param       $params
     *
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getList($params, $columns = [])
    {
        $tableNmae = app(static::$model)->getTable();
        $query = (static::$model)::orderBy("{$tableNmae}.id", "desc");

        //查询指定列
        if (!empty($columns)) {
            $query->select($columns);
        }

        //构建查询条件
        $query = static::listWhere($params, $query);

        $list = $query->paginate(Request::input('page_size', 20));

        return $list;
    }

    /**
     * @param $params
     *
     * @return mixed
     */
    public static function create($params)
    {
        $params = (static::$model)::buildBaseProperties($params);
        return (static::$model)::query()->create($params);
    }

    public static function createMultiple($params)
    {


    }

    /**
     * @param $id
     * @param $params
     *
     * @return mixed
     * @throws BusinessException
     */
    public static function update($id, $params)
    {
        $params = (static::$model)::buildBaseProperties($params, false);
        $obj = static::find($id);
        if (empty($obj)) {
            throw new BusinessException(ErrorCode::NOT_EXIST_ERROR);
        }

        //过滤掉多余的属性
        $attributes = array_intersect_key($params, array_flip($obj->getFillable()));

        $obj->setRawAttributes($attributes);
        return $obj->save();
    }


    /**
     * @param $id
     *
     * @return BaseModel
     */
    public static function find($id)
    {
        return (static::$model)::find($id);
    }

    /**
     *
     * @param integer $id
     *
     * @return bool
     * @throws \Exception
     */
    public static function delete($id)
    {
        $model = static::find($id);
        if ($model) {
            $model->delete();
        }
        return true;
    }

    /**
     * @param integer $id
     *
     * @param integer $dataStatus
     *
     * @return bool
     * @throws BusinessException
     */
    public static function operation($id, $dataStatus)
    {
        $obj = static::find($id);
        if (empty($obj)) {
            throw new BusinessException(ErrorCode::NOT_EXIST_ERROR);
        }
        $obj->dataStatus = $dataStatus;
        $obj->save();

        return true;
    }
}
