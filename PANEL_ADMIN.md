# 👨‍💼 Panel de Administración

Sistema completo de gestión para el sitio web de Adopta un Abuelo Colombia.

---

## 🚀 Acceso al Panel

### URL del Panel

```
Local: http://localhost/caminemos-juntos-php/admin/login.html
Producción: https://caminemosjuntos.org/admin/login.html
```

### Credenciales por Defecto

⚠️ **IMPORTANTE: Cambiar después del primer login**

```
Usuario: admin
Contraseña: Admin123!
```

---

## 📋 Características del Panel

### 1. Dashboard
- ✅ Estadísticas en tiempo real
- ✅ Total de abuelos (por género)
- ✅ Total de donaciones y montos
- ✅ Mensajes pendientes
- ✅ Transacciones PayU
- ✅ Donaciones recientes
- ✅ Accesos rápidos

### 2. Gestión de Abuelos
- ✅ **Crear** nuevos abuelos
- ✅ **Editar** información existente
- ✅ **Eliminar** (soft delete)
- ✅ **Filtrar** por estado y género
- ✅ **Buscar** por nombre o ciudad
- ✅ **Paginación** automática
- ✅ Vista previa de imágenes

**Campos disponibles:**
- Nombre completo
- Edad (60-120 años)
- Ciudad
- Género (M/F)
- Descripción (mínimo 20 caracteres)
- Foto URL
- Fecha de nacimiento
- Estado (disponible/adoptado/eliminado)

### 3. Gestión de Donaciones
- ✅ Ver donaciones de personas
- ✅ Ver donaciones de empresas
- ✅ Filtrar por tipo y estado
- ✅ Ver detalles completos
- ✅ Estadísticas de montos
- ✅ Exportar datos (futuro)

### 4. Mensajes de Contacto
- ✅ Ver todos los mensajes
- ✅ Filtrar por leído/no leído
- ✅ Marcar como leído
- ✅ Eliminar mensajes
- ✅ Badge de mensajes no leídos
- ✅ Vista detallada con fecha y hora

### 5. Sistema de Autenticación
- ✅ Login seguro con tokens
- ✅ Sesiones con expiración (24 horas)
- ✅ Opción "Recordar sesión"
- ✅ Logout manual
- ✅ Auto-logout por inactividad
- ✅ Verificación de sesión en cada request

### 6. Roles y Permisos
- ✅ **Super Admin**: Acceso total
- ✅ **Admin**: Gestión completa excepto usuarios
- ✅ **Editor**: Solo crear y editar contenido

---

## 🗄️ Base de Datos

### Nuevas Tablas Creadas

#### admin_users
Usuarios administradores del sistema.

**Campos:**
- `id` - ID único
- `username` - Nombre de usuario
- `email` - Email
- `password_hash` - Contraseña encriptada (bcrypt)
- `nombre_completo` - Nombre real
- `rol` - super_admin, admin, editor
- `activo` - Estado (activo/inactivo)
- `ultimo_acceso` - Última vez que inició sesión
- `created_at`, `updated_at`

#### admin_sessions
Sesiones activas de administradores.

**Campos:**
- `id` - ID único
- `user_id` - Referencia al usuario
- `session_token` - Token de sesión (64 caracteres)
- `ip_address` - IP del usuario
- `user_agent` - Navegador utilizado
- `expires_at` - Fecha de expiración
- `created_at`

#### admin_logs
Registro de actividades del panel.

**Campos:**
- `id` - ID único
- `user_id` - Usuario que realizó la acción
- `accion` - Tipo de acción (LOGIN, CREATE, UPDATE, DELETE, etc.)
- `entidad` - Tabla afectada (abuelos, donaciones, etc.)
- `entidad_id` - ID del registro afectado
- `descripcion` - Detalles adicionales
- `ip_address` - IP del usuario
- `created_at` - Fecha y hora

---

## 🔐 Seguridad

### Medidas Implementadas

1. **Contraseñas Encriptadas**
   ```php
   password_hash($password, PASSWORD_BCRYPT);
   ```

2. **Tokens de Sesión**
   - 64 caracteres aleatorios
   - Almacenados de forma segura
   - Expiración automática

3. **Validación de Sesión**
   - Verificación en cada request
   - Auto-renovación de token
   - Limpieza de sesiones expiradas

4. **Logs de Actividad**
   - Registro de todas las acciones
   - IP y user agent guardados
   - Auditoría completa

5. **Roles y Permisos**
   - Control de acceso por nivel
   - Verificación en backend
   - Protección de endpoints críticos

---

## 📖 Uso del Panel

### Primer Acceso

1. **Acceder al panel:**
   ```
   http://localhost/caminemos-juntos-php/admin/login.html
   ```

2. **Iniciar sesión:**
   - Usuario: `admin`
   - Contraseña: `Admin123!`

3. **CAMBIAR LA CONTRASEÑA:**
   - Ve a Configuración
   - Click en "Cambiar Contraseña"
   - Ingresa contraseña actual y nueva
   - Guarda

### Agregar un Abuelo

1. Ve a "Abuelos" en el menú
2. Click en "Nuevo Abuelo"
3. Llena el formulario:
   - Nombre completo
   - Edad (60-120)
   - Ciudad
   - Género
   - Descripción (mínimo 20 caracteres)
   - Foto URL (ej: `assets/images/abuelo-13.jpg`)
4. Click en "Guardar"

### Gestionar Donaciones

1. Ve a "Donaciones"
2. Filtra por tipo (Personas/Empresas/Todas)
3. Click en el ícono del ojo para ver detalles
4. Cambiar estado si es necesario

### Ver Mensajes

1. Ve a "Mensajes"
2. Los mensajes no leídos aparecen destacados
3. Click en "Marcar como Leído" para procesarlos
4. Puedes eliminar mensajes antiguos

---

## 🔌 API Endpoints del Admin

### Autenticación

```http
POST /api/auth/login
Body: { "username": "admin", "password": "Admin123!" }

POST /api/auth/logout
Headers: Authorization: Bearer {token}

GET /api/auth/verify
Headers: Authorization: Bearer {token}

GET /api/auth/me
Headers: Authorization: Bearer {token}

POST /api/auth/change-password
Headers: Authorization: Bearer {token}
Body: { "current_password": "...", "new_password": "..." }
```

### Dashboard

```http
GET /api/admin/dashboard
Headers: Authorization: Bearer {token}
```

### Abuelos

```http
GET /api/admin/abuelos?pagina=1&limite=20&estado=disponible
Headers: Authorization: Bearer {token}

POST /api/admin/abuelos
Headers: Authorization: Bearer {token}
Body: { "nombre": "...", "edad": 75, ... }

PUT /api/admin/abuelos/{id}
Headers: Authorization: Bearer {token}
Body: { "nombre": "...", ... }

DELETE /api/admin/abuelos/{id}
Headers: Authorization: Bearer {token}
```

### Donaciones

```http
GET /api/admin/donaciones?tipo=todas&pagina=1
Headers: Authorization: Bearer {token}

PUT /api/admin/donaciones/{tipo}/{id}
Headers: Authorization: Bearer {token}
Body: { "estado": "completada" }
```

### Mensajes

```http
GET /api/admin/mensajes?leido=false
Headers: Authorization: Bearer {token}

PUT /api/admin/mensajes/{id}/marcar-leido
Headers: Authorization: Bearer {token}

DELETE /api/admin/mensajes/{id}
Headers: Authorization: Bearer {token}
```

### Logs

```http
GET /api/admin/logs?pagina=1&limite=50
Headers: Authorization: Bearer {token}
```

---

## 🛠️ Instalación

### 1. Crear las Tablas

```bash
# Desde terminal o phpMyAdmin
mysql -u root caminemos_juntos < database/admin-schema.sql
```

O en phpMyAdmin:
- Selecciona la base de datos `caminemos_juntos`
- Importar → `database/admin-schema.sql`

### 2. Verificar Instalación

```sql
-- Ver usuario creado
SELECT username, email, rol FROM admin_users;

-- Debe mostrar: admin | admin@caminemosjuntos.org | super_admin
```

### 3. Acceder al Panel

```
http://localhost/caminemos-juntos-php/admin/login.html
```

Usuario: `admin`  
Contraseña: `Admin123!`

### 4. Cambiar Contraseña

⚠️ **MUY IMPORTANTE**: Cambiar la contraseña por defecto inmediatamente.

---

## 🔒 Cambiar Contraseña por Defecto

### Opción 1: Desde el Panel (Próximamente)

1. Login con credenciales por defecto
2. Ve a Configuración
3. Cambiar contraseña

### Opción 2: Desde MySQL

```sql
-- Generar hash de nueva contraseña en PHP:
-- php -r "echo password_hash('TuNuevaContraseñaSegura', PASSWORD_BCRYPT);"

UPDATE admin_users 
SET password_hash = '$2y$10$NUEVO_HASH_AQUI'
WHERE username = 'admin';
```

### Opción 3: Crear script PHP

Crea `cambiar-password.php` en la raíz (temporal):

```php
<?php
require_once 'api/config/database.php';

$newPassword = 'TuNuevaContraseñaSegura';
$hash = password_hash($newPassword, PASSWORD_BCRYPT);

$db = Database::getInstance();
$db->execute(
    "UPDATE admin_users SET password_hash = ? WHERE username = 'admin'",
    [$hash]
);

echo "Contraseña actualizada exitosamente para usuario 'admin'";
echo "<br>Nueva contraseña: $newPassword";
echo "<br><strong>¡Elimina este archivo inmediatamente!</strong>";
?>
```

Accede a: `http://localhost/caminemos-juntos-php/cambiar-password.php`  
Luego **ELIMINA** el archivo.

---

## 👥 Crear Nuevos Usuarios

```sql
INSERT INTO admin_users (username, email, password_hash, nombre_completo, rol, activo) 
VALUES (
    'nuevo_usuario',
    'usuario@example.com',
    '$2y$10$...', -- Generar con password_hash()
    'Nombre Completo',
    'editor', -- o 'admin' o 'super_admin'
    TRUE
);
```

---

## 📊 Logs y Auditoría

Todos los usuarios tienen sus acciones registradas en `admin_logs`:

```sql
-- Ver actividad reciente
SELECT 
    l.created_at,
    u.username,
    l.accion,
    l.entidad,
    l.descripcion
FROM admin_logs l
JOIN admin_users u ON l.user_id = u.id
ORDER BY l.created_at DESC
LIMIT 50;

-- Ver actividad de un usuario
SELECT * FROM admin_logs 
WHERE user_id = 1 
ORDER BY created_at DESC;

-- Ver acciones específicas
SELECT * FROM admin_logs 
WHERE accion = 'ELIMINAR_ABUELO' 
ORDER BY created_at DESC;
```

---

## 🐛 Troubleshooting

### No puedo iniciar sesión

**Verificar credenciales:**
```sql
SELECT username, email FROM admin_users WHERE activo = TRUE;
```

**Resetear contraseña a `Admin123!`:**
```sql
UPDATE admin_users 
SET password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE username = 'admin';
```

### "Sesión expirada" constantemente

Limpiar sesiones antiguas:
```sql
DELETE FROM admin_sessions WHERE expires_at < NOW();
```

### No veo los abuelos en el panel

1. Verifica que existan en la BD:
   ```sql
   SELECT COUNT(*) FROM abuelos WHERE estado = 'disponible';
   ```

2. Abre F12 → Consola y busca errores

3. Verifica que el token esté siendo enviado

---

## 📦 Archivos del Panel

```
admin/
├── login.html                  # Página de login
├── dashboard.html              # Dashboard principal
├── abuelos.html                # Gestión de abuelos
├── donaciones.html             # Gestión de donaciones
├── mensajes.html               # Mensajes de contacto
├── logs.html                   # Logs de actividad (futuro)
├── configuracion.html          # Configuración (futuro)
│
└── assets/
    ├── css/
    │   └── admin.css           # Estilos del panel
    └── js/
        ├── admin-auth.js       # Sistema de autenticación
        └── admin-api.js        # Cliente API
```

---

## 🔄 Flujo de Autenticación

```
1. Usuario ingresa credenciales → POST /api/auth/login
2. Backend verifica usuario y contraseña
3. Backend genera token de sesión (64 chars)
4. Token guardado en admin_sessions
5. Token enviado al frontend
6. Frontend guarda token en localStorage/sessionStorage
7. Frontend incluye token en cada request: Authorization: Bearer {token}
8. Backend verifica token antes de procesar
9. Token renovado automáticamente (24h)
10. Logout elimina la sesión
```

---

## 🎨 Personalización

### Cambiar Colores

Edita `admin/assets/css/admin.css`:

```css
:root {
    --color-primary: #2c5f2d;    /* Verde principal */
    --color-secondary: #97bc62;  /* Verde claro */
    --color-accent: #ff8c42;     /* Naranja */
}
```

### Agregar Nuevas Secciones

1. Crea el HTML (ej: `admin/patrocinadores.html`)
2. Agrega el endpoint en `api/controllers/AdminController.php`
3. Agrega la ruta en `api/index.php`
4. Agrega el método en `admin/assets/js/admin-api.js`
5. Agrega el enlace en el sidebar

---

## 📱 Responsive

El panel es completamente responsive:
- ✅ Desktop: Sidebar fijo, contenido amplio
- ✅ Tablet: Sidebar colapsable
- ✅ Móvil: Sidebar oculto, botón de menú

---

## 🚀 Próximas Mejoras

### En Desarrollo
- [ ] Página de logs de actividad
- [ ] Configuración del sistema
- [ ] Gestión de patrocinadores
- [ ] Gestión de usuarios admin
- [ ] Cambio de contraseña desde el panel

### Futuras
- [ ] Upload de imágenes directo
- [ ] Exportar donaciones a Excel/CSV
- [ ] Gráficos de estadísticas (Chart.js)
- [ ] Notificaciones en tiempo real
- [ ] Editor WYSIWYG para descripciones
- [ ] Calendario de cumpleaños

---

## 📞 Soporte

Para soporte técnico del panel de administración:
- Consulta los logs en `admin_logs`
- Revisa la consola del navegador (F12)
- Verifica que la API responda correctamente

---

## ✅ Checklist Post-Instalación

- [ ] Acceder al panel con credenciales por defecto
- [ ] Cambiar contraseña del admin
- [ ] Crear usuario personal con tu email
- [ ] Probar crear un abuelo
- [ ] Probar editar un abuelo
- [ ] Verificar que las estadísticas se muestran
- [ ] Revisar mensajes de contacto
- [ ] Revisar donaciones
- [ ] Desactivar el usuario "admin" por defecto (opcional)

---

**Desarrollado por:** Fabián Esneider Menjura Sánchez  
**Email:** fabianesneider39@gmail.com  
**Versión:** 1.0.0  
**Fecha:** Octubre 2025

