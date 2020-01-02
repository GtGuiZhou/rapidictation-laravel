<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\WordModel;

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


    public function create()
    {
        
    }
} 