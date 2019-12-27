<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait CrudTrait
{

    /**
     * @var Request
     */
    protected $createRequest;

    /**
     * @var Request
     */
    protected $editRequest;

    /**
     * @var array
     */
    protected $createRelationRequest = [];

    public function index(Request $request)
    {
        $where = json_decode($request->get('where', false));
        $sort = json_decode($request->get('sort', false));
        $relations = json_decode($request->get('relations', false));
        $search = $request->get('search', false);
        $size = $request->get('size', 10);
        $searchFields = isset($this->searchFields) ? $this->searchFields : [];
        $list = $this->model
            // 搜索
            ->when(count($searchFields) && $search, function ($query) use ($searchFields, $search) {
                foreach ($searchFields as $field) {
                    $query->where($field, 'like', '%' . $search . '%');
                }
            })
            // 过滤
            ->when($where, function ($query, $where) {
                foreach ($where as $field => $value) {
                    $query->where($field, '=', $value);
                }
            })
            // 排序
            ->when($sort, function ($query, $sort) {
                foreach ($sort as $field => $order) {
                    $query->orderBy($field, $order);
                }
            })
            // 关联
            ->when(count($relations) > 0, function ($query) use ($relations) {
                 $query->with($relations);
            })
            // 分页
            ->paginate($size);

        return $list->toArray();
    }

    public function create()
    {
        $relation = \request()->get('relation',false);
        $id = \request()->get('id',false);
        if (!$relation || $id === false){
            // 当前模型创建
            if ($this->createRequest) {
                $data = $this->createRequest->validated();
            } else {
                $data = \request()->post();
            }
            $this->model->fill($data)->save();
        } else {
            // 关联创建
            if (isset($this->createRelationRequest,$relation)){
                $data = $this->createRelationRequest[$relation]->validated();
            } else {
                $data = \request()->post();
            }
            $this->model = $this->model->findOrFail($id);
            $this->model->$relation()->fill($data)->create();
        }
    }

    public function edit($id)
    {
        $this->model = $this->model->findOrFail($id);
        if ($this->editRequest) {
            $data = $this->editRequest->validated();
        } else {
            $data = \request()->input();
        }
        $this->model->fill($data)->save();
    }

    public function delete($id)
    {
        //
        $relation = \request()->get('relation',false);
        $relation_id = \request()->get('relation_id',false);
        if (!$relation) {
            $this->model->destroy(explode(',', $id));
        } else {
            $this->model = $this->model->findOrFail($id);
            $this->model->$relation()->detach(explode(',',$relation_id));
        }
    }
}