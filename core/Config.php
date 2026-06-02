<?php

/**
 * Gerenciador de Configurações
 * 
 * Carrega e disponibiliza configurações do arquivo config/app.php.
 * Suporta notação pontilhada: Config::get('database.host')
 * 
 * As configurações do banco de dados (tabela settings) são
 * carregadas sob demanda para configurações editáveis pelo painel.
 * 
 * @package Core
 */

declare(strict_types=1);

namespace Core;

class Config
{
    private static array $config = [];
    private static array $dbSettings = [];
    private static bool $dbSettingsLoaded = false;

    /**
     * Carrega configuração do arquivo
     */
    public static function load(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \RuntimeException("Arquivo de configuração não encontrado: {$filePath}");
        }

        self::$config = require $filePath;
    }

    /**
     * Obtém valor de configuração usando notação pontilhada
     * 
     * Exemplo: Config::get('database.host') retorna $config['database']['host']
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        $value = self::$config;

        foreach ($keys as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * Define valor em tempo de execução
     */
    public static function set(string $key, mixed $value): void
    {
        $keys = explode('.', $key);
        $config = &self::$config;

        foreach ($keys as $i => $segment) {
            if ($i === count($keys) - 1) {
                $config[$segment] = $value;
            } else {
                if (!isset($config[$segment]) || !is_array($config[$segment])) {
                    $config[$segment] = [];
                }
                $config = &$config[$segment];
            }
        }
    }

    /**
     * Retorna toda a configuração
     */
    public static function all(): array
    {
        return self::$config;
    }

    /**
     * Obtém configuração editável pelo painel (tabela settings)
     */
    public static function setting(string $key, mixed $default = null): mixed
    {
        self::loadDbSettings();
        return self::$dbSettings[$key] ?? $default;
    }

    /**
     * Obtém todas as settings de um grupo
     */
    public static function settingsGroup(string $group): array
    {
        self::loadDbSettings();

        $result = [];
        foreach (self::$dbSettings as $key => $value) {
            if (str_starts_with($key, $group . '.')) {
                $shortKey = substr($key, strlen($group) + 1);
                $result[$shortKey] = $value;
            }
        }

        return $result;
    }

    /**
     * Atualiza uma setting no banco
     */
    public static function setSetting(string $group, string $key, mixed $value): void
    {
        try {
            $db = Database::getInstance();
            $existing = $db->fetchOne(
                "SELECT id FROM settings WHERE `group` = :group AND `key` = :key",
                ['group' => $group, 'key' => $key]
            );

            if ($existing) {
                $db->update('settings', ['value' => (string) $value], '`group` = :group AND `key` = :key', ['group' => $group, 'key' => $key]);
            } else {
                $db->insert('settings', ['group' => $group, 'key' => $key, 'value' => (string) $value, 'type' => 'text']);
            }

            // Atualiza cache local
            self::$dbSettings["{$group}.{$key}"] = $value;
        } catch (\Throwable $e) {
            Logger::error('Erro ao salvar setting', ['key' => $key, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Carrega settings do banco de dados
     */
    private static function loadDbSettings(): void
    {
        if (self::$dbSettingsLoaded) {
            return;
        }

        try {
            $db = Database::getInstance();
            $rows = $db->fetchAll("SELECT `group`, `key`, value FROM settings");

            foreach ($rows as $row) {
                self::$dbSettings["{$row['group']}.{$row['key']}"] = $row['value'];
            }

            self::$dbSettingsLoaded = true;
        } catch (\Throwable $e) {
            // Se o banco não estiver disponível ainda (instalação)
            self::$dbSettingsLoaded = true;
        }
    }

    /**
     * Força recarregamento das settings do banco
     */
    public static function refreshDbSettings(): void
    {
        self::$dbSettingsLoaded = false;
        self::$dbSettings = [];
        self::loadDbSettings();
    }

    /**
     * Salva configurações no arquivo config/app.php
     * Usado pelo painel de instalação/configuração
     */
    public static function saveToFile(array $newConfig): bool
    {
        $filePath = ROOT_PATH . '/config/app.php';

        $merged = array_replace_recursive(self::$config, $newConfig);

        $content = "<?php\n\n/**\n * Configuração Principal da Aplicação\n * Gerado automaticamente pelo sistema.\n */\n\nreturn " . self::varExport($merged) . ";\n";

        $result = file_put_contents($filePath, $content, LOCK_EX);

        if ($result !== false) {
            self::$config = $merged;
            return true;
        }

        return false;
    }

    /**
     * Exporta array de forma legível
     */
    private static function varExport(mixed $value, int $indent = 0): string
    {
        if (is_array($value)) {
            $pad = str_repeat('    ', $indent);
            $innerPad = str_repeat('    ', $indent + 1);

            if (empty($value)) {
                return '[]';
            }

            $isSequential = array_keys($value) === range(0, count($value) - 1);
            $items = [];

            foreach ($value as $k => $v) {
                $key = $isSequential ? '' : "'" . addslashes((string) $k) . "' => ";
                $items[] = $innerPad . $key . self::varExport($v, $indent + 1);
            }

            return "[\n" . implode(",\n", $items) . ",\n{$pad}]";
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_null($value)) {
            return 'null';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return "'" . addslashes((string) $value) . "'";
    }
}
