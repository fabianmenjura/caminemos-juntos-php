<?php
class Validator {
    private $errors = [];
    private $data = [];

    public function __construct($data) {
        $this->data = $data;
    }

    public function required($field, $message = null) {
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
            $this->errors[$field][] = $message ?? "El campo $field es requerido";
        }
        return $this;
    }

    public function email($field, $message = null) {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = $message ?? "El campo $field debe ser un email válido";
        }
        return $this;
    }

    public function min($field, $min, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $min) {
            $this->errors[$field][] = $message ?? "El campo $field debe tener al menos $min caracteres";
        }
        return $this;
    }

    public function max($field, $max, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $max) {
            $this->errors[$field][] = $message ?? "El campo $field no puede tener más de $max caracteres";
        }
        return $this;
    }

    public function numeric($field, $message = null) {
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field][] = $message ?? "El campo $field debe ser numérico";
        }
        return $this;
    }

    public function minValue($field, $min, $message = null) {
        if (isset($this->data[$field]) && (float)$this->data[$field] < $min) {
            $this->errors[$field][] = $message ?? "El campo $field debe ser mayor o igual a $min";
        }
        return $this;
    }

    public function inArray($field, $array, $message = null) {
        if (isset($this->data[$field]) && !in_array($this->data[$field], $array)) {
            $this->errors[$field][] = $message ?? "El campo $field tiene un valor inválido";
        }
        return $this;
    }

    public function fails() {
        return !empty($this->errors);
    }

    public function passes() {
        return empty($this->errors);
    }

    public function errors() {
        return $this->errors;
    }
}
?>
