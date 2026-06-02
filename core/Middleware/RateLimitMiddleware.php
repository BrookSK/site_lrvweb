<?php

/**
 * Middleware de Rate Limiting
 * 
 * Limita requisições por IP para prevenir abuso.
 * 
 * @package Core\Middleware
 */

declare(strict_types=1);

namespace Core\Middleware;

use Core\Request;
use Core\Config;

class RateLimitMiddleware
{
    /**
     * Verifica rate limiting
     */
    public static function handle(Request $request): void
    {
        $maxRequests = (int) Config::get('security.rate_limit_requests', 60);
        $window = (int) Config::get('security.rate_limit_window', 60);
        $ip = $request->getIp();

        $cacheDir = ROOT_PATH . '/cache/rate_limit/';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $file = $cacheDir . md5($ip) . '.json';

        $data = [];
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true) ?? [];
        }

        $now = time();

        // Remove requisições fora da janela
        $data = array_filter($data, fn($timestamp) => $timestamp > ($now - $window));

        if (count($data) >= $maxRequests) {
            http_response_code(429);
            header('Retry-After: ' . $window);
            echo json_encode([
                'error' => 'Muitas requisições. Tente novamente em breve.',
                'retry_after' => $window,
            ]);
            exit;
        }

        $data[] = $now;
        file_put_contents($file, json_encode($data), LOCK_EX);
    }
}
