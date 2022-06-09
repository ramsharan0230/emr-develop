<?php

namespace Modules\Physiotherapy\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class PhysiotherapyServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('Physiotherapy', 'Database/Migrations'));
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
            module_path('Physiotherapy', 'Config/config.php') => config_path('physiotherapy.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Physiotherapy', 'Config/config.php'), 'physiotherapy'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/physiotherapy');

        $sourcePath = module_path('Physiotherapy', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/physiotherapy';
        }, \Config::get('view.paths')), [$sourcePath]), 'physiotherapy');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/physiotherapy');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'physiotherapy');
        } else {
            $this->loadTranslationsFrom(module_path('Physiotherapy', 'Resources/lang'), 'physiotherapy');
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
            app(Factory::class)->load(module_path('Physiotherapy', 'Database/factories'));
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
