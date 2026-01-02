<?php
// Pasar el header Authorization a PHP antes de incluir el backend
// Múltiples métodos para asegurar compatibilidad
if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    // Ya está disponible
} elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
    $_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
} elseif (function_exists('getallheaders')) {
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $_SERVER['HTTP_AUTHORIZATION'] = $headers['Authorization'];
    } elseif (isset($headers['authorization'])) {
        $_SERVER['HTTP_AUTHORIZATION'] = $headers['authorization'];
    }
}

// Redirigir todas las peticiones al backend
require_once __DIR__ . '/../backend/index.php';
?>
