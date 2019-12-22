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

function fastCrudRouter($routerName, $controller)
{
    Route::get("$routerName/page/{page}/size/{size}", "$controller@index");
    Route::post($routerName, "$controller@create");
    Route::delete("$routerName/{id}", "$controller@delete");
    Route::put("$routerName/{id}", "$controller@edit");
}

Route::post('admin/admins/login', 'Admin\Admin@login');
Route::prefix('admin')
    ->middleware('auth:admin')// 注意要区分不同模块的用户
    ->group(function () {
        Route::put('admins/logout', 'Admin\Admin@logout');
        fastCrudRouter('admins', "Admin\Admin");
        fastCrudRouter('active_codes', "Admin\ActiveCode");
        Route::post('active_codes/randGenerate/{number}', 'Admin\ActiveCode@randGenerate');
        Route::put('active_codes/release/{id}', 'Admin\ActiveCode@release');
    });
