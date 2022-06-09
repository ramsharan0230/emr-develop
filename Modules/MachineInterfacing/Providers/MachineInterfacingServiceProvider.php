<?php

namespace Modules\MachineInterfacing\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class MachineInterfacingServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('MachineInterfacing', 'Database/Migrations'));
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
            module_path('MachineInterfacing', 'Config/config.php') => config_path('machineinterfacing.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('MachineInterfacing', 'Config/config.php'), 'machineinterfacing'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/machineinterfacing');

        $sourcePath = module_path('MachineInterfacing', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/machineinterfacing';
        }, \Config::get('view.paths')), [$sourcePath]), 'machineinterfacing');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/machineinterfacing');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'machineinterfacing');
        } else {
            $this->loadTranslationsFrom(module_path('MachineInterfacing', 'Resources/lang'), 'machineinterfacing');
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
            app(Factory::class)->load(module_path('MachineInterfacing', 'Database/factories'));
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
