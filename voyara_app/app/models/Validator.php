<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
class Validator {
    private array $errors = [];
    private array $data   = [];

    public function __construct(array $input) {
        
        foreach ($input as $k => $v) {
            $this->data[$k] = is_string($v) ? trim($v) : $v;
        }
    }

    public function required(string $field, string $label = ''): self {
        $label = $label ?: ucfirst($field);
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            $this->errors[$field] = "{$label} is required.";
        }
        return $this;
    }

    public function email(string $field, string $label = ''): self {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && $this->data[$field] !== '') {
            if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field] = "{$label} must be a valid email address.";
            }
        }
        return $this;
    }

    public function minLength(string $field, int $min, string $label = ''): self {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $min) {
            $this->errors[$field] = "{$label} must be at least {$min} characters.";
        }
        return $this;
    }

    public function maxLength(string $field, int $max, string $label = ''): self {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $max) {
            $this->errors[$field] = "{$label} must not exceed {$max} characters.";
        }
        return $this;
    }

    public function numeric(string $field, string $label = ''): self {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && $this->data[$field] !== '') {
            if (!is_numeric($this->data[$field])) {
                $this->errors[$field] = "{$label} must be a number.";
            }
        }
        return $this;
    }

    public function min(string $field, float $min, string $label = ''): self {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && is_numeric($this->data[$field])) {
            if ((float)$this->data[$field] < $min) {
                $this->errors[$field] = "{$label} must be at least {$min}.";
            }
        }
        return $this;
    }

    public function date(string $field, string $label = ''): self {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && $this->data[$field] !== '') {
            $d = DateTime::createFromFormat('Y-m-d', $this->data[$field]);
            if (!$d || $d->format('Y-m-d') !== $this->data[$field]) {
                $this->errors[$field] = "{$label} must be a valid date (YYYY-MM-DD).";
            }
        }
        return $this;
    }

    public function future(string $field, string $label = ''): self {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && $this->data[$field] !== '') {
            if (strtotime($this->data[$field]) <= strtotime('today')) {
                $this->errors[$field] = "{$label} must be a future date.";
            }
        }
        return $this;
    }

    public function in(string $field, array $allowed, string $label = ''): self {
        $label = $label ?: ucfirst($field);
        if (isset($this->data[$field]) && !in_array($this->data[$field], $allowed, true)) {
            $this->errors[$field] = "{$label} contains an invalid value.";
        }
        return $this;
    }

    public function passes(): bool { return empty($this->errors); }
    public function fails(): bool  { return !empty($this->errors); }
    public function errors(): array { return $this->errors; }

    public function get(string $field, mixed $default = null): mixed {
        return $this->data[$field] ?? $default;
    }

    public function all(): array { return $this->data; }
}
