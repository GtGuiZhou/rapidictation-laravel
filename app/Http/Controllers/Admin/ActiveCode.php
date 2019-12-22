<?php

namespace App\Http\Controllers\Admin;

use App\ActiveCodeModel;
use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller;
use App\Http\Traits\CrudTrait;
use Illuminate\Support\Str;

class ActiveCode extends Controller
{
    use CrudTrait;

    /**
     * @var ActiveCodeModel
     */
    protected $model;

    public function __construct()
    {
        $this->model = new ActiveCodeModel();
    }


    public function randGenerate($number)
    {
        for ($i =0 ;$i < $number; $i++){
            $this->model->create([
                'code' => Str::random(64)
            ]);
        }
    }

    public function release($id)
    {
        $model = $this->model->findOrFail($id);
        $model->release();
    }
}
