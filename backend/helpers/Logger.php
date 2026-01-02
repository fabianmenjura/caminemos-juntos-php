<?php
/**
 * Sistema de Logging del Proyecto
 * Logs separados por tipo para mejor debugging
 */

class Logger {
    private static $logDir = __DIR__ . '/../../logs/';
    
    /**
     * Log general
     */
    public static function log($message, $context = []) {
        self::writeLog('app.log', 'INFO', $message, $context);
    }
    
    /**
     * Log de errores
     */
    public static function error($message, $context = []) {
        self::writeLog('error.log', 'ERROR', $message, $context);
    }
    
    /**
     * Log de API
     */
    public static function api($endpoint, $method, $status, $data = []) {
        $message = "$method $endpoint - Status: $status";
        self::writeLog('api.log', 'API', $message, $data);
    }
    
    /**
     * Log de autenticación
     */
    public static function auth($action, $username = null, $success = true) {
        $message = "$action - User: " . ($username ?? 'unknown') . " - " . ($success ? 'SUCCESS' : 'FAILED');
        self::writeLog('auth.log', 'AUTH', $message);
    }
    
    /**
     * Log de base de datos
     */
    public static function db($query, $params = [], $error = null) {
        $message = "Query: $query";
        $context = ['params' => $params];
        
        if ($error) {
            $context['error'] = $error;
            self::writeLog('db.log', 'DB_ERROR', $message, $context);
        } else {
            self::writeLog('db.log', 'DB', $message, $context);
        }
    }
    
    /**
     * Log de debug (siempre activo para troubleshooting)
     */
    public static function debug($message, $context = []) {
        // Siempre escribir logs de debug
        self::writeLog('debug.log', 'DEBUG', $message, $context);
    }
    
    /**
     * Escribir en el archivo de log
     */
    private static function writeLog($filename, $level, $message, $context = []) {
        try {
            // Crear directorio si no existe
            if (!is_dir(self::$logDir)) {
                mkdir(self::$logDir, 0755, true);
            }
            
            $filepath = self::$logDir . $filename;
            
            // Formato del log
            $timestamp = date('Y-m-d H:i:s');
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
            $method = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
            $uri = $_SERVER['REQUEST_URI'] ?? '-';
            
            $logEntry = "[$timestamp] [$level] [$ip] [$method] $uri\n";
            $logEntry .= "Message: $message\n";
            
            if (!empty($context)) {
                $logEntry .= "Context: " . json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            }
            
            $logEntry .= str_repeat('-', 80) . "\n";
            
            // Escribir en el archivo
            file_put_contents($filepath, $logEntry, FILE_APPEND | LOCK_EX);
            
            // Rotar logs si son muy grandes (> 10MB)
            if (file_exists($filepath) && filesize($filepath) > 10 * 1024 * 1024) {
                rename($filepath, self::$logDir . date('Y-m-d_His') . '_' . $filename);
            }
            
        } catch (Exception $e) {
            // Si falla el logging, usar error_log de PHP
            error_log("Logger failed: " . $e->getMessage());
            error_log("Original message: $message");
        }
    }
    
    /**
     * Limpiar logs antiguos
     */
    public static function clean($days = 30) {
        $files = glob(self::$logDir . '*.log');
        $now = time();
        
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 60 * 60 * 24 * $days) {
                    unlink($file);
                }
            }
        }
    }
    
    /**
     * Obtener últimas líneas de un log
     */
    public static function tail($filename, $lines = 100) {
        $filepath = self::$logDir . $filename;
        
        if (!file_exists($filepath)) {
            return [];
        }
        
        $file = new SplFileObject($filepath, 'r');
        $file->seek(PHP_INT_MAX);
        $lastLine = $file->key();
        $startLine = max(0, $lastLine - $lines);
        
        $result = [];
        for ($i = $startLine; $i <= $lastLine; $i++) {
            $file->seek($i);
            $result[] = $file->current();
        }
        
        return $result;
    }
}
?>

