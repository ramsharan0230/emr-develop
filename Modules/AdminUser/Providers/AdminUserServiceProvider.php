<?php

namespace Modules\AdminUser\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\AdminUser\Services\Contracts\AdminUserContract;
use Modules\AdminUser\Services\Repositories\AdminUserService;

class AdminUserServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('AdminUser', 'Database/Migrations'));
        $this->serviceBind();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('AdminUser', 'Config/config.php') => config_path('adminuser.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('AdminUser', 'Config/config.php'), 'adminuser'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/adminuser');

        $sourcePath = module_path('AdminUser', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/adminuser';
        }, \Config::get('view.paths')), [$sourcePath]), 'adminuser');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/adminuser');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'adminuser');
        } else {
            $this->loadTranslationsFrom(module_path('AdminUser', 'Resources/lang'), 'adminuser');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('AdminUser', 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
    public function serviceBind()
    {
        $this->app->bind(AdminUserContract::class, AdminUserService::class);
    }
}
