<?php

namespace Modules\OperationTheatre\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class OperationTheatreServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('OperationTheatre', 'Database/Migrations'));
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
            module_path('OperationTheatre', 'Config/config.php') => config_path('operationtheatre.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('OperationTheatre', 'Config/config.php'), 'operationtheatre'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/operationtheatre');

        $sourcePath = module_path('OperationTheatre', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/operationtheatre';
        }, \Config::get('view.paths')), [$sourcePath]), 'operationtheatre');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/operationtheatre');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'operationtheatre');
        } else {
            $this->loadTranslationsFrom(module_path('OperationTheatre', 'Resources/lang'), 'operationtheatre');
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
            app(Factory::class)->load(module_path('OperationTheatre', 'Database/factories'));
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
