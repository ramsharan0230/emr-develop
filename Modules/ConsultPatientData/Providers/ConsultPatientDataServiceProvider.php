<?php

namespace Modules\ConsultPatientData\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ConsultPatientDataServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('ConsultPatientData', 'Database/Migrations'));
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
            module_path('ConsultPatientData', 'Config/config.php') => config_path('consultpatientdata.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('ConsultPatientData', 'Config/config.php'), 'consultpatientdata'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/consultpatientdata');

        $sourcePath = module_path('ConsultPatientData', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/consultpatientdata';
        }, \Config::get('view.paths')), [$sourcePath]), 'consultpatientdata');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/consultpatientdata');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'consultpatientdata');
        } else {
            $this->loadTranslationsFrom(module_path('ConsultPatientData', 'Resources/lang'), 'consultpatientdata');
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
            app(Factory::class)->load(module_path('ConsultPatientData', 'Database/factories'));
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
