<?php

use Inertia\Inertia;

if (! function_exists('atomic'))
{
  /**
   * Render a specific Atomic component template.
   * 
   * @param  string  $component
   * @param  string  $template
   * @param  \Illuminate\Http\Resources\Json\JsonResource|array  $data  []
   * @return \Inertia\ResponseFactory|\Inertia\Response
   */
  function atomic(string $component, string $template, JsonResource|array $data = [])
  {
    $request = request();

    if ($data instanceof JsonResource) {
      $data = array_merge($data->resolve($request), $data->additional);
    }

    Inertia::setRootView('core::inertia');

    return Inertia::render(compact('component', 'template'), $data);
  }
}
