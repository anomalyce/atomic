<?php

namespace Anomalyce\Atomic\Adapter;

use Anomalyce\Atomic\Adapter\Atomic;
use Anomalyce\Atomic\Adapter\Theme\Generator;
use Illuminate\Support\AggregateServiceProvider;

class AdapterServiceProvider extends AggregateServiceProvider
{
  /**
   * The provider class names.
   *
   * @var array
   */
  protected $providers = [
    //
  ];

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    parent::register();

    $this->app->singleton('atomic', function ($app) {
      return new Atomic($app);
    });

    $this->app->singleton('atomic.theme.generator', function ($app) {
      return new Generator;
    });
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
