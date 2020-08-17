<?php

namespace Swarovsky\Core\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Spatie\Permission\Middlewares\RoleOrPermissionMiddleware;
use Swarovsky\Core\Http\Middleware\AdvancedPermissionMiddleware;
use Swarovsky\Core\Http\Middleware\Google2FAMiddleware;
use Swarovsky\Core\Services\FormService;
use Swarovsky\Core\Facades\FormFacade;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // use this if your package has views
        $this->loadViewsFrom(realpath(dirname(__DIR__, 1) . '/resources/views'), 'swarovsky-core');

        // use this if your package has lang files
        $this->loadTranslationsFrom(dirname(__DIR__, 1) . '/resources/lang', 'swarovsky-core');

        // use this if your package has routes
        $this->setupRoutes($this->app->router);

        $this->loadMigrationsFrom(dirname(__DIR__, 1).'/migrations');

        // use this if your package needs a config file
        // $this->publishes([
        //         __DIR__.'/config/config.php' => config_path('skeleton.php'),
        // ]);

        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(
            dirname(__DIR__, 1).'/config/crud.php', 'core-crud'
        );
        $this->mergeConfigFrom(
            dirname(__DIR__, 1).'/config/google2fa.php', 'core-google2fa'
        );
        $this->mergeConfigFrom(
            dirname(__DIR__, 1).'/config/permission.php', 'core-permission'
        );
    }

    /**
     * Define the routes for the application.
     *
     * @param Router $router
     * @return void
     */
    public function setupRoutes(Router $router): void
    {
        $router->group([
            'namespace' => 'Swarovsky\Core\Http\Controllers',
            'middleware' => ['web', 'auth', 'verified']
        ], static function ($router) {
            require dirname(__DIR__, 1) . '/routes/web.php';
        });

        $router->group([
            'namespace' => 'Swarovsky\Core\Http\Controllers\Api',
            'middleware' => ['auth:api']
        ], static function ($router) {
            require dirname(__DIR__, 1) . '/routes/api.php';
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSkeleton();
        $this->app['router']->aliasMiddleware('2fa', Google2FAMiddleware::class);
        $this->app['router']->aliasMiddleware('advanced_permission', AdvancedPermissionMiddleware::class);
        $this->app['router']->aliasMiddleware('permission', PermissionMiddleware::class);
        $this->app['router']->aliasMiddleware('role', RoleMiddleware::class);
        $this->app['router']->aliasMiddleware('role_or_permission', RoleOrPermissionMiddleware::class);

        config([
            dirname(__DIR__, 1).'/config/crud.php',
            dirname(__DIR__, 1).'/config/google2fa.php',
            dirname(__DIR__, 1).'/config/permission.php',
        ]);

        $this->app->singleton('lb-uikit-3-forms', static function() {
            return new FormService();
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('Form', FormFacade::class);
    }

    private function registerSkeleton(): void
    {
        $this->app->bind('swarovsky-core', static function ($app) {
            return new CoreServiceProvider($app);
        });
    }

    public function provides(): array
    {
        return ['lb-uikit-3-forms'];
    }

}
