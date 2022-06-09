<?php

namespace Modules\ExtraReceipt\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ExtraReceiptServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('ExtraReceipt', 'Database/Migrations'));
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
            module_path('ExtraReceipt', 'Config/config.php') => config_path('extrareceipt.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('ExtraReceipt', 'Config/config.php'), 'extrareceipt'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/extrareceipt');

        $sourcePath = module_path('ExtraReceipt', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/extrareceipt';
        }, \Config::get('view.paths')), [$sourcePath]), 'extrareceipt');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/extrareceipt');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'extrareceipt');
        } else {
            $this->loadTranslationsFrom(module_path('ExtraReceipt', 'Resources/lang'), 'extrareceipt');
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
            app(Factory::class)->load(module_path('ExtraReceipt', 'Database/factories'));
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
