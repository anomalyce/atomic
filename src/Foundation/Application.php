<?php

namespace Anomalyce\Atomic\Adapter\Foundation;

use Anomalyce\Atomic\Adapter\Atomic;
use Illuminate\Foundation\Application as Laravel;

class Application extends Laravel
{
  /**
   * Get the path to the bootstrap directory.
   *
   * @param  string  $path Optionally, a path to append to the bootstrap path
   * @return string
   */
  public function bootstrapPath($path = '')
  {
    return Atomic::appPath('storage'.DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.$path);
  }
}
