<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin','admin\AdminController@index')->name('admin');
$router->group(['prefix' => 'admin'], function($router)
{
    $router->post('/showCategories','admin\CategoriesController@showCategories');
    $router->post('/showaddCategoryForm','admin\CategoriesController@showaddCategoryForm');
    $router->post('/addCategory','admin\CategoriesController@addCategory');
});

$router->group(['prefix' => 'api/v1'], function($router)
{
    //M-pesa Integrations APIs

    $router->post('/generateAccessToken','MpesaController@generateAccessToken');
    $router->post('/customerMpesaSTKPush','MpesaController@customerMpesaSTKPush');
    $router->post('/validationCallback','MpesaController@validation');
    $router->post('/confirmationCallback','MpesaController@confirmationCallback');
    $router->post('/stkConfirmationCallback','MpesaController@stkConfirmationCallback');
    $router->post('/registerUrls','MpesaController@registerUrls');
});

