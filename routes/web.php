<?php
use Pusher\Laravel\Facades\Pusher;
use Illuminate\Support\Facades\App;
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





Route::get('/', function() {

    return view('welcome');
});

Route::get('/example', function() {

    return view('example');
});


Route::post('signup', 'AuthController@register');
Route::post('login', 'AuthController@login');


Route::group(['middleware' => ['web']], function () {

    Auth::routes();
    Route::get('/home', 'HomeController@index')->name('home');

    Route::post('/checkUser', 'HomeController@checkUser');

    Route::post('/deleteInfo', 'HomeController@deleteInfo');

});

Route::group(['middleware' => 'jwt.auth'], function(){
    Route::get('auth/user', 'AuthController@user');
    Route::post('auth/logout', 'AuthController@logout');
    Route::middleware('jwt.refresh')->get('/token/refresh', 'AuthController@refresh');

});
