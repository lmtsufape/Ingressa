<?php

namespace App\Providers;

use App\Strategies\Listagens\ConvocacaoStrategy;
use App\Strategies\Listagens\PendenciaStrategy;
use App\Strategies\Listagens\ResultadoStrategy;
use App\Strategies\Listagens\StrategyResolver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->tag([
            ConvocacaoStrategy::class,
            PendenciaStrategy::class,
            ResultadoStrategy::class,
            // FinalStrategy::class,
        ], 'listagem.strategies');

        $this->app->when(StrategyResolver::class)
            ->needs('$strategies')
            ->giveTagged('listagem.strategies');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
