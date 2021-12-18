<?php

namespace Anomalyce\Atomic\Adapter\Console\Commands;

use Illuminate\Console\Command;

class LinksCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'atomic:links';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Generate all of the required Atomic links.';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $this->refreshPackageResourceLinks();

    return 0;
  }

  /**
   * Refresh the package resource links.
   *
   * @return void
   */
  protected function refreshPackageResourceLinks()
  {
    foreach ($this->laravel['config']->get('filesystems.links') as $link => $path) {
      if (! is_link($link)) {
        continue;
      }

      unlink($link);
      $this->comment("The [${link}] link has been removed.");
    }

    $this->call('storage:link');
  }
}
