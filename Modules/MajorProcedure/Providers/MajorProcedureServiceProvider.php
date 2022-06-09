<?php

namespace Modules\MajorProcedure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class MajorProcedureServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('MajorProcedure', 'Database/Migrations'));
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
            module_path('MajorProcedure', 'Config/config.php') => config_path('majorprocedure.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('MajorProcedure', 'Config/config.php'), 'majorprocedure'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/majorprocedure');

        $sourcePath = module_path('MajorProcedure', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/majorprocedure';
        }, \Config::get('view.paths')), [$sourcePath]), 'majorprocedure');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/majorprocedure');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'majorprocedure');
        } else {
            $this->loadTranslationsFrom(module_path('MajorProcedure', 'Resources/lang'), 'majorprocedure');
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
            app(Factory::class)->load(module_path('MajorProcedure', 'Database/factories'));
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
