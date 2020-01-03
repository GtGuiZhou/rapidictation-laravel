<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\WordModel;
use Illuminate\Http\Request;


class Word extends Controller
{

    /**
     * @var WordModel
     */
    protected $model;

    public function __construct()
    {
        $this->model = new WordModel();
    }


    public function create(Request $request)
    {
        $word = $request->input('word');
        $wordModel = $this->model->where('word',$word)->first();
        if (!$wordModel){
            $this->model->word = $word;
            $this->model->translation()->save();
            $wordModel = $this->model;
        }

        return $wordModel;
    }
} 