<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class HandleApiErrors
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (ValidationException $e) {
            return response()->json([
                'STATE' => 'INVALID_DATA',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'STATE' => 'NOT_FOUND',
                'message' => 'Resource not found'
            ], 404);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                'STATE' => 'NOT_FOUND',
                'message' => 'Endpoint not found'
            ], 404);
        } catch (UnauthorizedHttpException $e) {
            return response()->json([
                'STATE' => 'UNAUTHORIZED',
                'message' => 'Unauthorized access'
            ], 401);
        } catch (\Exception $e) {
            \Log::error('API Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'STATE' => 'ERROR',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}