<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiKeyMiddleware
{
    /**
     * Заголовок API ключа.
     */
    private const HEADER_NAME = 'X-API-Key';

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $providedKey = $request->headers->get(self::HEADER_NAME);

        if ($providedKey === null || $providedKey === '') {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Требуется API-ключ.');
        }

        $originalKey = config('app.api_static_key');

        if (!$originalKey) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'API-ключ не настроен.');
        }

        if ($originalKey !== $providedKey) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Недействительный API-ключ.');
        }

        return $next($request);
    }
}
