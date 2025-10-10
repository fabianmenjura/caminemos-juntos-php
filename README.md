# 🏠 Adopta un Abuelo Colombia

Sistema web completo para gestión de adopciones de abuelos, donaciones y contacto con integración de PayU para pagos en línea.

## 📋 Índice

- [Características](#características)
- [Requisitos](#requisitos)
- [Instalación Local](#instalación-local)
- [Instalación en Hostinger](#instalación-en-hostinger)
- [Configuración](#configuración)
- [Uso](#uso)
- [API](#api)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Soporte](#soporte)

---

## ✨ Características

- ✅ Galería de abuelos disponibles para adopción
- ✅ Sistema de donaciones (personas y empresas)
- ✅ Integración con PayU (PSE, tarjetas, efectivo)
- ✅ Formulario de contacto
- ✅ Cumpleaños destacados del mes
- ✅ Sección de patrocinadores
- ✅ Diseño responsivo (móvil y desktop)
- ✅ API RESTful completa
- ✅ Seguridad con validación y sanitización

---

## 🔧 Requisitos

### Servidor Local
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache con mod_rewrite habilitado
- Laragon (recomendado) o XAMPP

### Servidor Producción (Hostinger)
- Plan de hosting con PHP y MySQL
- Acceso a phpMyAdmin
- Soporte para .htaccess
- SSL habilitado (recomendado)

---

## 🚀 Instalación Local

### Paso 1: Clonar o Descargar el Proyecto

```bash
# Si usas Git
git clone [URL_DEL_REPOSITORIO] C:\laragon\www\caminemos-juntos-php

# O simplemente copia la carpeta a:
C:\laragon\www\caminemos-juntos-php
```

### Paso 2: Configurar la Base de Datos

1. **Iniciar Laragon**
   - Abre Laragon
   - Inicia Apache y MySQL

2. **Crear la Base de Datos**
   - Abre phpMyAdmin: http://localhost/phpmyadmin
   - Crea una nueva base de datos llamada `caminemos_juntos`
   - Selecciona la base de datos

3. **Importar la Estructura**
   - Ve a la pestaña "Importar"
   - Selecciona el archivo `database/schema.sql`
   - Click en "Continuar"

4. **Importar los Datos de Prueba** (Opcional)
   - Ve a la pestaña "Importar"
   - Selecciona el archivo `database/seed.sql`
   - Click en "Continuar"

### Paso 3: Configurar el Archivo .env

1. **Copiar el archivo de ejemplo**
   ```bash
   cd C:\laragon\www\caminemos-juntos-php\api
   copy config.example.env .env
   ```

2. **Editar el archivo .env**
   ```env
   APP_ENV=development
   APP_DEBUG=true
   APP_URL=http://localhost/caminemos-juntos-php
   
   DB_HOST=localhost
   DB_NAME=caminemos_juntos
   DB_USER=root
   DB_PASSWORD=
   
   # PayU Sandbox (pruebas)
   PAYU_MERCHANT_ID=508029
   PAYU_API_KEY=4Vj8eK4rloUd272L48hsrarnUA
   PAYU_API_LOGIN=pRRXKOl8ikMmt9u
   PAYU_ACCOUNT_ID=512321
   PAYU_TEST_MODE=true
   
   PAYU_RESPONSE_URL=http://localhost/caminemos-juntos-php/api/payu/response.php
   PAYU_CONFIRMATION_URL=http://localhost/caminemos-juntos-php/api/payu/confirmation.php
   ```

### Paso 4: Verificar la Instalación

1. **Abrir el navegador**
   ```
   http://localhost/caminemos-juntos-php/
   ```

2. **Probar la API**
   ```
   http://localhost/caminemos-juntos-php/api/health
   ```
   
   Debe devolver:
   ```json
   {
     "success": true,
     "data": {
       "status": "OK",
       "message": "API funcionando correctamente"
     }
   }
   ```

3. **Probar la galería de abuelos**
   ```
   http://localhost/caminemos-juntos-php/api/abuelos
   ```

---

## 🌐 Instalación en Hostinger

### Paso 1: Preparar los Archivos

1. **Comprimir el proyecto**
   - Comprime toda la carpeta `caminemos-juntos-php`
   - Formato: `.zip`

### Paso 2: Subir al Hosting

1. **Acceder al File Manager de Hostinger**
   - Inicia sesión en tu panel de Hostinger
   - Ve a "Archivos" → "Administrador de archivos"

2. **Subir los archivos**
   - Navega a la carpeta `public_html`
   - Sube el archivo `.zip`
   - Extrae el contenido
   - Mueve todos los archivos de `caminemos-juntos-php` a `public_html`

### Paso 3: Configurar la Base de Datos

1. **Crear la Base de Datos**
   - En el panel de Hostinger, ve a "Bases de datos MySQL"
   - Click en "Crear nueva base de datos"
   - Nombre: `u123456_caminemos` (o el que prefieras)
   - Crea un usuario y anota las credenciales

2. **Importar la Estructura**
   - En "Bases de datos MySQL", click en "Administrar" (phpMyAdmin)
   - Selecciona tu base de datos
   - Ve a "Importar"
   - Sube `database/schema.sql`
   - Click en "Continuar"

3. **Importar los Datos**
   - Repite el proceso con `database/seed.sql`

### Paso 4: Configurar el Archivo .env

1. **Crear el archivo .env**
   - En el File Manager, navega a `public_html/api`
   - Crea un nuevo archivo llamado `.env`
   - Copia el contenido de `config.example.env`

2. **Editar las credenciales**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://tudominio.com
   
   DB_HOST=localhost
   DB_NAME=u123456_caminemos
   DB_USER=u123456_admin
   DB_PASSWORD=TU_CONTRASEÑA_MYSQL
   
   # PayU Producción (cuando estés listo)
   PAYU_MERCHANT_ID=TU_MERCHANT_ID
   PAYU_API_KEY=TU_API_KEY
   PAYU_API_LOGIN=TU_API_LOGIN
   PAYU_ACCOUNT_ID=TU_ACCOUNT_ID
   PAYU_TEST_MODE=false
   
   PAYU_RESPONSE_URL=https://tudominio.com/api/payu/response.php
   PAYU_CONFIRMATION_URL=https://tudominio.com/api/payu/confirmation.php
   ```

### Paso 5: Configurar .htaccess

1. **Editar .htaccess en la raíz**
   - Descomenta la redirección HTTPS:
   ```apache
   # Forzar HTTPS
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

### Paso 6: Verificar la Instalación

1. **Probar el sitio**
   ```
   https://tudominio.com/
   ```

2. **Probar la API**
   ```
   https://tudominio.com/api/health
   ```

3. **Verificar SSL**
   - Asegúrate de que el candado verde aparece en el navegador

---

## ⚙️ Configuración

### Configuración de PayU

#### Modo Sandbox (Pruebas)
```env
PAYU_MERCHANT_ID=508029
PAYU_API_KEY=4Vj8eK4rloUd272L48hsrarnUA
PAYU_API_LOGIN=pRRXKOl8ikMmt9u
PAYU_ACCOUNT_ID=512321
PAYU_TEST_MODE=true
```

**Tarjetas de prueba:**
- Visa: `4097440000000004`
- Mastercard: `5471300000000003`
- CVV: `123`
- Fecha: Cualquier fecha futura

#### Modo Producción
1. Regístrate en [PayU Colombia](https://www.payu.com.co/)
2. Obtén tus credenciales reales
3. Actualiza el `.env` con tus datos
4. Cambia `PAYU_TEST_MODE=false`

### Personalización

#### Cambiar Colores
Edita `assets/css/style.css`:
```css
:root {
    --color-primary: #2c5f2d;    /* Verde principal */
    --color-secondary: #97bc62;  /* Verde claro */
    --color-accent: #ff8c42;     /* Naranja */
}
```

#### Agregar Abuelos
1. Accede a phpMyAdmin
2. Ve a la tabla `abuelos`
3. Inserta nuevos registros manualmente o usa:
```sql
INSERT INTO abuelos (nombre, edad, ciudad, descripcion, genero, foto_url) 
VALUES ('Nombre Completo', 75, 'Ciudad', 'Descripción...', 'M', 'ruta/foto.jpg');
```

#### Agregar Patrocinadores
```sql
INSERT INTO patrocinadores (nombre, logo_url, sitio_web, descripcion, nivel) 
VALUES ('Empresa', 'logo.png', 'https://...', 'Descripción', 'oro');
```

---

## 📖 Uso

### Para Usuarios Finales

1. **Explorar Abuelos**
   - Navega por la galería
   - Usa los filtros por género
   - Busca por nombre o ciudad

2. **Hacer una Donación**
   - Click en "Donar"
   - Selecciona tipo (persona o empresa)
   - Llena el formulario
   - Elige método de pago (PSE, Tarjeta, Efectivo)
   - Completa el pago

3. **Enviar Mensaje**
   - Ve a la sección de contacto
   - Llena el formulario
   - Click en "Enviar"

### Para Administradores

#### Ver Donaciones
```sql
-- Donaciones de personas
SELECT * FROM donaciones_personas ORDER BY fecha_donacion DESC;

-- Donaciones de empresas
SELECT * FROM donaciones_empresas ORDER BY fecha_donacion DESC;

-- Transacciones PayU
SELECT * FROM transacciones_payu ORDER BY fecha_transaccion DESC;
```

#### Ver Mensajes
```sql
SELECT * FROM mensajes_contacto ORDER BY fecha_envio DESC;
```

---

## 🔌 API

### Endpoints Disponibles

#### Salud del API
```http
GET /api/health
```

**Respuesta:**
```json
{
  "success": true,
  "data": {
    "status": "OK",
    "message": "API funcionando correctamente",
    "timestamp": "2025-10-10T13:41:34-05:00"
  }
}
```

#### Listar Abuelos
```http
GET /api/abuelos?pagina=1&limite=10&genero=M
```

**Parámetros:**
- `pagina` (opcional): Número de página (default: 1)
- `limite` (opcional): Resultados por página (default: 10)
- `genero` (opcional): 'M', 'F', o null para todos

**Respuesta:**
```json
{
  "success": true,
  "data": {
    "abuelos": [...],
    "total": 12,
    "pagina": 1,
    "limite": 10,
    "totalPaginas": 2
  }
}
```

#### Buscar Abuelos
```http
GET /api/abuelos/buscar/{termino}?limite=10
```

#### Cumpleaños del Mes
```http
GET /api/cumpleanos?limite=5
```

#### Patrocinadores
```http
GET /api/patrocinadores?nivel=oro
```

#### Enviar Mensaje de Contacto
```http
POST /api/contacto
Content-Type: application/json

{
  "nombre": "Juan Pérez",
  "email": "juan@example.com",
  "telefono": "3001234567",
  "mensaje": "Me gustaría información..."
}
```

#### Crear Donación Personal
```http
POST /api/donaciones/personas
Content-Type: application/json

{
  "nombre_donante": "María García",
  "email": "maria@example.com",
  "telefono": "3001234567",
  "monto": 50000,
  "tipo_donacion": "unica",
  "mensaje": "Para el abuelo Juan"
}
```

#### Crear Donación Empresarial
```http
POST /api/donaciones/empresas
Content-Type: application/json

{
  "nombre_empresa": "Tech Corp",
  "nit": "900123456-1",
  "contacto_nombre": "Pedro López",
  "contacto_email": "pedro@techcorp.com",
  "contacto_telefono": "3001234567",
  "monto": 500000,
  "tipo_donacion": "mensual"
}
```

#### Crear Pago PayU
```http
POST /api/payu/create-payment
Content-Type: application/json

{
  "monto": 50000,
  "descripcion": "Donación para adopción",
  "email_pagador": "comprador@example.com",
  "nombre_pagador": "Juan Pérez",
  "telefono_pagador": "3001234567"
}
```

---

## 📁 Estructura del Proyecto

```
caminemos-juntos-php/
│
├── 📄 README.md                    # Este archivo
├── 📄 INSTALACION_LOCAL.md         # Guía detallada local
├── 📄 INSTALACION_HOSTINGER.md     # Guía detallada Hostinger
├── 📄 .htaccess                    # Configuración Apache
├── 📄 index.html                   # Página principal
├── 📄 donar.html                   # Página de donaciones
│
├── 📁 api/                         # Backend PHP
│   ├── 📄 .env                     # Configuración (NO versionar)
│   ├── 📄 config.example.env       # Ejemplo de configuración
│   ├── 📄 index.php                # Router principal
│   ├── 📄 .htaccess                # Configuración API
│   │
│   ├── 📁 config/                  # Configuración
│   │   ├── config.php              # Variables de entorno
│   │   └── database.php            # Conexión MySQL
│   │
│   ├── 📁 controllers/             # Controladores
│   │   ├── AbuelosController.php
│   │   ├── ContactoController.php
│   │   ├── DonacionesController.php
│   │   └── PayUController.php
│   │
│   ├── 📁 services/                # Servicios
│   │   └── PayUService.php         # Integración PayU
│   │
│   └── 📁 helpers/                 # Utilidades
│       ├── Response.php            # Respuestas JSON
│       └── Validator.php           # Validaciones
│
├── 📁 assets/                      # Assets frontend
│   ├── 📁 css/
│   │   └── style.css               # Estilos principales
│   ├── 📁 js/
│   │   ├── api.js                  # Cliente API
│   │   ├── app.js                  # Lógica principal
│   │   └── donacion.js             # Lógica donaciones
│   └── 📁 images/                  # Imágenes
│
├── 📁 donacion/                    # Páginas resultado PayU
│   ├── exito.html                  # Pago exitoso
│   └── error.html                  # Pago fallido
│
└── 📁 database/                    # Scripts SQL
    ├── schema.sql                  # Estructura BD
    └── seed.sql                    # Datos de prueba
```

---

## 🔒 Seguridad

### Buenas Prácticas Implementadas

- ✅ **Validación de entradas**: Todos los datos se validan antes de procesar
- ✅ **Sanitización**: Limpieza de datos con `htmlspecialchars()` y `filter_var()`
- ✅ **PDO Preparado**: Prevención de SQL Injection
- ✅ **Headers de seguridad**: X-Content-Type-Options, X-Frame-Options, etc.
- ✅ **CORS configurado**: Control de acceso entre dominios
- ✅ **Validación de firma PayU**: Verificación de integridad de pagos
- ✅ **Archivo .env**: Credenciales fuera del código fuente

### Recomendaciones Adicionales

1. **No versionar el .env**
   - Agrega `.env` a `.gitignore`
   
2. **Usar HTTPS en producción**
   - Obligatorio para pagos con PayU
   
3. **Actualizar credenciales**
   - Cambia las credenciales de base de datos en producción
   
4. **Monitorear logs**
   - Revisa regularmente los logs de Apache y PHP

---

## 🐛 Troubleshooting

### Error: "No se puede conectar a la base de datos"
**Causa**: Credenciales incorrectas en `.env`  
**Solución**: Verifica `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`

### Error: "Error 500 en la API"
**Causa**: Falta el archivo `.env` o tiene errores de sintaxis  
**Solución**: 
1. Verifica que `.env` existe en `api/`
2. Compara con `config.example.env`

### Error: "PayU devuelve INVALID_SIGNATURE"
**Causa**: Credenciales incorrectas o firma mal calculada  
**Solución**: Verifica `PAYU_API_KEY` y `PAYU_MERCHANT_ID`

### Error: "Las imágenes no cargan"
**Causa**: Rutas incorrectas o permisos  
**Solución**: 
1. Verifica que las imágenes estén en `assets/images/`
2. En Hostinger, verifica permisos (644 para archivos, 755 para carpetas)

### Error: ".htaccess no funciona"
**Causa**: mod_rewrite no habilitado  
**Solución**: 
- **Local**: Habilita mod_rewrite en Laragon
- **Hostinger**: Contacta soporte (usualmente está habilitado)

---

## 📞 Soporte

### Recursos

- **Documentación PayU**: https://developers.payulatam.com/
- **PHP Manual**: https://www.php.net/manual/es/
- **MySQL Docs**: https://dev.mysql.com/doc/

### Contacto

Para soporte del proyecto o dudas sobre implementación, contacta al administrador del sistema.

---

## 📄 Licencia

Este proyecto es propiedad de **Adopta un Abuelo Colombia**.  
Todos los derechos reservados © 2025

---

## 🙏 Créditos

**Desarrollado para**: Fundación Adopta un Abuelo Colombia  
**Tecnologías**: PHP 8, MySQL, JavaScript Vanilla, PayU Latam  
**Entorno de Desarrollo**: Laragon  

---

## 🚀 Próximos Pasos

Después de la instalación:

1. ✅ Verifica que todo funcione correctamente
2. 🎨 Personaliza colores y textos
3. 📸 Agrega fotos reales de los abuelos
4. 💳 Configura PayU en modo producción
5. 📧 Configura correos de notificación
6. 📊 Implementa analytics (Google Analytics)
7. 🔍 Optimiza SEO

---

**¡Gracias por usar nuestro sistema!** 🎉

