<?php
// Script temporal para generar hash de contraseña
$password = 'Admin123!';
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "Contraseña: $password\n";
echo "Hash: $hash\n\n";

// Actualizar en la base de datos
require_once 'api/config/database.php';
$db = Database::getInstance();

$db->execute(
    "UPDATE admin_users SET password_hash = ? WHERE username = 'admin'",
    [$hash]
);

echo "✅ Contraseña actualizada en la base de datos\n";
echo "Usuario: admin\n";
echo "Contraseña: $password\n\n";
echo "⚠️ ELIMINA ESTE ARCHIVO INMEDIATAMENTE\n";
?>

