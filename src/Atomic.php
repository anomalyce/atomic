<?php

namespace Anomalyce\Atomic\Adapter;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Foundation\Application;

class Atomic
{
  /**
   * Holds the Laravel application object.
   *
   * @var \Illuminate\Contracts\Foundation\Application
   */
  protected $laravel;

  /**
   * Holds all of the registered package resource paths.
   *
   * @var array
   */
  protected static $packageResources = [];

  /**
   * Holds the Atomic app directory path.
   *
   * @var string
   */
  protected static $appPath;

  /**
   * Holds the Laravel app directory path.
   *
   * @var string
   */
  protected static $laravelPath;

  /**
   * Instantiate a new Atomic instance.
   *
   * @param  \Illuminate\Contracts\Foundation\Application
   * @return void
   */
  public function __construct(Application $laravel)
  {
    $this->laravel = $laravel;
  }

  /**
   * Set the path to the Atomic app package.
   *
   * @param  string  $path
   * @return void
   */
  public static function setAppPath($path)
  {
    static::$appPath = $path;
  }

  /**
   * Get the path to the Atomic app package.
   *
   * @param  string  $path  null
   * @return string
   */
  public static function appPath($path = '')
  {
    return static::$appPath.($path ? DIRECTORY_SEPARATOR.$path : $path);
  }

  /**
   * Set the path to the Laravel app package.
   *
   * @param  string  $path
   * @return void
   */
  public static function setLaravelPath($path)
  {
    static::$laravelPath = $path;
  }

  /**
   * Get the path to the Laravel app package.
   *
   * @param  string  $path  null
   * @return string
   */
  public static function laravelPath($path = '')
  {
    return static::$laravelPath.($path ? DIRECTORY_SEPARATOR.$path : $path);
  }

  /**
   * Get the path to the Atomic core package.
   *
   * @param  string  $path  null
   * @return string
   */
  public static function corePath($path = '')
  {
    $basePath = __DIR__.DIRECTORY_SEPARATOR.'..';

    return $basePath.($path ? DIRECTORY_SEPARATOR.$path : $path);
  }

  /**
   * Setup a symlink to a specific package's public resources path.
   *
   * @param  string  $link
   * @param  string  $path
   * @return void
   */
  public function packageResources($link, $path)
  {
    $links = new Collection(
      $this->laravel['config']->get('filesystems.links')
    );

    $links->put($link, $path);

    $this->laravel['config']->set('filesystems.links', $links->all());

    static::$packageResources[$link] = $path;
  }

  /**
   * Retrieve all of the registered package resource paths.
   *
   * @return array
   */
  public static function allPackageResources()
  {
    return static::$packageResources;
  }
}
