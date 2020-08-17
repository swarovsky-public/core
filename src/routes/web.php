<?php
#Route::resource('damageType', 'DamageTypeController');

Route::get('user/2fa', 'PasswordSecurityController@show2faForm')->name('user.security');
Route::post('user/generate2faSecret', 'PasswordSecurityController@generate2faSecret')->name('user.generate2faSecret');

Route::post('user/enable/2fa', 'PasswordSecurityController@enable2fa')->name('user.enable2fa');
Route::post('user/disable/2fa', 'PasswordSecurityController@disable2fa')->name('user.disable2fa');
Route::post('user/2faVerify', static function () {
    return redirect(URL()->previous());
})->name('user.2faVerify')->middleware('2fa');


Route::group(['middleware' => [ 'password.confirm', '2fa']], static function () {
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'CrudController');
});
