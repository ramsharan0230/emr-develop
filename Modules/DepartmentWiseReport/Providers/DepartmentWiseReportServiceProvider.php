<?php

namespace Modules\DepartmentWiseReport\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class DepartmentWiseReportServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('DepartmentWiseReport', 'Database/Migrations'));
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
            module_path('DepartmentWiseReport', 'Config/config.php') => config_path('departmentwisereport.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('DepartmentWiseReport', 'Config/config.php'), 'departmentwisereport'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/departmentwisereport');

        $sourcePath = module_path('DepartmentWiseReport', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/departmentwisereport';
        }, \Config::get('view.paths')), [$sourcePath]), 'departmentwisereport');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/departmentwisereport');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'departmentwisereport');
        } else {
            $this->loadTranslationsFrom(module_path('DepartmentWiseReport', 'Resources/lang'), 'departmentwisereport');
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
            app(Factory::class)->load(module_path('DepartmentWiseReport', 'Database/factories'));
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
}
