<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\CrudTrait;
use App\WordModel;
use Illuminate\Http\Request;

class Word extends Controller
{
    use CrudTrait;

    protected $searchFields = ['ts'];

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
        if ($this->createRequest){
            $data = $this->createRequest->validated();
        } else {
            $data = \request()->input();
        }
        if (preg_match('/[a-z]+$/',strtolower($data['word']))) {
            $this->model->firstOrCreate(['word' => $data['word']]);
        }
    }

    public function batchImport(Request $request)
    {
        $words = $request->input();
        foreach ($words as $word){
            if (preg_match('/[a-z]+$/',strtolower($word))){
                $this->model->firstOrCreate(['word' => $word]);
            }
        }
    }

    public function againTranslation(Request $request ,$id)
    {
        $this->model = $this->model->findOrFail($id);
        $this->model->translation()->save();
    }



}
