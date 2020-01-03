<?php


namespace App\Http\Controllers\User;


use App\ActiveCodeModel;
use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Admin\ActiveCode;
use Illuminate\Http\Request;

class User
{

    /**
     * @var ActiveCode
     */
    protected $active_code;

    public function __construct()
    {
        $this->active_code = new ActiveCodeModel();
    }

    public function login($machine_code)
    {
        $activeCode = $this->active_code->where('machine_code',$machine_code)->first();
        if(!$activeCode){
            throw new InvalidRequestException('尚未绑定激活码');
        }
        if ($activeCode->status != 'active'){
            throw new InvalidRequestException('绑定的激活码未被激活');
        }
    }

    public function active(Request $request)
    {
        $active_code = $request->input('active_code');
        $machine_code = $request->input('machine_code');
        $activeCode = $this->active_code->where('code',$active_code)->first();
        if (!$activeCode){
            throw new InvalidRequestException('激活码不存在');
        }
        if (!empty($activeCode->machine_code)){
            throw new InvalidRequestException('激活码已被使用');
        }
        if ($activeCode->status != 'release'){
            throw new InvalidRequestException('激活码还未被发放');
        }
        $activeCode->machine_code = $machine_code;
        $activeCode->status = 'active';
        $activeCode->active_time = date('Y-m-d H:i:s',time());
        $activeCode->save();

    }
}