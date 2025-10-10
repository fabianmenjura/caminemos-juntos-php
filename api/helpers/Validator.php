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
    
    /**
     * Validación específica para abuelos
     */
    public static function validateAbuelo($data) {
        $errors = [];
        
        if (empty($data['nombre']) || strlen($data['nombre']) < 3) {
            $errors['nombre'] = 'El nombre debe tener al menos 3 caracteres';
        }
        
        if (empty($data['edad']) || !is_numeric($data['edad']) || $data['edad'] < 60 || $data['edad'] > 120) {
            $errors['edad'] = 'La edad debe estar entre 60 y 120 años';
        }
        
        if (empty($data['ciudad']) || strlen($data['ciudad']) < 3) {
            $errors['ciudad'] = 'La ciudad es requerida';
        }
        
        if (empty($data['descripcion']) || strlen($data['descripcion']) < 20) {
            $errors['descripcion'] = 'La descripción debe tener al menos 20 caracteres';
        }
        
        if (isset($data['genero']) && !in_array($data['genero'], ['M', 'F'])) {
            $errors['genero'] = 'El género debe ser M o F';
        }
        
        return $errors;
    }
}
?>
