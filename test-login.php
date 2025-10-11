<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'api/config/database.php';

echo "<h2>Test de Login</h2>";

// Obtener el usuario
$db = Database::getInstance();
$user = $db->fetchOne("SELECT * FROM admin_users WHERE username = 'admin'");

echo "<h3>Usuario en BD:</h3>";
echo "<pre>";
print_r($user);
echo "</pre>";

// Probar password_verify
$passwordToTest = 'Admin123!';
$isValid = password_verify($passwordToTest, $user['password_hash']);

echo "<h3>Verificación de contraseña:</h3>";
echo "Contraseña probada: <strong>$passwordToTest</strong><br>";
echo "Hash en BD: <code>{$user['password_hash']}</code><br>";
echo "Resultado: " . ($isValid ? "<strong style='color: green;'>✅ CORRECTO</strong>" : "<strong style='color: red;'>❌ INCORRECTO</strong>");

// Generar nuevo hash
echo "<hr>";
echo "<h3>Generar nuevo hash:</h3>";
$newHash = password_hash($passwordToTest, PASSWORD_BCRYPT);
echo "Nuevo hash generado: <code>$newHash</code><br>";

// Probar el nuevo hash
$testNew = password_verify($passwordToTest, $newHash);
echo "Verificación del nuevo: " . ($testNew ? "✅ OK" : "❌ FALLO");

echo "<hr>";
echo "<h3>Actualizar:</h3>";
echo "<form method='post'>";
echo "<input type='hidden' name='update' value='1'>";
echo "<button type='submit' class='btn btn-primary'>Actualizar password_hash en la BD</button>";
echo "</form>";

if (isset($_POST['update'])) {
    $db->execute(
        "UPDATE admin_users SET password_hash = ? WHERE username = 'admin'",
        [$newHash]
    );
    echo "<p style='color: green;'>✅ Actualizado! Recarga la página.</p>";
}
?>

