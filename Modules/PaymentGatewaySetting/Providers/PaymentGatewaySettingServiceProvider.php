<?php

namespace Modules\PaymentGatewaySetting\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class PaymentGatewaySettingServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('PaymentGatewaySetting', 'Database/Migrations'));
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
            module_path('PaymentGatewaySetting', 'Config/config.php') => config_path('paymentgatewaysetting.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('PaymentGatewaySetting', 'Config/config.php'), 'paymentgatewaysetting'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/paymentgatewaysetting');

        $sourcePath = module_path('PaymentGatewaySetting', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/paymentgatewaysetting';
        }, \Config::get('view.paths')), [$sourcePath]), 'paymentgatewaysetting');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/paymentgatewaysetting');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'paymentgatewaysetting');
        } else {
            $this->loadTranslationsFrom(module_path('PaymentGatewaySetting', 'Resources/lang'), 'paymentgatewaysetting');
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
            app(Factory::class)->load(module_path('PaymentGatewaySetting', 'Database/factories'));
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
