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

Route::get('/cron_script/{pass}', 'ScriptController@cronScript')->name('cron_script');
Route::get('/cron_script/{dir}/{action}', 'ScriptController@scanStations')->name('scan_station');

Route::get('/cron/scan-stations', 'CronController@scanStations')->name('scan_stations');
Route::post('/soap-call', 'CronController@soapApiCall')->name('soapApiCall'); // SOAP
Route::get('/admin/tankcron', 'TankCronController@tankcron')->name('tankcron');
//Route::get('/cron/send-trn-mail', 'CronController@sendTrnMail')->name('send-trn-mail');
Route::get('/mail_verify/{email}/{code}', 'CommonController@mail_verify')->name('mail_verify');

Route::get('/', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();


Route::group(['middleware' => 'auth'], function () {


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
        Route::post('/admin/store_edit_posadmin', 'UsersController@store_edit_posadmin')->name('store_edit_posadmin');
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

    //lotto MAnager
    Route::group(['middleware' => 'role:sai-manager'], function () {

            //Dashboard COntrollers
            
            Route::get('admin/showEmployees', 'EmployeeController@showEmployees')->name('showEmployees');
            Route::get('/employee/pdf', 'EmployeeController@createPDF')->name('createPDF');
            Route::get('admin/invoice', 'EmployeeController@invoice')->name('invoice');


        Route::get('/admin/posdashboard', 'PosDashboardController@index')->name('posdashboard');
        

        Route::get('/admin/user-list', 'UsersController@index')->name('users');
        Route::get('/admin/user-create', 'UsersController@user_create')->name('user_create');
        Route::post('/admin/user/store', 'UsersController@user_store')->name('user_store');
        Route::get('/admin/user/edit/{id}', 'UsersController@user_update')->name('user_update');

        Route::get('/admin/edit_user', 'UsersController@edit_user')->name('edit_user');

        Route::post('/admin/store_edit_user', 'UsersController@store_edit_user')->name('store_edit_user');
        Route::post('/admin/change_user_pass', 'UsersController@change_user_pass')->name('change_user_pass');
        Route::get('/admin/exportUsers','CustomersController@exportUsers')->name('exportUsers');
        Route::get('/admin/generate_pdf', 'CustomersController@generate_pdf')->name('generate_pdf');

        // order Create
        Route::get('/admin/order-list', 'OrderController@index')->name('order');
        Route::get('/admin/order-create', 'OrderController@order_create')->name('order_create');
        Route::post('/admin/order/store', 'OrderController@order_store')->name('order_store');
        Route::get('/admin/order/edit/{id}', 'OrderController@order_update')->name('order_update');
        Route::post('/order/img_upload', 'OrderController@img_upload')->name('img_upload');
        Route::post('/admin/order/search_order_data', 'OrderController@search_data_order')->name('search_data_order');
        Route::get('/admin/costing/{costid}', 'OrderController@costing')->name('costing');
        Route::get('/admin/order/generate_invoicepdf/{id}', 'OrderController@generate_invoicepdf')->name('generate_invoicepdf');
        Route::post('/admin/order/get_serializedata', 'OrderController@get_serializedata')->name('get_serializedata');
        Route::get('/admin/order/order_detail/{id}', 'OrderController@order_detail')->name('order_detail');
        Route::get('/admin/order/order_delete/{id}', 'OrderController@order_delete')->name('order_delete');
        
        // send sms script
        Route::get('/admin/order/send_sms_quotation_msg', 'OrderController@send_sms_quotation_msg')->name('send_sms_quotation_msg');
        Route::get('/admin/order/send_sms_reminder_msg', 'OrderController@send_sms_reminder_msg')->name('send_sms_reminder_msg');
        Route::get('/admin/order/send_sms_thanku_msg', 'OrderController@send_sms_thanku_msg')->name('send_sms_thanku_msg');
        //order complete status script
        Route::get('/admin/order/order_complte_success', 'OrderController@order_complte_success')->name('order_complte_success');
        
        // Category Create
        Route::get('/admin/category-list', 'CategoryController@index')->name('category');
        Route::get('/admin/category-create', 'CategoryController@category_create')->name('category_create');
        Route::post('/admin/category/store', 'CategoryController@category_store')->name('category_store');
        Route::get('/admin/category/store', 'CategoryController@category_store')->name('category_store');
        Route::get('/admin/category/edit/{id}', 'CategoryController@category_update')->name('category_update');
        Route::post('/category/img_upload', 'CategoryController@img_upload')->name('img_upload');
        Route::post('/admin/category/search_category_data', 'CategoryController@search_data_category')->name('search_data_category');

        // Banner Route
        Route::get('/admin/banner-list', 'BannerController@index')->name('banner');
        Route::get('/admin/banner-create', 'BannerController@banner_create')->name('banner_create');
        Route::post('/admin/banner/store', 'BannerController@banner_store')->name('banner_store');
        Route::get('/admin/banner/edit/{id}', 'BannerController@banner_update')->name('banner_update');
        Route::post('/banner/img_upload', 'BannerController@img_upload')->name('img_upload');
        Route::post('/admin/banner/search_banner_data', 'BannerController@search_data_banner')->name('search_data_banner');

         // Ticket Create
         Route::get('/admin/ticket-list', 'TicketController@index')->name('ticket');
         Route::get('/admin/ticket-create', 'TicketController@ticket_create')->name('ticket_create');
         Route::post('/admin/ticket/store', 'TicketController@ticket_store')->name('ticket_store');
         Route::get('/admin/ticket/edit/{id}', 'TicketController@ticket_update')->name('ticket_update');
         Route::post('/ticket/img_upload', 'TicketController@img_upload')->name('img_upload');
         Route::post('/admin/ticket/search_ticket_data', 'TicketController@search_data_ticket')->name('search_data_ticket');
         // Discount Create
         Route::get('/admin/discount-list', 'DiscountController@index')->name('discount');
         Route::get('/admin/discount-create', 'DiscountController@discount_create')->name('discount_create');
         Route::post('/admin/discount/store', 'DiscountController@discount_store')->name('discount_store');
         Route::get('/admin/discount/edit/{id}', 'DiscountController@discount_update')->name('discount_update');
         Route::post('/discount/img_upload', 'DiscountController@img_upload')->name('img_upload');
         Route::post('/admin/discount/search_discount_data', 'DiscountController@search_data_discount')->name('search_data_discount');

         // Inventory Modules
        Route::get('/admin/inventory-list', 'InventoryController@index')->name('inventory');
        Route::get('/admin/inventory-create', 'InventoryController@inventory_create')->name('inventory_create');
        Route::post('/admin/inventory/store', 'InventoryController@inventory_store')->name('inventory_store');
        Route::get('/admin/inventory/edit/{id}', 'InventoryController@inventory_update')->name('inventory_update');
        Route::post('/inventory/img_upload', 'InventoryController@img_upload')->name('img_upload');
        Route::post('/admin/inventory/get_stock_details', 'InventoryController@invoice_Details')->name('invoice_Details');
        Route::post('/admin/inventory/get_update_stock', 'InventoryController@update_Stock')->name('update_Stock');
        Route::post('/admin/inventory/add_new_stock', 'InventoryController@new_Stock')->name('new_Stock');
        Route::post('/admin/inventory/search_inventory_data', 'InventoryController@search_data_inventory')->name('search_data_inventory');
		
       //withorders
        Route::get('/admin/withorder-list', 'WithorderController@index')->name('withorder');
      
        // Booking order Modules
        Route::get('/admin/booking-list', 'BookingorderController@index')->name('booking');
        Route::post('/admin/booking/booking_detail/{id}', 'BookingorderController@booking_detail')->name('booking_detail');       
        Route::post('/admin/booking/search_booking_get', 'BookingorderController@search_data')->name('search_data');        
        
        // Feedback route
        Route::get('/admin/feedback-list', 'FeedbackController@index')->name('feedback');
        // Contact route
        Route::get('/admin/contact-list', 'ContactController@index')->name('contact');
        Route::post('/admin/contact/search_contact_data', 'ContactController@search_data')->name('search_data');

        // Faq route
         Route::get('/admin/faq-list', 'FaqController@index')->name('faq');
         Route::get('/admin/faq-create', 'FaqController@faq_create')->name('faq_create');
         Route::post('/admin/faq/store', 'FaqController@faq_store')->name('faq_store');
         Route::get('/admin/faq/edit/{id}', 'FaqController@faq_update')->name('faq_update');
         Route::post('/admin/faq/search_faq_data', 'FaqController@search_data_faq')->name('search_data_faq');

         // privacy policy
         Route::get('/admin/privacy-list', 'PrivacyController@index')->name('privacy');
         Route::get('/admin/privacy-create', 'PrivacyController@privacy_create')->name('privacy_create');
         Route::post('/admin/privacy/store', 'PrivacyController@privacy_store')->name('privacy_store');
         Route::get('/admin/privacy/edit/{id}', 'PrivacyController@privacy_update')->name('privacy_update');
         Route::post('/admin/privacy/search_privacy_data', 'PrivacyController@search_data_privacy')->name('search_data_privacy');

         // Terms conditions
         Route::get('/admin/terms-list', 'TermsController@index')->name('terms');
         Route::get('/admin/terms-create', 'TermsController@terms_create')->name('terms_create');
         Route::post('/admin/terms/store', 'TermsController@terms_store')->name('terms_store');
         Route::get('/admin/terms/edit/{id}', 'TermsController@terms_update')->name('terms_update');
         Route::post('/admin/terms/search_terms_data', 'TermsController@search_data_terms')->name('search_data_terms');
        // POS sales
        Route::get('/admin/sales-list', 'SalesController@index')->name('sales');
        Route::post('/admin/sales/get_sales_items', 'SalesController@order_Items')->name('order_Items');
        Route::get('/admin/sales/sales_detail/{id}', 'SalesController@sales_detail')->name('sales_detail');
        
        // Pos Zreports
        Route::get('/admin/zreports-list', 'ZreportsController@index')->name('zreports');
        Route::post('/admin/zreports/get_zreports_items', 'ZreportsController@order_Items')->name('order_Items');
        Route::get('/admin/zreports/zreports_detail/{id}', 'ZreportsController@zreports_detail')->name('zreports_detail');

        // Customer create to ticket
        Route::get('/admin/customer-list', 'CustomersController@index')->name('customer');
        Route::get('/admin/customer-create', 'CustomersController@customer_create')->name('customer_create');
        Route::get('/admin/customer/edit/{id}', 'CustomersController@customer_update')->name('customer_update');
        Route::post('/admin/customer/store', 'CustomersController@customer_store')->name('customer_store');
        Route::post('/validate-user', 'UsersController@validate_user')->name('user_validate');
        Route::get('/admin/company-user/{id}', 'UsersController@company_user')->name('company_user');
        Route::post('/add-company-user', 'UsersController@add_company_user')->name('add_company_user');
        Route::post('/admin/img_upload', 'UsersController@img_upload')->name('img_upload');

        // Book calender
        Route::get('/admin/booking_calender','BookCalenderController@booking_calneders')->name('calender');
        Route::post('calendar-crud-ajax', 'BookCalenderController@calendarEvents')->name('events');
        Route::get('calendar-event', 'BookCalenderController@index')->name('cale');


    });

});
Route::get('/','Front\HomeController@index');
Route::get('/about','Front\HomeController@about');
Route::get('/contact','Front\HomeController@contact');
Route::get('/faq','Front\HomeController@faq');
Route::get('/terms','Front\HomeController@terms');
Route::get('/privacy','Front\HomeController@privacy');
Route::get('/month','Front\HomeController@month');
Route::post('/contact_store','Front\HomeController@contact_store');
Route::get('/order_register','Front\HomeController@order_register');
Route::get('/get_states','Front\HomeController@get_states');
Route::get('/get_city','Front\HomeController@get_city');

Route::get('/registers','Front\RegisterController@registers');
Route::post("/front/store", "Front\RegisterController@store");

Route::get('/logins','Front\LoginController@logins');
Route::post('/frontlogin','Front\LoginController@frontlogin');
Route::post('/store_profile','Front\HomeController@store_profile');

Route::get('/user_profile','Front\HomeController@user_profile');

//06-04-2022
Route::get('/howtoplay','Front\HomeController@howtoplay');
Route::get('/howtowin','Front\HomeController@howtowin');

Route::get('/frontlogout','Front\LoginController@frontlogout'); 


