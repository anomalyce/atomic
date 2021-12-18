<?php

namespace Anomalyce\Atomic\Adapter\Theme;

class Generator
{
  /**
   * Holds all of the registered options.
   * 
   * @var array
   */
  protected array $options = [];

  /**
   * Retrieve the master Atomic object.
   * 
   * @return array
   */
  public function getAtomicObject(): array
  {
    return [
      'config' => [],
    ];
  }

  /**
   * Register a set of options under a group name.
   * 
   * @param  string  $group
   * @param  array  $values
   * @return void
   */
  public function registerOptions(string $group, array $values): void
  {
    $this->options[$group] = $values;
  }

  /**
   * Retrieve all of the registered options.
   * 
   * @return array
   */
  public function getConfig(): array
  {
    return $this->options;
  }

  /**
   * Generate the HTML output.
   * 
   * @return string
   */
  public function generateHtml(): string
  {
    $atomic = json_encode($this->getAtomicObject());

    $config = json_encode($this->getConfig());

    return <<<HTML
<script type="text/javascript">
  window.Atomic = {$atomic};

  (function () {
    const config = {$config};

    Object.assign(window.Atomic.config, config);
  })();
</script>
HTML;
  }
}
