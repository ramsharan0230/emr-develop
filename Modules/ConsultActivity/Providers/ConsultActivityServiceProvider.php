<?php

namespace Modules\ConsultActivity\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ConsultActivityServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('ConsultActivity', 'Database/Migrations'));
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
            module_path('ConsultActivity', 'Config/config.php') => config_path('consultactivity.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('ConsultActivity', 'Config/config.php'), 'consultactivity'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/consultactivity');

        $sourcePath = module_path('ConsultActivity', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/consultactivity';
        }, \Config::get('view.paths')), [$sourcePath]), 'consultactivity');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/consultactivity');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'consultactivity');
        } else {
            $this->loadTranslationsFrom(module_path('ConsultActivity', 'Resources/lang'), 'consultactivity');
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
            app(Factory::class)->load(module_path('ConsultActivity', 'Database/factories'));
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
