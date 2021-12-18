<?php

namespace Anomalyce\Atomic\Adapter\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

abstract class ThemeServiceProvider extends ServiceProvider
{
  /**
   * Define the theme name.
   * 
   * @return string
   */
  abstract public function name(): string;

  /**
   * Define the theme's absolute directory path.
   * 
   * @return string
   */
  abstract public function package_path(): string;

  /**
   * Define the public directory relative to the theme.
   * 
   * @return string
   */
  abstract public function public_path(): string;

  /**
   * Define a list of scripts to include from the theme.
   * 
   * @return array
   */
  abstract public function scripts(): array;

  /**
   * Define a list of stylesheets to include from the theme.
   * 
   * @return array
   */
  abstract public function stylesheets(): array;

  /**
   * Define a list of view paths to include.
   * 
   * @return array
   */
  abstract public function views(): array;

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    $config = $this->app['config'];

    $config->set('atomic.theme.name', $this->name());
    $config->set('atomic.theme.package', $this->package_path());
    $config->set('atomic.theme.public', $this->public_path());
    $config->set('atomic.theme.js', $this->scripts());
    $config->set('atomic.theme.css', $this->stylesheets());
    $config->set('atomic.theme.views', $this->views());

    $config->set('atomic.theme.manifest', $this->name());
    $config->set('atomic.theme.assets', url($this->name()));

    $this->app['atomic']->packageResources(
      public_path($this->name()), $this->package_path().'/'.$this->public_path()
    );
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    if ($this->app->resolved('blade.compiler')) {
      $this->registerDirective($this->app['blade.compiler']);
    } else {
      $this->app->afterResolving('blade.compiler', function (BladeCompiler $compiler) {
        $this->registerDirective($compiler);
      });
    }
  }

  /**
   * Register the Atomic blade directive.
   * 
   * @param  \Illuminate\View\Compilers\BladeCompiler  $compiler
   * @return void
   */
  protected function registerDirective(BladeCompiler $compiler)
  {
    $compiler->directive('atomic', function () {
      return "<?php echo app('atomic.theme.generator')->generateHtml(); ?>";
    });
  }
}
