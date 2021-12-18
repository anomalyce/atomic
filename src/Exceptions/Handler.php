<?php

namespace Anomalyce\Atomic\Adapter\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
  /**
   * A list of the exception types that are not reported.
   *
   * @var array
   */
  protected $dontReport = [
    //
  ];

  /**
   * A list of the inputs that are never flashed for validation exceptions.
   *
   * @var array
   */
  protected $dontFlash = [
    'current_password',
    'password',
    'password_confirmation',
  ];

  /**
   * Register the exception handling callbacks for the application.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return void
   */
  public function register()
  {
    $this->renderable(function (Throwable $e, $request) {
      //
    });

    $this->reportable(function (Throwable $e) {
      //
    });
  }

  /**
   * Render an exception into an HTTP response.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Throwable  $e
   * @return \Symfony\Component\HttpFoundation\Response
   *
   * @throws \Throwable
   */
  public function render($request, Throwable $e)
  {
    $response = parent::render($request, $e);

    if ($this->container->environment('local', 'testing') and config('app.debug')) {
      return $response;
    }

    if (in_array($response->status(), [ 500, 503, 404, 403 ])) {
      return atomic('Core', 'Error', [ 'status' => $response->status() ])
        ->toResponse($request)
        ->setStatusCode($response->status());
    } else if ($response->status() === 419) {
      return back()->withNotification('errors.419.message');
    }

    return $response;
  }
}
