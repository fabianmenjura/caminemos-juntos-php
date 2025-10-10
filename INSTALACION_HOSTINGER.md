# 🌐 Guía de Instalación en Hostinger - Paso a Paso
Desarrollado por FABIÁN ESNEIDER MENJURA SÁNCHEZ
fabianesneider39@gmail.com
2025
Esta guía te llevará desde cero hasta tener el proyecto en producción en Hostinger.

## 📋 Prerrequisitos

Antes de empezar, necesitas:

- ✅ Cuenta activa en Hostinger
- ✅ Plan de hosting con PHP y MySQL
- ✅ Dominio configurado (opcional pero recomendado)
- ✅ Proyecto funcionando en local
- ✅ Credenciales de PayU (para producción)

---

## 🎯 Resumen del Proceso

1. Preparar los archivos
2. Crear base de datos en Hostinger
3. Subir archivos al servidor
4. Configurar el .env
5. Importar datos
6. Configurar SSL
7. Probar el sitio

**Tiempo estimado**: 30-45 minutos

---

## 📦 Paso 1: Preparar los Archivos

### 1.1 Verificar el Proyecto Local

Antes de subir, asegúrate de que funciona en local:

```bash
http://localhost/caminemos-juntos-php/
http://localhost/caminemos-juntos-php/api/health
```

### 1.2 Crear una Copia Limpia

1. Abre `C:\laragon\www\`
2. Copia la carpeta `caminemos-juntos-php`
3. Renombra la copia a `caminemos-juntos-php-produccion`

### 1.3 Limpiar Archivos Innecesarios

En la carpeta de producción, **elimina**:
- ❌ `api/.env` (lo crearemos nuevo en el servidor)
- ❌ Archivos de desarrollo o prueba
- ❌ Carpetas `.git` (si existen)

### 1.4 Comprimir la Carpeta

1. Click derecho en `caminemos-juntos-php-produccion`
2. "Enviar a" → "Carpeta comprimida"
3. Renombra a `proyecto.zip`

**✅ Verificación**: Tienes un archivo `proyecto.zip` listo para subir.

---

## 🗄️ Paso 2: Crear la Base de Datos en Hostinger

### 2.1 Acceder al Panel de Hostinger

1. Ve a https://www.hostinger.com/
2. Inicia sesión con tu cuenta
3. Click en "Hosting" en el menú superior
4. Selecciona tu plan de hosting

### 2.2 Crear una Nueva Base de Datos

1. En el panel lateral, busca "Bases de datos MySQL"
2. Click en "Administrar bases de datos MySQL"
3. Click en "Crear nueva base de datos"
4. Configuración:
   ```
   Nombre de BD: caminemos_juntos (o el que prefieras)
   Usuario: (se crea automáticamente, anótalo)
   Contraseña: (genera una segura y anótala)
   ```
5. Click en "Crear"

### 2.3 Anotar las Credenciales

Guarda esta información en un lugar seguro:
```
Servidor MySQL: localhost (o la IP que te den)
Nombre de BD: u123456789_caminemos (ejemplo)
Usuario: u123456789_admin (ejemplo)
Contraseña: [tu contraseña generada]
Puerto: 3306 (por defecto)
```

**⚠️ IMPORTANTE**: No pierdas estas credenciales, las necesitarás después.

---

## 📤 Paso 3: Subir los Archivos

### 3.1 Acceder al Administrador de Archivos

1. En el panel de Hostinger, busca "Administrador de archivos"
2. Click en "Administrar"
3. Se abrirá el File Manager

### 3.2 Navegar a public_html

1. En el File Manager, busca la carpeta `public_html`
2. Doble click para entrar
3. **Importante**: Todo lo que subas aquí será visible en tu dominio

### 3.3 Subir el Archivo ZIP

1. Click en "Subir archivos" (botón arriba a la derecha)
2. Arrastra `proyecto.zip` o click en "Seleccionar archivos"
3. Espera a que suba (puede tardar 2-5 minutos dependiendo de tu conexión)
4. Verifica que aparezca `proyecto.zip` en la lista

### 3.4 Extraer el ZIP

1. Click derecho en `proyecto.zip`
2. Selecciona "Extraer"
3. Destino: `/public_html` (debe estar seleccionado)
4. Click en "Extraer"
5. Espera a que termine

### 3.5 Mover los Archivos

Ahora tienes: `public_html/caminemos-juntos-php-produccion/`

**Opción A: Usar un subdirectorio** (más fácil)
```
Renombra la carpeta a solo "caminemos"
Tu sitio será: tudominio.com/caminemos/
```

**Opción B: En la raíz** (recomendado para producción)
```
1. Entra a la carpeta caminemos-juntos-php-produccion
2. Selecciona TODO (Ctrl + A)
3. Click en "Mover"
4. Destino: /public_html
5. Mueve
6. Elimina la carpeta vacía caminemos-juntos-php-produccion
```

### 3.6 Eliminar el ZIP

1. Selecciona `proyecto.zip`
2. Click en "Eliminar"

**✅ Verificación**: En `public_html` debes ver ahora:
- `index.html`
- `donar.html`
- `.htaccess`
- Carpetas: `api/`, `assets/`, `database/`, `donacion/`

---

## ⚙️ Paso 4: Configurar el Archivo .env

### 4.1 Crear el Archivo .env

1. En el File Manager, navega a `public_html/api/`
2. Click en "Nuevo archivo"
3. Nombre: `.env` (con el punto al inicio)
4. Click en "Crear"

### 4.2 Editar el .env

1. Click derecho en `.env`
2. "Editar"
3. Copia y pega este contenido (ajustando tus datos):

```env
# Configuración de la Aplicación
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

# Configuración de Base de Datos
DB_HOST=localhost
DB_NAME=u123456789_caminemos
DB_USER=u123456789_admin
DB_PASSWORD=tu_contraseña_mysql_aquí

# Configuración de PayU (Producción)
# ⚠️ CAMBIA ESTOS VALORES POR TUS CREDENCIALES REALES DE PAYU
PAYU_MERCHANT_ID=TU_MERCHANT_ID
PAYU_API_KEY=TU_API_KEY
PAYU_API_LOGIN=TU_API_LOGIN
PAYU_ACCOUNT_ID=TU_ACCOUNT_ID
PAYU_TEST_MODE=false

# URLs de PayU
PAYU_RESPONSE_URL=https://tudominio.com/api/payu/response.php
PAYU_CONFIRMATION_URL=https://tudominio.com/api/payu/confirmation.php
```

4. **Ajusta estos valores**:
   - `APP_URL`: Tu dominio real (con https://)
   - `DB_NAME`, `DB_USER`, `DB_PASSWORD`: Las credenciales del Paso 2
   - Credenciales de PayU: Las que obtuviste de PayU Latam
   - `PAYU_TEST_MODE`: Déjalo en `true` para pruebas, cámbialo a `false` para producción real

5. Guarda el archivo (Ctrl + S o botón "Guardar")

**✅ Verificación**: El archivo `.env` está en `public_html/api/.env` con tus datos reales.

---

## 📊 Paso 5: Importar la Base de Datos

### 5.1 Acceder a phpMyAdmin

1. En el panel de Hostinger, busca "phpMyAdmin"
2. Click en "Administrar"
3. Se abrirá phpMyAdmin en una nueva pestaña

### 5.2 Seleccionar tu Base de Datos

1. En el menú izquierdo, busca tu base de datos (ej: `u123456789_caminemos`)
2. Click en ella

### 5.3 Importar schema.sql

1. Click en la pestaña "Importar"
2. Click en "Seleccionar archivo"
3. Necesitas descargar `schema.sql` del servidor:
   - Vuelve al File Manager
   - Navega a `public_html/database/`
   - Click derecho en `schema.sql` → "Descargar"
4. En phpMyAdmin, selecciona el `schema.sql` descargado
5. Scroll hasta abajo
6. Click en "Continuar"
7. Espera el mensaje "Importación finalizada con éxito"

### 5.4 Importar seed.sql (Datos de Prueba)

1. Repite el proceso anterior con `seed.sql`
2. Descarga `seed.sql` del servidor
3. Impórtalo en phpMyAdmin

### 5.5 Verificar los Datos

1. En phpMyAdmin, en el menú izquierdo, click en tu base de datos
2. Debes ver 7 tablas:
   - abuelos
   - cumpleanos
   - donaciones_empresas
   - donaciones_personas
   - mensajes_contacto
   - patrocinadores
   - transacciones_payu
3. Click en `abuelos` → Click en "Examinar"
4. Debes ver 12 registros

**✅ Verificación**: Todas las tablas creadas y con datos de prueba.

---

## 🔒 Paso 6: Configurar SSL (HTTPS)

### 6.1 Verificar el SSL

1. En el panel de Hostinger, busca "SSL"
2. Verifica que tu dominio tiene SSL activo (candado verde)
3. Si no lo tiene, actívalo:
   - Click en "Administrar SSL"
   - Selecciona tu dominio
   - Click en "Instalar SSL gratuito"
   - Espera 5-10 minutos

### 6.2 Forzar HTTPS

1. En el File Manager, abre `.htaccess` (en la raíz)
2. Verifica que tenga estas líneas descomentadas:

```apache
# Forzar HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

3. Si están comentadas (con #), quítale el #
4. Guarda el archivo

**✅ Verificación**: Al visitar http://tudominio.com, debe redirigir automáticamente a https://tudominio.com

---

## 🧪 Paso 7: Probar el Sitio

### 7.1 Probar la API

Abre en tu navegador:
```
https://tudominio.com/api/health
```

Debe devolver:
```json
{
  "success": true,
  "data": {
    "status": "OK",
    "message": "API funcionando correctamente",
    "timestamp": "..."
  }
}
```

### 7.2 Probar la Lista de Abuelos

```
https://tudominio.com/api/abuelos
```

Debe devolver un JSON con 12 abuelos.

### 7.3 Probar el Sitio Web

1. Ve a: `https://tudominio.com/`
2. Verifica:
   - ✅ La página carga correctamente
   - ✅ Las imágenes se ven
   - ✅ La galería de abuelos muestra datos
   - ✅ Los botones funcionan
   - ✅ El candado SSL aparece en el navegador

### 7.4 Probar el Formulario de Donación

1. Click en "Donar"
2. Llena el formulario con datos de prueba
3. Selecciona "Tarjeta de crédito"
4. Si `PAYU_TEST_MODE=true`, usa la tarjeta de prueba:
   ```
   Número: 4097440000000004
   CVV: 123
   Fecha: 12/2026
   Nombre: APPROVED
   ```
5. Completa el pago
6. Debes ser redirigido a la página de éxito

### 7.5 Verificar en la Base de Datos

1. Vuelve a phpMyAdmin
2. Selecciona la tabla `transacciones_payu`
3. Click en "Examinar"
4. Debe aparecer la transacción que acabas de hacer

**✅ Verificación**: Si todo lo anterior funciona, ¡el sitio está en producción!

---

## 🔧 Paso 8: Configuración de Producción de PayU

### 8.1 Obtener Credenciales Reales de PayU

1. Regístrate en https://www.payu.com.co/
2. Completa el proceso de registro comercial
3. Proporciona documentación requerida (RUT, cédula, etc.)
4. Espera la aprobación (puede tardar 2-5 días hábiles)
5. Una vez aprobado, obtén tus credenciales de producción:
   - Merchant ID
   - API Key
   - API Login
   - Account ID

### 8.2 Actualizar el .env

1. En el File Manager, edita `api/.env`
2. Reemplaza las credenciales de PayU con las reales
3. **Muy importante**: Cambia `PAYU_TEST_MODE=false`
4. Guarda

### 8.3 Probar con Pago Real

⚠️ **ADVERTENCIA**: Esto procesará un cargo real

1. Usa una tarjeta real
2. Haz una donación pequeña (ej: $5,000 COP)
3. Verifica que el pago se procese correctamente
4. Revisa tu dashboard de PayU para confirmar

---

## 🔍 Paso 9: Optimizaciones Finales

### 9.1 Configurar Permisos

En el File Manager:
- Archivos: 644
- Carpetas: 755
- `.env`: 600 (más seguro)

Para cambiar permisos:
1. Click derecho en archivo/carpeta
2. "Permisos"
3. Ajusta según necesidad

### 9.2 Agregar robots.txt

Crea `public_html/robots.txt`:
```
User-agent: *
Allow: /
Sitemap: https://tudominio.com/sitemap.xml

Disallow: /api/
Disallow: /database/
```

### 9.3 Agregar .htaccess de Seguridad Adicional

En `public_html/api/.htaccess`, agrega:
```apache
# Proteger archivos sensibles
<Files ".env">
  Order allow,deny
  Deny from all
</Files>

<Files "*.php">
  <IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !index\.php$
    RewriteRule ^(.*)$ index.php/$1 [L]
  </IfModule>
</Files>
```

### 9.4 Habilitar Compresión GZIP

En `.htaccess` raíz, verifica que esté:
```apache
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>
```

---

## 🐛 Solución de Problemas en Hostinger

### Problema 1: "Error 500"

**Causas posibles**:
- Archivo `.env` faltante o con errores
- Credenciales de BD incorrectas
- `.htaccess` mal configurado

**Solución**:
1. Verifica que `.env` existe en `api/`
2. Compara las credenciales con las de phpMyAdmin
3. Revisa los logs de errores en el panel de Hostinger

---

### Problema 2: "No se conecta a la base de datos"

**Solución**:
1. En phpMyAdmin, verifica que puedes acceder a la BD
2. Copia exactamente el nombre de usuario y contraseña
3. En `.env`, asegúrate de que no hay espacios antes o después de los valores
4. Prueba manualmente la conexión desde el terminal SSH (si tienes acceso)

---

### Problema 3: "Las imágenes no cargan"

**Solución**:
1. Verifica que la carpeta `assets/images/` se subió correctamente
2. Cambia los permisos de `assets/` a 755
3. Cambia los permisos de `assets/images/` a 755
4. Verifica las rutas en el código (deben ser relativas)

---

### Problema 4: "PayU devuelve INVALID_SIGNATURE"

**Solución**:
1. Verifica que `PAYU_API_KEY` y `PAYU_MERCHANT_ID` son correctos
2. Asegúrate de que no hay espacios en las credenciales
3. Si estás en modo prueba, usa las credenciales de sandbox
4. Si estás en producción, usa las credenciales reales

---

### Problema 5: "Error CORS al llamar la API"

**Solución**:
1. Verifica que `APP_URL` en `.env` coincide con tu dominio real
2. Asegúrate de que estás usando HTTPS
3. En el código, verifica que las llamadas a la API también usan HTTPS

---

### Problema 6: "Página en blanco"

**Solución**:
1. Habilita temporalmente `APP_DEBUG=true` en `.env`
2. Recarga la página para ver el error
3. Una vez identificado el error, vuelve a `APP_DEBUG=false`
4. Revisa los logs en el panel de Hostinger

---

## 📈 Monitoreo y Mantenimiento

### Revisar Logs

En el panel de Hostinger:
1. Ve a "Estadísticas"
2. "Logs de errores"
3. Revisa regularmente

### Backups

Hostinger hace backups automáticos, pero es bueno hacer tus propios:
1. Cada semana, exporta la base de datos desde phpMyAdmin
2. Descarga una copia del sitio desde el File Manager
3. Guarda en un lugar seguro

### Actualizar Datos

Para agregar/modificar abuelos:
1. Usa phpMyAdmin directamente
2. O crea un panel de administración (proyecto futuro)

---

## 🎉 ¡Felicitaciones!

Tu sitio está ahora en producción en Hostinger 🚀

### Checklist Final

- [x] Base de datos creada e importada
- [x] Archivos subidos al servidor
- [x] `.env` configurado con credenciales reales
- [x] SSL habilitado (HTTPS)
- [x] API funcionando
- [x] Sitio web visible y funcional
- [x] PayU configurado
- [x] Pruebas de donación exitosas

---

## 📞 Soporte

**Panel de Hostinger**: https://www.hostinger.com/cpanel  
**PayU Colombia**: https://www.payu.com.co/  
**Documentación PayU**: https://developers.payulatam.com/

---

## 📚 Siguientes Pasos

1. 🎨 Personaliza el diseño con tu logo y colores
2. 📸 Reemplaza las fotos de prueba con fotos reales
3. ✍️ Actualiza los textos con información real
4. 📧 Configura notificaciones por email
5. 📊 Agrega Google Analytics
6. 🔍 Optimiza para SEO
7. 💼 Crea un panel de administración (opcional)

---

**¡Éxito con tu proyecto!** 💚

