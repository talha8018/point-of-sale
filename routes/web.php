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
	Route::get('product/barcode/exist/{barcode}','Product\ProductController@barcodeExists');
	Route::get('product/barcode/update/exist/{barcode}/{id}','Product\ProductController@barcodeUpExists');
	Route::post('product/ajax/get-products','Product\ProductController@getProductsByCompanyId');
	// Product Routes End

	// Stock Routes Start
	Route::get('stock','Stock\StockController@show');
	Route::get('stock/search','Stock\StockController@stockSearch');
	Route::post('stock/add','Stock\StockController@insert');
	Route::post('stock/update','Stock\StockController@update');
	Route::get('stock/get-product-by-id/{id}','Stock\StockController@getStockByProductID');
	Route::post('stock/quantity/update','Stock\StockController@quantityUpdate');
	// Stock Routes End

	// Partner Routes Start
	Route::get('partners','Partner\PartnerController@show');	
	Route::post('partner/add','Partner\PartnerController@insert');	
	Route::post('partner/update','Partner\PartnerController@update');	
	Route::get('partner/delete/{id}','Partner\PartnerController@delete');	
	// Parnter Routes End

	// Purchase Routes Start
	Route::get('purchases','Purchase\PurchaseController@show');		
	Route::post('purchases/temp','Purchase\PurchaseController@insertTemp');		
	Route::post('purchases/make','Purchase\PurchaseController@makePurchase');				
	Route::get('purchases/temp/clear-all','Purchase\PurchaseController@deleteAll');		
	Route::get('purchases/temp/delete/{id}','Purchase\PurchaseController@deleteTemp');		
	Route::get('purchases/history','Purchase\PurchaseController@history');		
	Route::get('purchases/history/search','Purchase\PurchaseController@historySearch');		
	Route::get('purchases/bill/{bill}','Purchase\PurchaseController@searchBillByID');		
	// Purchase Routes End


	// Movements Routes Start
	Route::get('movements','Movements\MovementsController@show');		
	Route::get('movements/search','Movements\MovementsController@search');	
	Route::post('movements/add','Movements\MovementsController@insert');			
	// Movements Routes End
	
	// Sale Routes Start
	Route::get('sale','Sale\SaleController@show');
	Route::post('sale/temp','Sale\SaleController@insertTemp');		
	Route::get('sale/temp/delete/{product_id}','Sale\SaleController@deleteTemp');		
	Route::get('sale/temp/clear-all','Sale\SaleController@deleteAll');		
	Route::get('sale/temp/update-customer','Sale\SaleController@updateCustomer');		
	Route::get('sale/discount','Sale\SaleController@discount');		
	Route::post('sale/make','Sale\SaleController@makeSale');				
	Route::get('sale/history','Sale\SaleController@history');	
	Route::get('sale/history/search','Sale\SaleController@historySearch');
	Route::get('sale/bill/{bill}','Sale\SaleController@searchBillByID');		
			
	
	// Sale Routs End

});




Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
