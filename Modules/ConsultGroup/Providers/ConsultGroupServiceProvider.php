<?php

namespace Modules\ConsultGroup\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ConsultGroupServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('ConsultGroup', 'Database/Migrations'));
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
            module_path('ConsultGroup', 'Config/config.php') => config_path('consultgroup.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('ConsultGroup', 'Config/config.php'), 'consultgroup'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/consultgroup');

        $sourcePath = module_path('ConsultGroup', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/consultgroup';
        }, \Config::get('view.paths')), [$sourcePath]), 'consultgroup');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/consultgroup');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'consultgroup');
        } else {
            $this->loadTranslationsFrom(module_path('ConsultGroup', 'Resources/lang'), 'consultgroup');
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
            app(Factory::class)->load(module_path('ConsultGroup', 'Database/factories'));
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
