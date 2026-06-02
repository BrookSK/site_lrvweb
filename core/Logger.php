<?php

/**
 * Sistema de Logs
 * 
 * Gerencia logs da aplicação com diferentes níveis.
 * 
 * @package Core
 */

declare(strict_types=1);

namespace Core;

class Logger
{
    private static string $logPath = ROOT_PATH . '/logs/';

    /**
     * Log de informação
     */
    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    /**
     * Log de aviso
     */
    public static function warning(string $message, array $context = []): void
    {
        self::log('WARNING', $message, $context);
    }

    /**
     * Log de erro
     */
    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }

    /**
     * Log crítico
     */
    public static function critical(string $message, array $context = []): void
    {
        self::log('CRITICAL', $message, $context);
    }

    /**
     * Log de debug
     */
    public static function debug(string $message, array $context = []): void
    {
        if (APP_DEBUG) {
            self::log('DEBUG', $message, $context);
        }
    }

    /**
     * Log de auditoria (ações de usuários)
     */
    public static function audit(string $action, array $context = []): void
    {
        $context['ip'] = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $context['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $context['user_id'] = $_SESSION['user']['id'] ?? null;

        self::log('AUDIT', $action, $context, 'audit');
    }

    /**
     * Escreve o log no arquivo
     */
    private static function log(string $level, string $message, array $context = [], string $channel = 'app'): void
    {
        $logChannel = Config::get('log.channel', 'daily');

        if ($logChannel === 'daily') {
            $filename = $channel . '-' . date('Y-m-d') . '.log';
        } else {
            $filename = $channel . '.log';
        }

        $filepath = self::$logPath . $filename;

        // Cria diretório se não existir
        if (!is_dir(self::$logPath)) {
            mkdir(self::$logPath, 0755, true);
        }

        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $entry = "[{$timestamp}] [{$level}] {$message}{$contextStr}" . PHP_EOL;

        file_put_contents($filepath, $entry, FILE_APPEND | LOCK_EX);
    }
}
