<?php
// Script de prueba para el endpoint de QR Donaciones

// Simular una petición GET a /api/admin/qr-donaciones
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/caminemos-juntos-php/api/admin/qr-donaciones';
$_SERVER['SCRIPT_NAME'] = '/caminemos-juntos-php/api/index.php';

// Token de prueba (obtenerlo del localStorage del navegador)
// Abre la consola del navegador y ejecuta: console.log(localStorage.getItem('admin_token'))
$token = '875faaddc2a1cc8c685cb7c29ad56142c929a42f4acac8d7c55c51f4af3d4e4e'; // Reemplazar con tu token real

// Simular headers
$_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;

// Deshabilitar output buffering para ver errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "=== TEST QR ENDPOINT ===\n";
echo "Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Token: " . substr($token, 0, 10) . "...\n\n";

// Incluir el router
ob_start();
include __DIR__ . '/api/index.php';
$output = ob_get_clean();

echo "=== OUTPUT ===\n";
echo $output . "\n";

