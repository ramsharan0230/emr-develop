<?php

namespace Modules\IrdPurchaseReport\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class IrdPurchaseReportServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('IrdPurchaseReport', 'Database/Migrations'));
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
            module_path('IrdPurchaseReport', 'Config/config.php') => config_path('irdpurchasereport.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('IrdPurchaseReport', 'Config/config.php'), 'irdpurchasereport'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/irdpurchasereport');

        $sourcePath = module_path('IrdPurchaseReport', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/irdpurchasereport';
        }, \Config::get('view.paths')), [$sourcePath]), 'irdpurchasereport');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/irdpurchasereport');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'irdpurchasereport');
        } else {
            $this->loadTranslationsFrom(module_path('IrdPurchaseReport', 'Resources/lang'), 'irdpurchasereport');
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
            app(Factory::class)->load(module_path('IrdPurchaseReport', 'Database/factories'));
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
