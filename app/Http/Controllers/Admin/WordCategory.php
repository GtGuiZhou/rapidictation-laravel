<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller;
use App\Http\Traits\CrudTrait;
use App\WordCategoryModel;
use App\WordModel;
use Illuminate\Http\Request;

class WordCategory extends Controller
{
    use CrudTrait;


    /**
     * @var WordCategoryModel
     */
    protected $model;

    public function __construct()
    {
        $this->model = new WordCategoryModel();
    }


    public function indexWord($id)
    {
        $this->model = $this->model->findOrFail($id);
        $list = $this->model->words()->get();

        return $list;
    }

    public function createWord(Request $request,$id)
    {
        $this->model = $this->model->findOrFail($id);
        $data = $request->post();
        $wordModel = WordModel::where('word',$data['word'])->first();
        if ($wordModel){
            if ($this->model->words()->find($wordModel->id)){
                throw new InvalidRequestException('已存在该列表中');
            }
            $this->model->words()->attach($wordModel->id);
        } else {
          $wordModel =  $this->model->words()->create($data);
        }

        return $wordModel;
    }



}
