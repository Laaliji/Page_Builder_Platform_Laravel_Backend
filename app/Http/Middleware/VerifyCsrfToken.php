<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'auth/*',
        'sanctum/csrf-cookie'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the request method is not GET, HEAD, OPTIONS, or TRACE
        // and if the request doesn't match any of the excluded URIs
        if ($this->isReading($request) || $this->tokensMatch($request) || $this->inExceptArray($request)) {
            return $next($request);
        }

        throw new TokenMismatchException;
    }

    /**
     * Determine if the request has a valid CSRF token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function tokensMatch(Request $request)
    {
        $sessionToken = $request->session()->token();
        $inputToken = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

        return is_string($sessionToken) && is_string($inputToken) && hash_equals($sessionToken, $inputToken);
    }

    /**
     * Determine if the incoming request has a URI that should be excluded from CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray(Request $request)
    {
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the HTTP request is a "read" request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isReading(Request $request)
    {
        return in_array($request->method(), ['GET', 'HEAD', 'OPTIONS', 'TRACE']);
    }
}
