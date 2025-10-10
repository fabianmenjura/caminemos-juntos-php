<?php
/**
 * GitHub Webhook para Despliegue Automático
 * 
 * Este script recibe notificaciones de GitHub cuando haces push
 * y automáticamente despliega los cambios en el servidor.
 * 
 * Configuración:
 * 1. Sube este archivo a: ~/public_html/webhook.php
 * 2. Genera un token secreto: openssl rand -hex 32
 * 3. Configura el webhook en GitHub con ese token
 * 
 * URL del webhook: https://tudominio.com/webhook.php
 */

// ============================================
// CONFIGURACIÓN
// ============================================

// Token secreto - ¡CÁMBIALO! Genera uno con: openssl rand -hex 32
define('WEBHOOK_SECRET', 'CAMBIA_ESTE_TOKEN_POR_UNO_ALEATORIO_SEGURO');

// Rama que activa el despliegue
define('DEPLOY_BRANCH', 'refs/heads/production');

// Ruta al script de despliegue
define('DEPLOY_SCRIPT', __DIR__ . '/deploy.sh');

// Archivo de log
define('LOG_FILE', '/tmp/webhook_deploy.log');

// ============================================
// FUNCIONES
// ============================================

function logMessage($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message\n";
    file_put_contents(LOG_FILE, $logEntry, FILE_APPEND);
}

function sendResponse($status, $message, $data = []) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => $status === 200 ? 'success' : 'error',
        'message' => $message,
        'data' => $data,
        'timestamp' => date('c')
    ]);
    exit;
}

// ============================================
// VALIDACIÓN
// ============================================

// Verificar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    logMessage("ERROR: Método no permitido - " . $_SERVER['REQUEST_METHOD']);
    sendResponse(405, 'Method not allowed');
}

// Obtener headers
$headers = getallheaders();
$signature = $headers['X-Hub-Signature-256'] ?? '';
$event = $headers['X-GitHub-Event'] ?? '';

// Log del evento recibido
logMessage("Webhook recibido - Evento: $event");

// Verificar que hay una firma
if (empty($signature)) {
    logMessage("ERROR: Falta firma de GitHub");
    sendResponse(403, 'Missing signature');
}

// Obtener payload
$payload = file_get_contents('php://input');

if (empty($payload)) {
    logMessage("ERROR: Payload vacío");
    sendResponse(400, 'Empty payload');
}

// Verificar firma de GitHub
$hash = 'sha256=' . hash_hmac('sha256', $payload, WEBHOOK_SECRET);

if (!hash_equals($hash, $signature)) {
    logMessage("ERROR: Firma inválida");
    sendResponse(403, 'Invalid signature');
}

logMessage("✅ Firma verificada correctamente");

// ============================================
// PROCESAMIENTO
// ============================================

// Decodificar payload
$data = json_decode($payload, true);

if (!$data) {
    logMessage("ERROR: No se pudo decodificar el payload JSON");
    sendResponse(400, 'Invalid JSON payload');
}

// Verificar que es un evento push
if ($event !== 'push') {
    logMessage("INFO: Evento ignorado - $event");
    sendResponse(200, 'Event ignored (not a push event)', [
        'event' => $event
    ]);
}

// Verificar rama
$ref = $data['ref'] ?? '';

if ($ref !== DEPLOY_BRANCH) {
    logMessage("INFO: Push a rama ignorada - $ref");
    sendResponse(200, 'Branch ignored', [
        'branch_received' => $ref,
        'branch_expected' => DEPLOY_BRANCH
    ]);
}

// Obtener información del commit
$commits = $data['commits'] ?? [];
$pusher = $data['pusher']['name'] ?? 'unknown';
$commitCount = count($commits);
$lastCommit = end($commits);
$commitMessage = $lastCommit['message'] ?? 'No message';
$commitAuthor = $lastCommit['author']['name'] ?? 'Unknown';

logMessage("✅ Push válido recibido:");
logMessage("   • Pusher: $pusher");
logMessage("   • Commits: $commitCount");
logMessage("   • Mensaje: $commitMessage");
logMessage("   • Autor: $commitAuthor");

// ============================================
// DESPLIEGUE
// ============================================

// Verificar que existe el script
if (!file_exists(DEPLOY_SCRIPT)) {
    logMessage("ERROR: Script de despliegue no encontrado - " . DEPLOY_SCRIPT);
    sendResponse(500, 'Deploy script not found');
}

// Hacer el script ejecutable (por si acaso)
chmod(DEPLOY_SCRIPT, 0755);

logMessage("🚀 Iniciando despliegue...");

// Ejecutar script de despliegue
$output = [];
$returnCode = 0;

exec('bash ' . DEPLOY_SCRIPT . ' 2>&1', $output, $returnCode);

$outputString = implode("\n", $output);

// Log del resultado
if ($returnCode === 0) {
    logMessage("✅ Despliegue exitoso");
    logMessage("Output:\n" . $outputString);
    
    sendResponse(200, 'Deployment successful', [
        'branch' => DEPLOY_BRANCH,
        'commits' => $commitCount,
        'pusher' => $pusher,
        'commit_message' => $commitMessage,
        'deploy_output' => $output
    ]);
} else {
    logMessage("❌ Error en despliegue - Código: $returnCode");
    logMessage("Output:\n" . $outputString);
    
    sendResponse(500, 'Deployment failed', [
        'error_code' => $returnCode,
        'output' => $output
    ]);
}

