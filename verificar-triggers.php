<?php
// Script para verificar triggers

require_once 'api/config/database.php';

$db = Database::getInstance();

try {
    // Verificar triggers
    $triggers = $db->fetchAll("SHOW TRIGGERS FROM caminemos_juntos");
    
    echo "=== TRIGGERS EN LA BASE DE DATOS ===\n\n";
    
    if (empty($triggers)) {
        echo "❌ No hay triggers creados\n\n";
        echo "Necesitas ejecutar el archivo: database/sistema-notificaciones.sql\n";
        echo "En phpMyAdmin:\n";
        echo "1. Seleccionar base de datos 'caminemos_juntos'\n";
        echo "2. Ir a pestaña SQL\n";
        echo "3. Copiar y pegar el contenido del archivo sistema-notificaciones.sql\n";
        echo "4. Click en 'Continuar'\n";
    } else {
        echo "✅ Triggers encontrados: " . count($triggers) . "\n\n";
        
        foreach ($triggers as $trigger) {
            echo "- " . $trigger['Trigger'] . " -> " . $trigger['Table'] . " (" . $trigger['Timing'] . " " . $trigger['Event'] . ")\n";
        }
        
        // Buscar triggers de notificaciones
        $notifTriggers = array_filter($triggers, function($t) {
            return strpos($t['Trigger'], 'notif_') === 0;
        });
        
        echo "\n=== TRIGGERS DE NOTIFICACIONES ===\n\n";
        
        if (empty($notifTriggers)) {
            echo "❌ No se encontraron triggers de notificaciones\n";
            echo "Debes ejecutar el SQL para crearlos.\n";
        } else {
            echo "✅ Triggers de notificaciones: " . count($notifTriggers) . "\n";
            foreach ($notifTriggers as $t) {
                echo "  - " . $t['Trigger'] . "\n";
            }
        }
    }
    
    // Verificar notificaciones existentes
    $notifs = $db->fetchOne("SELECT COUNT(*) as count FROM notificaciones");
    echo "\n=== NOTIFICACIONES EXISTENTES ===\n";
    echo "Total: " . ($notifs['count'] ?? 0) . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

