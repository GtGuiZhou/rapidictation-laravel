<?php

namespace App\Http\Traits;
use Illuminate\Http\Request;

trait CrudTrait
{


    /**
     * @var array
     */
    protected $searchFields = [];
    /**
     * @var Request
     */
    protected $createRequest;

    /**
     * @var Request
     */
    protected $editRequest;

    public function index(Request $request,$page,$size)
    {
        $where = json_decode($request->get('where',false));
        $sort = json_decode($request->get('sort',false));
        $search = $request->get('search',false);
        $list = $this->model
            // 搜索
            ->when(count($this->searchFields),function ($query) use ($search){
                foreach ($this->searchFields as $field){
                    $query->where($field,'like','%'.$search.'%');
                }
            })
            // 过滤
            ->when($where,function ($query, $where){
                foreach ($where as $field => $value){
                    $query->where($field,'=',$value);
                }
            })
            // 排序
            ->when($sort,function ($query,$sort){
                foreach ($sort as $field => $order){
                    $query->orderBy($field,$order);
                }
            })
            // 分页
            ->paginate($size);

        return $list->toArray();
    }

    public function create()
    {
        if ($this->createRequest){
            $data = $this->createRequest->validated();
        } else {
            $data = \request()->input();
        }
        $this->model->fill($data)->save();
    }

    public function edit($id)
    {
        $this->model = $this->model->findOrFail($id);
        if ($this->editRequest){
            $data = $this->editRequest->validated();
        } else {
            $data = \request()->input();
        }
        $this->model->fill($data)->save();
    }

    public function delete($id)
    {
        //
        $this->model->destroy(explode(',',$id));
    }
}