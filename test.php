<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test de Configuración</h1>";

// Test 1: Archivo .env existe
echo "<h2>1. Verificar .env</h2>";
$envPath = __DIR__ . '/api/.env';
if (file_exists($envPath)) {
    echo "✅ .env existe en: $envPath<br>";
    echo "<pre>" . file_get_contents($envPath) . "</pre>";
} else {
    echo "❌ .env NO existe en: $envPath<br>";
}

// Test 2: Cargar config
echo "<h2>2. Cargar Config</h2>";
try {
    require_once __DIR__ . '/api/config/config.php';
    echo "✅ Config cargado<br>";
    echo "DB_HOST: " . DB_HOST . "<br>";
    echo "DB_NAME: " . DB_NAME . "<br>";
    echo "APP_URL: " . APP_URL . "<br>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Test 3: Conectar a MySQL
echo "<h2>3. Conexión MySQL</h2>";
try {
    require_once __DIR__ . '/api/config/database.php';
    $db = Database::getInstance();
    echo "✅ Conectado a MySQL<br>";
    
    $abuelos = $db->fetchAll("SELECT nombre, ciudad FROM abuelos LIMIT 3");
    echo "✅ Abuelos encontrados: " . count($abuelos) . "<br>";
    foreach ($abuelos as $abuelo) {
        echo "  - {$abuelo['nombre']} ({$abuelo['ciudad']})<br>";
    }
} catch (Exception $e) {
    echo "❌ Error MySQL: " . $e->getMessage() . "<br>";
}

echo "<h2>4. Conclusión</h2>";
echo "<a href='/caminemos-juntos-php/api/health'>Probar API Health</a>";
?>
