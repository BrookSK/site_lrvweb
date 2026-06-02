<?php

/**
 * Sistema de Validação
 * 
 * Valida dados de entrada com regras configuráveis.
 * 
 * @package Core
 */

declare(strict_types=1);

namespace Core;

class Validator
{
    private array $data;
    private array $rules;
    private array $errors = [];
    private array $validData = [];

    private array $messages = [
        'required' => 'O campo :field é obrigatório.',
        'email' => 'O campo :field deve ser um e-mail válido.',
        'min' => 'O campo :field deve ter no mínimo :param caracteres.',
        'max' => 'O campo :field deve ter no máximo :param caracteres.',
        'numeric' => 'O campo :field deve ser numérico.',
        'integer' => 'O campo :field deve ser um número inteiro.',
        'url' => 'O campo :field deve ser uma URL válida.',
        'confirmed' => 'A confirmação do campo :field não confere.',
        'unique' => 'O valor do campo :field já está em uso.',
        'exists' => 'O valor do campo :field não foi encontrado.',
        'date' => 'O campo :field deve ser uma data válida.',
        'in' => 'O campo :field contém um valor inválido.',
        'file' => 'O campo :field deve ser um arquivo válido.',
        'image' => 'O campo :field deve ser uma imagem.',
        'max_size' => 'O campo :field excede o tamanho máximo permitido.',
        'cpf_cnpj' => 'O campo :field deve ser um CPF ou CNPJ válido.',
        'phone' => 'O campo :field deve ser um telefone válido.',
    ];

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    /**
     * Executa a validação
     */
    public function passes(): bool
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = is_array($ruleString) ? $ruleString : explode('|', $ruleString);
            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {
                $param = null;

                if (str_contains($rule, ':')) {
                    [$rule, $param] = explode(':', $rule, 2);
                }

                $method = 'validate' . ucfirst($rule);

                if (method_exists($this, $method)) {
                    if (!$this->$method($field, $value, $param)) {
                        $message = str_replace(
                            [':field', ':param'],
                            [$field, $param ?? ''],
                            $this->messages[$rule] ?? "O campo :field é inválido."
                        );
                        $this->errors[$field][] = $message;
                        break; // Para na primeira falha do campo
                    }
                }
            }

            if (!isset($this->errors[$field]) && $value !== null) {
                $this->validData[$field] = $value;
            }
        }

        return empty($this->errors);
    }

    /**
     * Retorna erros de validação
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Retorna dados validados
     */
    public function getValidData(): array
    {
        return $this->validData;
    }

    // === Regras de Validação ===

    private function validateRequired(string $field, mixed $value, ?string $param): bool
    {
        return $value !== null && $value !== '' && $value !== [];
    }

    private function validateEmail(string $field, mixed $value, ?string $param): bool
    {
        if (empty($value)) return true;
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function validateMin(string $field, mixed $value, ?string $param): bool
    {
        if (empty($value)) return true;
        return mb_strlen((string) $value) >= (int) $param;
    }

    private function validateMax(string $field, mixed $value, ?string $param): bool
    {
        if (empty($value)) return true;
        return mb_strlen((string) $value) <= (int) $param;
    }

    private function validateNumeric(string $field, mixed $value, ?string $param): bool
    {
        if (empty($value)) return true;
        return is_numeric($value);
    }

    private function validateInteger(string $field, mixed $value, ?string $param): bool
    {
        if (empty($value)) return true;
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    private function validateUrl(string $field, mixed $value, ?string $param): bool
    {
        if (empty($value)) return true;
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    private function validateConfirmed(string $field, mixed $value, ?string $param): bool
    {
        return $value === ($this->data["{$field}_confirmation"] ?? null);
    }

    private function validateDate(string $field, mixed $value, ?string $param): bool
    {
        if (empty($value)) return true;
        $date = \DateTime::createFromFormat($param ?? 'Y-m-d', $value);
        return $date !== false;
    }

    private function validateIn(string $field, mixed $value, ?string $param): bool
    {
        if (empty($value)) return true;
        $allowed = explode(',', $param ?? '');
        return in_array($value, $allowed);
    }

    private function validateUnique(string $field, mixed $value, ?string $param): bool
    {
        if (empty($value)) return true;
        // param formato: table,column,except_id
        $parts = explode(',', $param ?? '');
        $table = $parts[0] ?? '';
        $column = $parts[1] ?? $field;
        $exceptId = $parts[2] ?? null;

        $sql = "SELECT COUNT(*) as total FROM {$table} WHERE {$column} = :value";
        $params = ['value' => $value];

        if ($exceptId) {
            $sql .= " AND id != :except_id";
            $params['except_id'] = $exceptId;
        }

        $result = Database::getInstance()->fetchOne($sql, $params);
        return (int) ($result['total'] ?? 0) === 0;
    }

    private function validatePhone(string $field, mixed $value, ?string $param): bool
    {
        if (empty($value)) return true;
        $cleaned = preg_replace('/[^0-9]/', '', $value);
        return strlen($cleaned) >= 10 && strlen($cleaned) <= 15;
    }
}
