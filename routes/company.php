<?php


use Illuminate\Support\Facades\Route;


// Company Panel Login Route
Route::get('login', 'CompanyLoginController@showLoginForm')->name('login');

// Company Panel Login POST Method
Route::post('companyPostLogin', 'CompanyLoginController@login')->name('companyPostLogin');

// Create New Company account Route
Route::get('register', 'CompanyLoginController@register')->name('index');

// Create New Company account POST METHOD
Route::post('registerCompany', 'CompanyLoginController@registerCompany')->name('registerCompany');

// Create New Company succcess
Route::get('success', 'CompanyLoginController@successMessage')->name('success');


Route::group(['middleware' => ['company']], function () {
        // Company Panel Dashboard Route
        Route::get('company', 'CompanyController@index')->name('company');

        // Company Panel Profile Route
        Route::get('profile', 'CompanyController@profile')->name('profile');

        // Company Panel Update Profile Method
        Route::post('updateProfile', 'CompanyController@updateProfile')->name('updateProfile');

        Route::resource('driver', 'DriverController');
        Route::post('driver/store', 'DriverController@store')->name('driver.store');

        // Admin Panel Update Driver Method
        Route::get('updateDriverStatus/{id}/{status}/{company_id}', 'DriverController@updateDriverStatus')->name('updateDriverStatus');

        Route::resource('panel-color', 'PanelColorController');

        //Company Bus Stations
        Route::resource('bus_stations', 'BusStationsController');

        //Company Buses
        Route::resource('buses', 'BusesController');

        //Company routes
        Route::resource('routes', 'RoutesController');

        //Company routes store
        Route::post('routeStore', 'RoutesController@store')->name('routeStore');

        //Company Payment Accounts
        Route::resource('payment_account', 'PaymentAccountController');

        //Company Assign Buses
        Route::resource('assign_bus', 'AssignBusesController');

        //Company withdraw
        Route::resource('withdraw', 'WithdrawController');

        Route::get('assign_bus/status/{id}/{status}', 'AssignBusesController@changeStatus')->name('assign_bus.status.change');

        Route::get('panelColorChangeStatus/{id}/{status}', 'PanelColorController@appColorChangeStatus')->name('panelColorChangeStatus');

        Route::get('changeThemes/{id}', 'HomeController@changeThemes')->name('changeThemes');

        Route::get('changeThemesMode/{local}', 'HomeController@changeThemesMode')->name('changeThemesMode');

        Route::post('logout', 'CompanyLoginController@logout')->name('logout');

});
