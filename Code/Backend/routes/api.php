<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(['prefix' => 'v1'],
    function () {

        //sign in & register
        Route::post('authenticate', 'Auth\AuthenticateController@authenticate');
        Route::post('authenticate/invalidate', 'Auth\AuthenticateController@invalidate');
        Route::post('authenticate/register', 'Auth\AuthenticateController@register');

        //Social login
        Route::get('social-callback/{provider}', 'Auth\AuthenticateController@handleSocialCallback');
        Route::post('social-login', 'Auth\AuthenticateController@socialLogin');

        //Forget password
        Route::post('authenticate/forget-password', 'Auth\Password\ForgotPasswordController@getResetToken');
        Route::post('authenticate/reset-password', 'Auth\Password\ResetPasswordController@reset');
        Route::post('authenticate/valid-code', 'Auth\Password\ResetPasswordController@codeValid');

        //Verify user
        Route::post('authenticate/verify-account', 'Auth\AuthenticateController@verifyUser');
        Route::post('authenticate/send-confirmation-code', 'Auth\AuthenticateController@resendVerificationCode');

        //Image upload
        Route::post('upload', 'UploadController@uploadImage');
        //lookups
        Route::get('get-all-lookups', 'UserController@getAllLookups');

        //countries
        Route::get('countries/{id}', 'CountryController@show');
        Route::get('countries', 'CountryController@index');
        Route::get('countries/{id}/cities', 'CountryController@getCitiesOfCountry');

        //platforms
        Route::get('platforms', 'PlatformController@index');

        //cities
        Route::get('cities/{id}', 'CityController@show');
        Route::get('cities', 'CityController@index');

        //languages
        Route::get('languages/{id}', 'LanguageController@show');
        Route::get('languages', 'LanguageController@index');

        //gender
        Route::get('genders/{id}', 'GenderController@show');
        Route::get('genders', 'GenderController@index');

        //categories
        Route::get('categories/{id}', 'CategoryController@show');
        Route::get('categories', 'CategoryController@index');


        //master search
        Route::post('masters/filteredList', 'MasterController@getPagedList');
        Route::get('masters/filterData', 'MasterController@getMastersFilterData');

        //master details
        Route::get('masters/{master_id}' , 'MasterController@show');

        Route::post('trending/filteredList', 'TrendingListController@getPagedList');
        Route::post('featured/filteredList', 'FeaturedController@getPagedList');
        Route::post('trending-featured' , 'MasterController@getTrendingAndFeatured');

    });

Route::group(['prefix' => 'v1', 'middleware' => ['jwt.customAuth']],
    function () {

        //users
        Route::get('authenticate/user', 'Auth\AuthenticateController@getAuthenticatedUser');
        Route::post('users/filtered-list', 'UserController@getPagedList');
        Route::resource('users', 'UserController');

        Route::post('users/update-profile' , 'UserController@updateProfile');

        //Roles & access
        Route::post('roles/filtered-list', 'RoleController@getPagedList');
        Route::resource('roles', 'RoleController');

        Route::resource('master-ratings', 'MasterRatingController');
        Route::post('add-fav', 'FavoritesController@addFavorite');
        Route::post('remove-fav' , 'FavoritesController@removeFavorite');
        Route::resource('favorites', 'FavoritesController');

        Route::post('masters', 'MasterController@store');
        Route::post('masters/validate','MasterController@validateMasterRequest');

        Route::post('users/change-password' , 'UserController@changePassword');

        Route::post('requestContact', 'ContactRequestController@requestContact');

        Route::get('getUserNotifications', 'NotificationController@getUserNotifications');
        Route::post('markNotificationAsRead', 'NotificationController@readUserNotifications');


        Route::group(['middleware' => 'CheckUserIsAdmin'],
            function () {
                Route::resource('cities', 'CityController');
                Route::resource('countries', 'CountryController');
                Route::resource('languages', 'LanguageController');
                Route::resource('genders', 'GenderController');
                Route::resource('categories', 'CategoryController');
                Route::get('main-categories', 'CategoryController@mainCategory');
                Route::resource('notifications', 'NotificationController');
                Route::resource('admin/masters', 'AdminMasterController');
                Route::post('admin/masters/filtered-list', 'AdminMasterController@getPagedList');
                Route::post('admin/masters/auto-complete', 'AdminMasterController@autoCompleteMasters');
                Route::post('admin/master/request/update-status', 'AdminMasterController@updateStatus');
                Route::resource('currencies', 'CurrencyController');
            });

        Route::group(['middleware' => 'CheckMasterAuthorized'],
            function () {
                Route::resource('masters/{master_id}/packages', 'PackageController');
                Route::post('packages/store-list' , 'PackageController@storeList');
                Route::put('masters/{master_id}', 'MasterController@update');
                Route::post('acceptContactRequest/{request_id}', 'ContactRequestController@acceptContactRequest');
                Route::post('rejectContactRequest/{request_id}', 'ContactRequestController@rejectContactRequest');
                Route::resource('masters/{master_id}/contactRequests', 'ContactRequestController');
            });


        Route::group(['prefix' => 'userAccess'],
            function () {
                Route::get('users/rights', 'RoleController@getRoleRights');
                Route::get('roles/modules-rights', 'RoleController@getModulesRights');
                Route::get('roles/CanAccess/{rightId}', 'RoleController@CanAccess');
            });
    });
