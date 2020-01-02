<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\WordCategoryModel;

class WordCategory extends Controller
{


    /**
     * @var WordCategoryModel
     */
    protected $model;

    public function __construct()
    {
        $this->model = new WordCategoryModel();
    }

    public function index()
    {
        $list = $this->model->with('children')->get();
        return $list;
    }

    public function words($id)
    {
        $category = $this->model->find($id);
        if (!$category){
            return [];
        }

        $list = $category->words()->where('status','normal')->get();
        return $list;
    }
}