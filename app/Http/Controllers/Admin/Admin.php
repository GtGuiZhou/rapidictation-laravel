<?php

namespace App\Http\Controllers\Admin;

use App\AdminModel;
use App\Http\Controllers\Controller;
use App\Http\Traits\CrudTrait;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin extends Controller
{

    use AuthenticatesUsers,CrudTrait;

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

    public function loggedOut()
    {
        return 'success';
    }

    /**
     * 认证成功返回的数据
     * @param Request $request
     * @param $user
     * @return string
     */
    protected function authenticated(Request $request, $user)
    {
        return $user;
    }



}

