<?php

namespace Modules\ConsultDiagnostic\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ConsultDiagnosticServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('ConsultDiagnostic', 'Database/Migrations'));
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
            module_path('ConsultDiagnostic', 'Config/config.php') => config_path('consultdiagnostic.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('ConsultDiagnostic', 'Config/config.php'), 'consultdiagnostic'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/consultdiagnostic');

        $sourcePath = module_path('ConsultDiagnostic', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/consultdiagnostic';
        }, \Config::get('view.paths')), [$sourcePath]), 'consultdiagnostic');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/consultdiagnostic');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'consultdiagnostic');
        } else {
            $this->loadTranslationsFrom(module_path('ConsultDiagnostic', 'Resources/lang'), 'consultdiagnostic');
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
            app(Factory::class)->load(module_path('ConsultDiagnostic', 'Database/factories'));
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
