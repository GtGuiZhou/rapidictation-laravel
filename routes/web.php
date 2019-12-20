<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

function fastCrudRouter($routerName,$controller){
    Route::get("$routerName/page/{page}/size/{size}","$controller@index");
    Route::post($routerName,"$controller@create");
    Route::delete("$routerName/{id}","$controller@delete");
    Route::put("$routerName/{id}","$controller@edit");
}

Route::prefix('admin')->group(function () {
    fastCrudRouter('admins',"Admin\Admin");
});
