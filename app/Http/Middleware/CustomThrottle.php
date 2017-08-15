<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests;

class CustomThrottle extends ThrottleRequests
{

    /**
      * @override buildResponse()
      * redirect to last page with input and tooManyAttempts error message
      * flashes $retryAfter to session
      *
      * @param  string  $key
      * @param  int  $maxAttempts
      * @return Illuminate\Http\Response
      */
    protected function buildResponse($key, $maxAttempts)
    {
      $retryAfter = $this->limiter->availableIn($key);

      return back()->withInput()
                   ->withErrors(['tooManyAttempts' => 'errors.throttle'])
                   ->with('retryAfter', $retryAfter);
    }
}
