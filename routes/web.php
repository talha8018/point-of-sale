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


Route::middleware(['auth'])->group(function () {
	Route::get('dashboard','Dashboard\DashboardController@show')->name('dashboard');

	// Company Routes Start
	Route::get('companies','Company\CompanyController@show');
	Route::post('company/add','Company\CompanyController@insert');
	Route::post('company/update','Company\CompanyController@update');
	Route::get('company/delete/{id}','Company\CompanyController@delete');
	// Company Routes End

	// Product Routes Start
	Route::get('products','Product\ProductController@show');
	Route::post('product/add','Product\ProductController@insert');
	Route::post('product/update','Product\ProductController@update');
	Route::get('product/delete/{id}','Product\ProductController@delete');
	Route::post('product/ajax/get-products','Product\ProductController@getProductsByCompanyId');
	// Product Routes End

	// Stock Routes Start
	Route::get('stock','Stock\StockController@show');
	Route::post('stock/add','Stock\StockController@insert');
	Route::post('stock/update','Stock\StockController@update');
	// Stock Routes End
});




Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
