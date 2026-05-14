<?php

declare(strict_types=1);

namespace App\Middleware;

/**
 * Validates input data against a set of rules.
 * SRP: Only validates — does not sanitize, route, or render.
 */
class Validator
{
    private array $errors = [];

    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleString) {
            $fieldRules = explode('|', $ruleString);
            foreach ($fieldRules as $rule) {
                $this->applyRule($field, $data[$field] ?? null, $rule);
            }
        }

        return empty($this->errors);
    }

    private function applyRule(string $field, mixed $value, string $rule): void
    {
        [$ruleName, $param] = array_pad(explode(':', $rule, 2), 2, null);

        match ($ruleName) {
            'required' => $this->checkRequired($field, $value),
            'min'      => $this->checkMin($field, $value, (int)$param),
            'max'      => $this->checkMax($field, $value, (int)$param),
            'numeric'  => $this->checkNumeric($field, $value),
            'in'       => $this->checkIn($field, $value, explode(',', $param ?? '')),
            default    => null,
        };
    }

    private function checkRequired(string $field, mixed $value): void
    {
        if ($value === null || $value === '') {
            $this->errors[$field][] = ucfirst($field) . ' is required.';
        }
    }

    private function checkNumeric(string $field, mixed $value): void
    {
        if ($value !== null && $value !== '' && !is_numeric($value)) {
            $this->errors[$field][] = ucfirst($field) . ' must be a number.';
        }
    }

    private function checkMin(string $field, mixed $value, int $min): void
    {
        if (is_numeric($value) && (int)$value < $min) {
            $this->errors[$field][] = ucfirst($field) . " must be at least {$min}.";
        }
    }

    private function checkMax(string $field, mixed $value, int $max): void
    {
        if (is_numeric($value) && (int)$value > $max) {
            $this->errors[$field][] = ucfirst($field) . " must not exceed {$max}.";
        }
    }

    private function checkIn(string $field, mixed $value, array $allowed): void
    {
        if ($value !== null && $value !== '' && !in_array($value, $allowed, true)) {
            $list = implode(', ', $allowed);
            $this->errors[$field][] = ucfirst($field) . " must be one of: {$list}.";
        }
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
