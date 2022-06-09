<?php

namespace Modules\Bedoccupancy\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class BedoccupancyServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('Bedoccupancy', 'Database/Migrations'));
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
            module_path('Bedoccupancy', 'Config/config.php') => config_path('bedoccupancy.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Bedoccupancy', 'Config/config.php'), 'bedoccupancy'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/bedoccupancy');

        $sourcePath = module_path('Bedoccupancy', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/bedoccupancy';
        }, \Config::get('view.paths')), [$sourcePath]), 'bedoccupancy');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/bedoccupancy');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'bedoccupancy');
        } else {
            $this->loadTranslationsFrom(module_path('Bedoccupancy', 'Resources/lang'), 'bedoccupancy');
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
            app(Factory::class)->load(module_path('Bedoccupancy', 'Database/factories'));
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
