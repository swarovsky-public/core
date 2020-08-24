php artisan vendor:publish
to publish css/js 



Edit base model User as follow:

    use Swarovsky\Core\Models\User as Core;                               
    class User extends Core
    {
    ...



Extend admin sidebar
` <li>
        <a class="uk-accordion-title uk-text-small uk-text-bold" href="#">Security</a>
        <div class="uk-accordion-content uk-margin-remove-top">
            <ul class="uk-nav uk-nav-default">
                <li><a href="{{route('roles.index')}}">Roles</a></li>
                ...
            </ul>
        </div>
    </li>`
        
Add new model example goals:
1. make migration
2. create model that extends Swarovsky\Core\Models\AdvancedModel;
3. into web.php (routes)
    Route::group(['namespace' => '\Swarovsky\Core\Http\Controllers'], static function () {
            Route::resource('goals', 'CrudController');
            ...


swarovsky-core (config)
return [
    'layout' => [
        'app' => 'vendor.swarovsky.core.layouts.app',
        'admin' => 'vendor.swarovsky.core.layouts.admin'
    ]
];
