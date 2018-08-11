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
	// Company Routes End
	// Product Routes Start
	Route::group(['middleware' => ['permission:cp-create']], function () {  
		Route::get('products/add','Product\ProductController@addProduct');
		Route::post('product/add','Product\ProductController@insert');
		Route::post('company/add','Company\CompanyController@insert');
	});

	Route::group(['middleware' => ['permission:cp-list']], function () {  
		Route::get('products','Product\ProductController@show');
		Route::get('companies','Company\CompanyController@show');
	});

	Route::group(['middleware' => ['permission:cp-edit']], function () { 
		Route::post('company/update','Company\CompanyController@update');
		Route::post('product/update','Product\ProductController@update');
		Route::get('products/update/{id}','Product\ProductController@updateProduct');
	});
	Route::group(['middleware' => ['permission:cp-delete']], function () { 
		Route::get('company/delete/{id}','Company\CompanyController@delete');
		Route::get('product/delete/{id}','Product\ProductController@delete');
	});
	Route::get('product/barcode/exist/{barcode}','Product\ProductController@barcodeExists');
	Route::get('product/barcode/update/exist/{barcode}/{id}','Product\ProductController@barcodeUpExists');
	Route::post('product/ajax/get-products','Product\ProductController@getProductsByCompanyId');
	// Product Routes End

	// Partner Routes Start
	Route::group(['middleware' => ['permission:partner-create']], function () {  
		Route::get('partner/add-partner','Partner\PartnerController@addPartner');  
		Route::post('partner/add','Partner\PartnerController@insert');	
	});
	Route::group(['middleware' => ['permission:partner-list']], function () {  
		Route::get('partners','Partner\PartnerController@show');  
	});
	Route::group(['middleware' => ['permission:partner-edit']], function () {  
		Route::get('partner/update-partner/{id}','Partner\PartnerController@updatePartner');  
		Route::post('partner/update','Partner\PartnerController@update');
	});
	//Route::get('partner/delete/{id}','Partner\PartnerController@delete');	
	// Parnter Routes End



	// Stock Routes Start
	Route::group(['middleware' => ['permission:stock']], function () {  
		Route::get('stock','Stock\StockController@show');
		Route::get('stock/search','Stock\StockController@stockSearch');
		Route::post('stock/add','Stock\StockController@insert');
		Route::post('stock/update','Stock\StockController@update');
		Route::get('stock/get-product-by-id/{id}','Stock\StockController@getStockByProductID');
		Route::post('stock/quantity/update','Stock\StockController@quantityUpdate');
	});
	// Stock Routes End




	// Purchase Routes Start
	Route::group(['middleware' => ['permission:purchase']], function () {  
		Route::get('purchases','Purchase\PurchaseController@show');		
		Route::post('purchases/temp','Purchase\PurchaseController@insertTemp');		
		Route::post('purchases/make','Purchase\PurchaseController@makePurchase');				
		Route::get('purchases/temp/clear-all','Purchase\PurchaseController@deleteAll');		
		Route::get('purchases/temp/delete/{id}','Purchase\PurchaseController@deleteTemp');
	});
	Route::group(['middleware' => ['permission:purchase-history']], function () {  
		Route::get('purchases/history','Purchase\PurchaseController@history');		
		Route::get('purchases/history/search','Purchase\PurchaseController@historySearch');		
		Route::get('purchases/bill/{bill}','Purchase\PurchaseController@searchBillByID');
	});
	
	Route::group(['middleware' => ['permission:purchase-edit']], function () {  
		Route::get('purchase/edit','Purchase\PurchaseController@showEdit');	
		Route::post('purchase/edit','Purchase\PurchaseController@editBill');
	});
	Route::group(['middleware' => ['permission:purchase-delete']], function () {  
		Route::post('purchase/delete','Purchase\PurchaseController@deleteBill');			
	});
	// Purchase Routes End


	// Movements Routes Start
	Route::group(['middleware' => ['permission:movements-list']], function () {  
		Route::get('movements','Movements\MovementsController@show');		
		Route::get('movements/search','Movements\MovementsController@search');	
	});
	Route::group(['middleware' => ['permission:movements-add']], function () {  
		Route::post('movements/add','Movements\MovementsController@insert');					
	});	
	// Movements Routes End
	
	// Sale Routes Start
	Route::group(['middleware' => ['permission:sale']], function () {  
	
	Route::get('sale','Sale\SaleController@show');
	Route::post('sale/temp','Sale\SaleController@insertTemp');		
	Route::get('sale/temp/delete/{product_id}','Sale\SaleController@deleteTemp');		
	Route::get('sale/temp/clear-all','Sale\SaleController@deleteAll');		
	Route::get('sale/temp/update-customer','Sale\SaleController@updateCustomer');		
	Route::get('sale/discount','Sale\SaleController@discount');		
	Route::post('sale/make','Sale\SaleController@makeSale');
	});
	Route::group(['middleware' => ['permission:sale-history']], function () {  
		Route::get('sale/history','Sale\SaleController@history');	
		Route::get('sale/history/search','Sale\SaleController@historySearch');
		Route::get('sale/bill/{bill}','Sale\SaleController@searchBillByID');	
	});	
	
	Route::group(['middleware' => ['permission:sale-edit']], function () {  
		Route::get('sale/edit','Sale\SaleController@showEdit');			
		Route::get('sale/get-bill/{bill}','Sale\SaleController@getBill');			
		Route::post('sale/edit','Sale\SaleController@editBill');
	});			

	Route::group(['middleware' => ['permission:sale-delete']], function () {  
		Route::post('sale/delete','Sale\SaleController@deleteBill');
	});			
	// Sale Routs End

});




Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test', 'TestController@test');
