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

Route::get('/cron/scan-stations', 'CronController@scanStations')->name('scan_stations');
Route::post('/soap-call', 'CronController@soapApiCall')->name('soapApiCall'); // SOAP
Route::get('/cron/send-csr-mail', 'CronController@sendCsrMail')->name('send_csr_mail');
Route::get('/admin/csr-detail/{id}', 'CronController@csrDetail')->name('csr_detail');
Route::get('/admin/tankcron', 'TankCronController@tankcron')->name('tankcron');
//Route::get('/cron/send-trn-mail', 'CronController@sendTrnMail')->name('send-trn-mail');
Route::get('/mail_verify/{email}/{code}', 'CommonController@mail_verify')->name('mail_verify');

Route::get('/', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();


Route::group(['middleware' => 'auth'], function () {

    Route::group(['middleware' => 'role:lotto-manager'], function () {
        //station controller
        Route::get('/admin/stock-list', 'StockController@index')->name('stock');
        Route::get('/admin/stock-create', 'StockController@stock_create')->name('stock_create');
        Route::post('/admin/stock/store', 'StockController@stock_store')->name('stock_store');
        Route::get('/admin/stock/edit/{id}', 'StockController@stock_update')->name('stock_update');
        Route::post('/stock/img_upload', 'StockController@img_upload')->name('img_upload');
        Route::get('/admin/stock-download-list', 'StockController@listDowload')->name('stock_download');
        Route::get('/admin/tankstock_detail/{id}', 'StockController@detail')->name('tankstock_detail');
        Route::post('/admin/get_stock_details', 'StockController@invoice_Details')->name('invoice_Details');
        

    });
    
    Route::group(['middleware' => 'role:admin'], function () {
        //station controller
        Route::get('/admin/station-list', 'StationsController@index')->name('station');
        Route::get('/admin/station-create', 'StationsController@station_create')->name('station_create');
        Route::get('/admin/station/edit/{id}', 'StationsController@station_update')->name('station_update');
        Route::post('/admin/station/store', 'StationsController@station_store')->name('station_store');
        Route::post('/station/img_upload', 'StationsController@img_upload')->name('img_upload');
        // Route::post('admin/station_delete', 'StationsController@station_delete')->name('station_delete');


        
        //common controller
        Route::post('admin/common/list', 'CommonController@listing')->name('admin_list');
        Route::post('admin/common/iupdate', 'CommonController@iupdate')->name('admin_iupdate');

        //user controller
        Route::get('/admin/user-list', 'UsersController@index')->name('user');
        Route::get('/admin/user-create', 'UsersController@user_create')->name('user_create');
        Route::get('/admin/user/edit/{id}', 'UsersController@user_update')->name('user_update');
        Route::post('/admin/user/store', 'UsersController@user_store')->name('user_store');
        Route::post('/validate-user', 'UsersController@validate_user')->name('user_validate');
        Route::get('/admin/company-user/{id}', 'UsersController@company_user')->name('company_user');
        Route::post('/add-company-user', 'UsersController@add_company_user')->name('add_company_user');
        Route::post('/admin/img_upload', 'UsersController@img_upload')->name('img_upload');
    });

    Route::group(['middleware' => 'role:admin|owner|manager'], function () {
        //csr controller
        Route::get('/admin/csr-list', 'CsrController@index')->name('csr');
        Route::get('/admin/csr-mail/{url}', 'CsrController@sendMail')->name('csr_mail');

        //loyalty controller
        Route::post('/admin/user-loyalty-update', 'LoyaltyController@user_loyalty_update')->name('user_loyalty_update');

        Route::get('/admin/stations-loyalty', 'LoyaltyController@stations_loyalty')->name('stations_loyalty');
        Route::post('/admin/stations-loyalty-update', 'LoyaltyController@stations_loyalty_update')->name('stations_loyalty_update');


       Route::get('/admin/station-price', 'PriceController@stations_price')->name('stations_price');
       Route::post('/admin/stations-price-update', 'PriceController@stations_price_update')->name('stations_price_update');

       Route::post('/admin/stations-price-delete', 'PriceController@stations_price_delete')->name('stations_price_delete');

        // //rct
        // Route::get('/admin/rct-list', 'RCTController@index')->name('rct-list');
        // Route::get('/admin/rct-list-download', 'RCTController@listDowload')->name('rct_download');
        // Route::get('/admin/rct-detail/{id}', 'RCTController@detail')->name('rct_detail');
        // Route::get('/admin/rct-mail/{url}', 'RCTController@sendMail')->name('rct_mail');

       //tank controller
        Route::get('/admin/tank-list', 'TankController@index')->name('tank');
        Route::get('/admin/tank-create', 'TankController@tank_create')->name('tank_create');
        Route::get('/admin/tank/edit/{id}', 'TankController@tank_update')->name('tank_update');
        Route::post('/admin/tank/store', 'TankController@tank_store')->name('tank_store');
        // Route::post('admin/tank_delete', 'TankController@tank_delete')->name('tank_delete');
        Route::post('/get_station_details', 'CommonController@get_station_details')->name('get_station_details');
        Route::post('/get_tank_details', 'CommonController@get_tank_details')->name('get_tank_details');

        //tank trn  controller
        Route::get('tank-trn', 'TankTrnController@index')->name('tank-trn');
        Route::get('tank-trn-download', 'TankTrnController@listDowload')->name('tank-trn-download');
        Route::get('/admin/tanktrn_detail/{id}', 'TankTrnController@detail')->name('tanktrn_detail');
        Route::get('/admin/tanktrn_mail/{url}', 'TankTrnController@sendMail')->name('tanktrn_mail');
        
        // stock
        Route::get('/admin/stock-list', 'StockController@index')->name('stock');
        Route::get('/admin/stock-create', 'StockController@stock_create')->name('stock_create');
        Route::post('/admin/stock/store', 'StockController@stock_store')->name('stock_store');
        Route::get('/admin/stock/edit/{id}', 'StockController@stock_update')->name('stock_update');
        Route::post('/stock/img_upload', 'StockController@img_upload')->name('img_upload');
        Route::get('/admin/stock-download-list', 'StockController@listDowload')->name('stock_download');
        Route::get('/admin/tankstock_detail/{id}', 'StockController@detail')->name('tankstock_detail');
        Route::post('/admin/get_stock_details', 'StockController@invoice_Details')->name('invoice_Details');
    });

    Route::group(['middleware' => 'role:admin|owner|manager|company|user'], function () {
        //dashboard
        Route::get('/admin/dashboard', 'DashboardController@index')->name('dashboard');
        //RCT dashboard
        Route::get('/admin/rct-details', 'RCTController@dashboard')->name('rct-details');
        Route::post('admin/common/uploadpdf', 'CommonController@uploadpdf')->name('upload_pdf_file');

        //trn controller
        Route::get('/admin/trn-list', 'TrnController@index')->name('trn');
        Route::get('/admin/trn-detail/{id}', 'TrnController@detail')->name('trn_detail');
        Route::get('/admin/trn-mail/{url}', 'TrnController@sendMail')->name('trn_mail');
        Route::get('/admin/trn-list-download', 'TrnController@listDowload')->name('trn_download');

        //loyalty controller
        Route::get('/admin/loyalty-list', 'LoyaltyController@index')->name('loyalty');

        //user controller
        Route::get('/admin/edit_user', 'UsersController@edit_user')->name('edit_user');
        Route::post('/admin/store_edit_user', 'UsersController@store_edit_user')->name('store_edit_user');
        Route::post('/admin/change_user_pass', 'UsersController@change_user_pass')->name('change_user_pass');
        Route::post('/admin/get_product_details', 'TrnController@get_product_details')->name('get_product_details');
        Route::post('/admin/get_type_details', 'TrnController@get_type_details')->name('get_type_details');

        //rct
        Route::get('/admin/rct-list', 'RCTController@index')->name('rct-list');
        Route::get('/admin/rct-list-download', 'RCTController@listDowload')->name('rct_download');
        Route::get('/admin/rct-detail/{id}', 'RCTController@detail')->name('rct_detail');
        Route::get('/admin/rct-mail/{url}', 'RCTController@sendMail')->name('rct_mail');
    });

    Route::group(['middleware' => 'role:manager|accountant|account-manager|admin|owner'], function () {
       Route::get('/admin/expense', 'ExpenseController@index')->name('expense');
       Route::get('/admin/expense-create', 'ExpenseController@expense_create')->name('expense_create');
       Route::get('/admin/expense/edit/{id}', 'ExpenseController@expense_update')->name('expense_update');
       Route::post('/admin/expense/store', 'ExpenseController@expense_store')->name('expense_store');
       Route::post('/expense/img_upload', 'ExpenseController@img_upload')->name('img_upload');
       Route::post('admin/update_pending_status', 'ExpenseController@update_pending_status')->name('update_pending_status');
       Route::post('admin/get_expense_details', 'ExpenseController@get_expense_details')->name('update_pending_status');
       // Route::post('admin/expense_delete', 'ExpenseController@expense_delete')->name('expense_delete');
       Route::get('/admin/expense-download', 'ExpenseController@listDowload')->name('expense-download');
       Route::get('/admin/periodic-report', 'PeriodicReportController@index')->name('periodic-report');
       Route::get('/admin/periodic-report-download', 'PeriodicReportController@list_download')->name('periodic-report-download');
       Route::post('admin/common/delete', 'CommonController@delete')->name('admin_delete');


    });

    

});
