<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\CrudTrait;
use App\WordCategoryModel;
use Illuminate\Http\Request;

class WordCategory extends Controller
{
    use CrudTrait;
}
