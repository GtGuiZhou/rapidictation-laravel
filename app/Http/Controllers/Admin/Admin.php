<?php

namespace App\Http\Controllers\Admin;

use App\AdminModel;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo = '/admin';

    /**
     * @var array
     */
    protected $searchFields = [];

    /**
     * @var AdminModel
     */
    protected $model;

    /**
     * @var Request
     */
    protected $createRequest;

    /**
     * @var Request
     */
    protected $editRequest;


    public function __construct()
    {
        $this->model = new AdminModel();
    }


    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function username()
    {
        return 'username';
    }

    /**
     * 认证成功返回的数据
     * @param Request $request
     * @param $user
     * @return string
     */
    protected function authenticated(Request $request, $user)
    {
        return 'success';
    }


    public function index(Request $request,$page,$size)
    {
        $where = json_decode($request->get('where',false));
        $order = json_decode($request->get('order',false));
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
            ->when($order,function ($query,$order){
                foreach ($order as $field => $sort){
                    $query->orderBy($field,$sort);
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

