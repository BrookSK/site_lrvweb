<?php

/**
 * Sistema de Internacionalização (i18n)
 * 
 * Gerencia traduções e detecção de idioma.
 * 
 * @package Core
 */

declare(strict_types=1);

namespace Core;

class I18n
{
    private static ?string $locale = null;
    private static array $translations = [];
    private static array $availableLocales = [];

    /**
     * Retorna idiomas disponíveis (com lazy load da config)
     */
    public static function getAvailableLocales(): array
    {
        if (empty(self::$availableLocales)) {
            self::$availableLocales = Config::get('app.available_locales', ['pt', 'en', 'es']);
        }
        return self::$availableLocales;
    }

    /**
     * Define o idioma atual
     */
    public static function setLocale(string $locale): void
    {
        if (in_array($locale, self::getAvailableLocales())) {
            self::$locale = $locale;
        }
    }

    /**
     * Retorna o idioma atual
     */
    public static function getLocale(): string
    {
        if (self::$locale) {
            return self::$locale;
        }

        // Detecta pelo URL
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $segments = explode('/', trim($uri, '/'));

        if (!empty($segments[0]) && in_array($segments[0], self::getAvailableLocales())) {
            self::$locale = $segments[0];
            return self::$locale;
        }

        // Fallback para o padrão
        self::$locale = Config::get('app.locale', 'pt');
        return self::$locale;
    }

    /**
     * Traduz uma chave
     */
    public static function get(string $key, array $replace = [], ?string $locale = null): string
    {
        $locale = $locale ?? self::getLocale();
        self::loadTranslations($locale);

        $translation = self::$translations[$locale][$key] ?? $key;

        // Substituição de variáveis
        foreach ($replace as $search => $value) {
            $translation = str_replace(":{$search}", (string) $value, $translation);
        }

        return $translation;
    }

    /**
     * Carrega traduções do banco ou arquivos
     */
    private static function loadTranslations(string $locale): void
    {
        if (isset(self::$translations[$locale])) {
            return;
        }

        self::$translations[$locale] = [];

        // Primeiro tenta carregar do arquivo (mais rápido)
        $file = ROOT_PATH . "/resources/lang/{$locale}.php";
        if (file_exists($file)) {
            self::$translations[$locale] = require $file;
            return;
        }

        // Fallback: carrega do banco de dados
        try {
            $db = Database::getInstance();
            $rows = $db->fetchAll(
                "SELECT `key`, value FROM translations WHERE language_code = :locale",
                ['locale' => $locale]
            );

            foreach ($rows as $row) {
                self::$translations[$locale][$row['key']] = $row['value'];
            }
        } catch (\Throwable $e) {
            // Silenciosamente falha se o banco não estiver disponível
        }
    }

    /**
     * Retorna URL com prefixo de idioma
     */
    public static function url(string $path): string
    {
        $locale = self::getLocale();
        $baseUrl = Config::get('app.url', '');
        return rtrim($baseUrl, '/') . '/' . $locale . '/' . ltrim($path, '/');
    }
}
