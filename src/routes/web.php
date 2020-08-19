<?php
#Route::resource('damageType', 'DamageTypeController');
Route::group(['namespace' => '\Swarovsky\Core\Http\Controllers'], static function () {

    Auth::routes(['verify' => true]);
    Route::get('redirect/google', 'Auth\LoginController@redirectToProviderGoogle');
    Route::get('redirect/facebook', 'Auth\LoginController@redirectToProviderFacebook');
    Route::get('callback/google', 'Auth\LoginController@handleProviderCallbackGoogle');
    Route::get('callback/facebook', 'Auth\LoginController@handleProviderCallbackFacebook');


    Route::group(['middleware' => [ 'auth', 'verified']], static function () {

        Route::get('user/2fa', 'Auth\PasswordSecurityController@show2faForm')->name('user.security');
        Route::post('user/generate2faSecret', 'Auth\PasswordSecurityController@generate2faSecret')->name('user.generate2faSecret');

        Route::post('user/enable/2fa', 'Auth\PasswordSecurityController@enable2fa')->name('user.enable2fa');
        Route::post('user/disable/2fa', 'Auth\PasswordSecurityController@disable2fa')->name('user.disable2fa');
        Route::post('user/2faVerify', static function () {
            return redirect(URL()->previous());
        })->name('user.2faVerify')->middleware('2fa');
        Route::get('profile', 'UserController@profile')->name('user.profile');
        Route::put('profile/update', 'UserController@update_profile')->name('user.update_profile');

        Route::group(['middleware' => ['password.confirm', '2fa', 'throttle:120,1']], static function () {
            Route::resource('users', 'UserController');
            Route::resource('roles', 'CrudController');
            Route::resource('permissions', 'CrudController');
        });

     });

});
