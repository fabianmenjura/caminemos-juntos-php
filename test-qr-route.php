<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simular la petición
$requestUri = '/caminemos-juntos-php/api/admin/qr-donaciones';
$scriptName = '/caminemos-juntos-php/api/index.php';

$path = str_replace(dirname($scriptName), '', $requestUri);
$path = parse_url($path, PHP_URL_PATH);
$path = trim($path, '/');

$segments = explode('/', $path);

echo "URI: $requestUri\n";
echo "Script: $scriptName\n";
echo "Path: $path\n";
echo "Segments: " . print_r($segments, true) . "\n";

echo "\nChecks:\n";
echo "segments[0] = '{$segments[0]}'\n";
echo "segments[1] = '" . ($segments[1] ?? 'NOT SET') . "'\n";
echo "Is admin? " . ($segments[0] === 'admin' ? 'YES' : 'NO') . "\n";
echo "Is qr-donaciones? " . (($segments[1] ?? '') === 'qr-donaciones' ? 'YES' : 'NO') . "\n";

echo "\nCondition result:\n";
if ($segments[0] === 'admin' && isset($segments[1]) && $segments[1] === 'qr-donaciones') {
    echo "✅ QR Route would be matched!\n";
} else {
    echo "❌ QR Route would NOT be matched\n";
}

