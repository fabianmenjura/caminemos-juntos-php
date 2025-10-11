<?php
/**
 * Visor de Logs
 * Acceso: http://localhost/caminemos-juntos-php/ver-logs.php
 */

// Seguridad básica
$password = 'Admin123!';
$inputPassword = $_GET['pass'] ?? '';

if ($inputPassword !== $password) {
    die('<h1>Acceso Denegado</h1><p>Agrega ?pass=Admin123! a la URL</p>');
}

$logDir = __DIR__ . '/logs/';
$logFile = $_GET['file'] ?? 'debug.log';
$lines = isset($_GET['lines']) ? intval($_GET['lines']) : 100;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visor de Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; background: #1e1e1e; color: #d4d4d4; font-family: 'Consolas', monospace; }
        .log-content { background: #2d2d2d; padding: 20px; border-radius: 8px; overflow-x: auto; }
        pre { margin: 0; white-space: pre-wrap; font-size: 12px; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        .success { color: #4ec9b0; }
        .header { background: #3c3c3c; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .btn { margin: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-file-alt"></i> Visor de Logs</h1>
        <div class="mt-3">
            <?php
            $files = glob($logDir . '*.log');
            foreach ($files as $file) {
                $basename = basename($file);
                $active = ($basename === $logFile) ? 'btn-primary' : 'btn-outline-light';
                echo "<a href='?pass=$password&file=$basename&lines=$lines' class='btn btn-sm $active'>$basename</a> ";
            }
            ?>
        </div>
        <div class="mt-2">
            <select class="form-select form-select-sm d-inline-block w-auto" onchange="location.href='?pass=<?=$password?>&file=<?=$logFile?>&lines='+this.value">
                <option value="50" <?=$lines==50?'selected':''?>>50 líneas</option>
                <option value="100" <?=$lines==100?'selected':''?>>100 líneas</option>
                <option value="500" <?=$lines==500?'selected':''?>>500 líneas</option>
                <option value="1000" <?=$lines==1000?'selected':''?>>1000 líneas</option>
            </select>
            <button class="btn btn-sm btn-success" onclick="location.reload()">🔄 Recargar</button>
            <a href="?pass=<?=$password?>&file=<?=$logFile?>&lines=<?=$lines?>&clear=1" class="btn btn-sm btn-danger" onclick="return confirm('¿Limpiar este log?')">🗑️ Limpiar</a>
        </div>
    </div>

    <div class="log-content">
        <h5 class="text-white mb-3">📄 <?= htmlspecialchars($logFile) ?></h5>
        <pre><?php
        $filepath = $logDir . $logFile;
        
        // Limpiar log si se solicita
        if (isset($_GET['clear'])) {
            file_put_contents($filepath, '');
            echo "✅ Log limpiado\n";
        }
        
        if (file_exists($filepath)) {
            $content = file($filepath);
            $content = array_slice($content, -$lines);
            
            foreach ($content as $line) {
                $line = htmlspecialchars($line);
                
                // Colorear según tipo
                if (strpos($line, '[ERROR]') !== false) {
                    echo "<span class='error'>$line</span>";
                } else if (strpos($line, '[WARNING]') !== false) {
                    echo "<span class='warning'>$line</span>";
                } else if (strpos($line, '[SUCCESS]') !== false || strpos($line, 'SUCCESS') !== false) {
                    echo "<span class='success'>$line</span>";
                } else {
                    echo $line;
                }
            }
            
            if (empty($content)) {
                echo "📭 El log está vacío\n";
            }
        } else {
            echo "❌ El archivo de log no existe: $filepath\n";
            echo "📁 Archivos disponibles:\n";
            foreach (glob($logDir . '*.log') as $file) {
                echo "  - " . basename($file) . "\n";
            }
        }
        ?></pre>
    </div>

    <div class="mt-3 text-center">
        <small class="text-muted">
            Auto-recarga en <span id="countdown">10</span>s 
            <button class="btn btn-sm btn-outline-light" onclick="clearInterval(timer); document.getElementById('countdown').textContent = '∞'">Pausar</button>
        </small>
    </div>

    <script>
        let seconds = 10;
        const timer = setInterval(() => {
            seconds--;
            document.getElementById('countdown').textContent = seconds;
            if (seconds <= 0) location.reload();
        }, 1000);
    </script>
</body>
</html>

