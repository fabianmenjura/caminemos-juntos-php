<?php
// Cargar variables de entorno desde .env
function loadEnv($path = null) {
    if ($path === null) {
        $path = __DIR__ . '/../.env';
    }
    
    if (!file_exists($path)) {
        error_log(".env not found at: " . $path);
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }

        if (strpos($line, '=') === false) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        putenv("$name=$value");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

// Cargar variables de entorno
loadEnv();

// Configuración general
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
define('APP_DEBUG', $_ENV['APP_DEBUG'] ?? 'false');
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost');

// Configuración de base de datos
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'caminemos_juntos');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? '');

// Configuración de PayU
define('PAYU_MERCHANT_ID', $_ENV['PAYU_MERCHANT_ID'] ?? '508029');
define('PAYU_API_KEY', $_ENV['PAYU_API_KEY'] ?? '4Vj8eK4rloUd272L48hsrarnUA');
define('PAYU_API_LOGIN', $_ENV['PAYU_API_LOGIN'] ?? 'pRRXKOl8ikMmt9u');
define('PAYU_ACCOUNT_ID', $_ENV['PAYU_ACCOUNT_ID'] ?? '512321');
define('PAYU_TEST_MODE', $_ENV['PAYU_TEST_MODE'] ?? 'true');

// URLs de PayU
define('PAYU_RESPONSE_URL', $_ENV['PAYU_RESPONSE_URL'] ?? APP_URL . '/api/payu/response.php');
define('PAYU_CONFIRMATION_URL', $_ENV['PAYU_CONFIRMATION_URL'] ?? APP_URL . '/api/payu/confirmation.php');

// Configuración de CORS
define('ALLOWED_ORIGINS', [
    'http://localhost:8080',
    'http://localhost:3000',
    APP_URL
]);

// Timezone
date_default_timezone_set('America/Bogota');

// Error reporting
if (APP_DEBUG === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>
