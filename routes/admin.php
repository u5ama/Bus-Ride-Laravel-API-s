<?php


use Illuminate\Support\Facades\Route;


Route::get('/', 'LoginController@index')->name('login');
Route::get('login', 'LoginController@index')->name('login');
Route::post('login', 'LoginController@loginCheck')->name('loginCheck');

Route::group(['middleware' => ['auth:admin', 'adminLanguage']], function () {

    Route::get('dashboard', 'HomeController@index')->name('dashboard');

    // Admin Panel Company Resource
    Route::resource('company', 'CompanyController');
    // Admin Panel Company Status Get Method
    Route::get('getCompanyStatus/{company_id}', 'CompanyController@getCompanyStatus')->name('getCompanyStatus');
    // Admin Panel Company Status Change Method
    Route::get('company/{id}/{status}', 'CompanyController@status')->name('companyStatus');

    // Admin Panel GET Drivers against company
    Route::get('driver/addDriver/{company_id}', 'DriverController@addDriver')->name('driver.addDriver');

    // Admin Panel Edit Driver Route
    Route::get('driver/editDriver/{company_id}/{driver_id}', 'DriverController@edit')->name('driver.editDriver');

    // Admin Panel Update Driver Method
    Route::get('updateDriverStatus/{id}/{status}/{company_id}', 'CompanyController@updateDriverStatus')->name('updateDriverStatus');

    // Admin Panel Update Company Method
    Route::get('updateDriverCompany/{driver_id}/{company_id}', 'CompanyController@updateDriverCompany')->name('updateDriverCompany');

    // admin change driver registtation status alow and disallow in app
    Route::get('changeDriverRegStatus/{id}/{status}', 'CompanyController@changeDriverRegStatus')->name('changeDriverRegStatus');

    // Admin Panel Create New Driver Method
    Route::post('driver/store', 'DriverController@store')->name('driver.store');

    // Company Panel Driver Details Method
    Route::get('getDriverDetail/{id}', 'DriverListController@showPassenger')->name('getDriverDetail');

    Route::resource('bus_stations', 'BusStationsController');

    Route::resource('buses', 'BusesController');

    Route::resource('routes', 'RoutesController');
    Route::resource('withdraw_requests', 'WithdrawRequestsController');
    Route::post('routeStore', 'RoutesController@store')->name('routeStore');

    Route::resource('assign_bus', 'AssignBusesController');
    Route::get('assign_bus/status/{id}/{status}', 'AssignBusesController@changeStatus')->name('assign_bus.status.change');


    Route::resource('admin', 'AdminController');
    Route::resource('on-boarding', 'OnBoardingController');
    Route::get('admin/status/{id}/{status}', 'AdminController@changeStatus')->name('admin.status.change');

    Route::post('getChart', 'AdminController@getChart')->name('getChart');
    Route::post('getUserChart', 'AdminController@getUserChart')->name('getUserChart');

    Route::get('user/status/{id}/{status}', 'UserController@changeStatus')->name('user.status.change');
    Route::get('userDetails/{id}', 'UserController@userDetails')->name('userDetails');
    Route::get('userVehicle', 'UserController@userVehicle')->name('userVehicle');
    Route::get('userAddress', 'UserController@userAddress')->name('userAddress');
    Route::get('editUserVehicle/{id}', 'UserController@editUserVehicle')->name('editUserVehicle');
    Route::get('destroyUserVehicle', 'UserController@destroyUserVehicle')->name('destroyUserVehicle');

    Route::get('profile', 'HomeController@profile')->name('profile');
    Route::post('editProfile', 'HomeController@editProfile')->name('editProfile');
    Route::post('logout', 'LoginController@logout')->name('logout');
    Route::get('password', 'PasswordController@index')->name('password');
    Route::post('changePassword', 'PasswordController@changePassword')->name('changePassword');

    Route::get('changeThemes/{id}', 'HomeController@changeThemes')->name('changeThemes');
    Route::get('changeThemesMode/{local}', 'HomeController@changeThemesMode')->name('changeThemesMode');


    Route::resource('role', 'RoleController');
    Route::resource('user', 'UserController');
    Route::resource('country', 'CountryController');
    Route::resource('permission', 'PermissionController');
    Route::resource('smtp-credential', 'SmtpCredentialController');
    Route::get('smtp-credential/status/{id}/{status}', 'SmtpCredentialController@changeStatus')->name('smtp-credential.status.change');
    Route::resource('fcm-credential', 'FcmCredentialController');
    Route::get('fcm-credential/status/{id}/{status}', 'FcmCredentialController@changeStatus')->name('fcm-credential.status.change');

    Route::resource('language', 'LanguageController');
    Route::get('language/status/{id}/{status}', 'LanguageController@changeStatus')->name('language.status.change');

    Route::resource('language-screen', 'LanguageScreenController');
    Route::post('getLanguageScreen', 'LanguageStringController@getLanguageScreen')->name('getLanguageScreen');
    Route::get('view-language-screen/{id}', 'LanguageScreenController@viewLanguageScreen')->name('view-language-screen');

    Route::get('viewScreenString', 'LanguageScreenController@viewScreenString')->name('viewScreenString');
    Route::get('language-screen/status/{id}/{status}', 'LanguageScreenController@changeStatus')->name('languageScreen.status.change');
    Route::resource('language-string', 'LanguageStringController');
    Route::get('language-string/status/{id}/{status}', 'LanguageStringController@changeStatus')->name('languageString.status.change');

    Route::get('report-problem', 'ReportProblemController@index')->name('report-problem');
    Route::delete('reportProblemDelete/{id}', 'ReportProblemController@destroy')->name('reportProblemDelete');
    Route::get('reportProblemDetails/{id}', 'ReportProblemController@show')->name('reportProblemDetails');
    Route::get('contact-us', 'ContactUsController@index')->name('contact-us');
    Route::delete('contactUsDelete/{id}', 'ContactUsController@destroy')->name('contactUsDelete');

    Route::resource('app-control', 'AppControlController');
    Route::resource('app-menu', 'AppMenusController');
    Route::get('app-menu/{id}/{status}', 'AppMenusController@changeWebView')->name('changeWebView');
    Route::post('app-menu_saveOrder','AppMenusController@saveOrder');
    Route::resource('social-link', 'SocialLinkController');

    Route::post('getChart', 'AdminController@getChart')->name('getChart');
    Route::post('getUserChart', 'AdminController@getUserChart')->name('getUserChart');
    Route::post('settingUpdate', 'SettingController@settingUpdate')->name('settingUpdate');
    Route::get('setting', 'SettingController@index')->name('setting');

    Route::resource('panel-color', 'PanelColorController');
    Route::get('panelColorChangeStatus/{id}/{status}', 'PanelColorController@appColorChangeStatus')->name('panelColorChangeStatus');

   Route::get('appControlChangeStatus/{id}/{status}', 'AppControlController@appControlChangeStatus')->name('appControlChangeStatus');
    Route::get('socialLinkChangeStatus/{id}/{status}', 'SocialLinkController@socialLinkChangeStatus')->name('socialLinkChangeStatus');


    Route::resource('body', 'BodyController');
    Route::resource('brand', 'BrandController');
    Route::resource('engine', 'EngineController');
    Route::resource('vehicle-type', 'VehicleTypeController');
    Route::resource('user-vehicle', 'UserVehicleController');
    Route::resource('emergency-service', 'EmergencyServiceController');
    Route::resource('quick-service', 'QuickServiceController');
    Route::resource('page', 'PageController');
    Route::resource('notification', 'NotificationController');
    Route::resource('dealer', 'DealerController');
    Route::post('getBrands', 'UserController@getBrands')->name('getBrands');
    Route::post('getModels', 'UserController@getModels')->name('getModels');
    Route::post('userVehicleStore', 'UserController@userVehicleStore')->name('userVehicleStore');
    Route::get('userAddressAdd', 'UserController@userAddressAdd')->name('userAddressAdd');
    Route::get('createAddress/{id}', 'UserController@createAddress')->name('createAddress');
    Route::post('userAddressStore', 'UserController@userAddressStore')->name('userAddressStore');

     Route::get('details/{id}', 'NotificationController@details')->name('details');

    Route::get('orderBy','OnBoardingController@orderBy')->name('orderBy');
    Route::post('saveOrder','OnBoardingController@saveOrder')->name('saveOrder');
});
