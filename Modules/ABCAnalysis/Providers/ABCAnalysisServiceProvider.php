<?php

namespace Modules\ABCAnalysis\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ABCAnalysisServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('ABCAnalysis', 'Database/Migrations'));
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
            module_path('ABCAnalysis', 'Config/config.php') => config_path('abcanalysis.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('ABCAnalysis', 'Config/config.php'), 'abcanalysis'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/abcanalysis');

        $sourcePath = module_path('ABCAnalysis', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/abcanalysis';
        }, \Config::get('view.paths')), [$sourcePath]), 'abcanalysis');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/abcanalysis');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'abcanalysis');
        } else {
            $this->loadTranslationsFrom(module_path('ABCAnalysis', 'Resources/lang'), 'abcanalysis');
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
            app(Factory::class)->load(module_path('ABCAnalysis', 'Database/factories'));
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
