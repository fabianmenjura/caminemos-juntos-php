# 📘 Documentación Completa del Proyecto

**Adopta un Abuelo Colombia**  
Sistema Web de Donaciones y Gestión de Adopciones

---

## 📑 Tabla de Contenidos

1. [Visión General](#visión-general)
2. [Documentación Disponible](#documentación-disponible)
3. [Stack Tecnológico](#stack-tecnológico)
4. [Arquitectura](#arquitectura)
5. [Características Principales](#características-principales)
6. [Base de Datos](#base-de-datos)
7. [API Endpoints](#api-endpoints)
8. [Integración PayU](#integración-payu)
9. [Seguridad](#seguridad)
10. [Mantenimiento](#mantenimiento)

---

## 🎯 Visión General

### Propósito del Proyecto

Sistema web completo para la fundación "Adopta un Abuelo Colombia" que permite:
- Mostrar abuelos disponibles para adopción
- Recibir donaciones de personas naturales y empresas
- Procesar pagos en línea mediante PayU
- Gestionar mensajes de contacto
- Mostrar cumpleaños destacados
- Exhibir patrocinadores

### Estado del Proyecto

✅ **Completado y Funcionando**
- Backend PHP completamente funcional
- Frontend responsive y moderno
- Integración PayU configurada
- Base de datos estructurada
- Documentación completa

---

## 📚 Documentación Disponible

El proyecto incluye los siguientes documentos:

### Guías de Instalación
1. **`README.md`** - Documentación principal y referencia rápida
2. **`INSTALACION_LOCAL.md`** - Guía paso a paso para instalación en Laragon (local)
3. **`INSTALACION_HOSTINGER.md`** - Guía paso a paso para despliegue en Hostinger

### Documentos de Referencia
4. **`PROYECTO_COMPLETO.md`** - Este documento (visión técnica completa)
5. **`api/config.example.env`** - Archivo de configuración de ejemplo

### Orden Recomendado de Lectura

**Para Desarrolladores:**
1. README.md (resumen)
2. INSTALACION_LOCAL.md (setup local)
3. PROYECTO_COMPLETO.md (detalles técnicos)

**Para Deployment:**
1. README.md (resumen)
2. INSTALACION_HOSTINGER.md (despliegue)

**Para Mantenimiento:**
1. Este documento (PROYECTO_COMPLETO.md)
2. Comentarios en el código fuente

---

## 🛠️ Stack Tecnológico

### Backend
- **Lenguaje**: PHP 7.4+
- **Base de Datos**: MySQL 5.7+
- **Servidor Web**: Apache 2.4+
- **Módulos Requeridos**: mod_rewrite, PDO, cURL

### Frontend
- **HTML5**: Estructura semántica
- **CSS3**: Estilos modernos con variables CSS
- **JavaScript Vanilla**: Sin frameworks, código nativo
- **Fetch API**: Para llamadas asíncronas

### Integraciones
- **PayU Latam**: Pasarela de pagos
- **PDO**: Capa de abstracción de base de datos
- **JSON**: Formato de intercambio de datos

### Herramientas de Desarrollo
- **Laragon**: Entorno de desarrollo local
- **phpMyAdmin**: Administración de base de datos
- **Git**: Control de versiones (opcional)

---

## 🏗️ Arquitectura

### Patrón de Diseño

**MVC Simplificado**
```
Modelo (Base de Datos) ← → Controlador (PHP) ← → Vista (HTML/JS)
```

### Flujo de Datos

```
1. Usuario → Frontend (HTML/JS)
2. Frontend → API REST (PHP)
3. API → Controlador (PHP)
4. Controlador → Base de Datos (MySQL)
5. Base de Datos → Controlador (resultados)
6. Controlador → API (JSON response)
7. API → Frontend (datos)
8. Frontend → Usuario (interfaz actualizada)
```

### Estructura de Carpetas Detallada

```
caminemos-juntos-php/
│
├── 📄 index.html                   # Página principal
├── 📄 donar.html                   # Página de donaciones
├── 📄 .htaccess                    # Configuración Apache (raíz)
├── 📄 README.md                    # Documentación principal
├── 📄 INSTALACION_LOCAL.md         # Guía instalación local
├── 📄 INSTALACION_HOSTINGER.md     # Guía instalación Hostinger
├── 📄 PROYECTO_COMPLETO.md         # Este documento
│
├── 📁 api/                         # Backend PHP
│   ├── 📄 index.php                # Router principal de la API
│   ├── 📄 .htaccess                # Configuración Apache (API)
│   ├── 📄 .env                     # Variables de entorno (NO versionar)
│   ├── 📄 config.example.env       # Ejemplo de configuración
│   │
│   ├── 📁 config/                  # Configuración del sistema
│   │   ├── 📄 config.php           # Carga de variables .env
│   │   └── 📄 database.php         # Clase de conexión MySQL (Singleton)
│   │
│   ├── 📁 controllers/             # Lógica de negocio
│   │   ├── 📄 AbuelosController.php        # Gestión de abuelos
│   │   ├── 📄 ContactoController.php       # Mensajes de contacto
│   │   ├── 📄 DonacionesController.php     # Donaciones
│   │   └── 📄 PayUController.php           # Integración PayU
│   │
│   ├── 📁 services/                # Servicios externos
│   │   └── 📄 PayUService.php      # Cliente PayU Latam
│   │
│   └── 📁 helpers/                 # Utilidades
│       ├── 📄 Response.php         # Respuestas JSON estandarizadas
│       └── 📄 Validator.php        # Validación de datos
│
├── 📁 assets/                      # Recursos del frontend
│   ├── 📁 css/
│   │   └── 📄 style.css            # Estilos principales
│   │
│   ├── 📁 js/
│   │   ├── 📄 api.js               # Cliente API (fetch)
│   │   ├── 📄 app.js               # Lógica página principal
│   │   └── 📄 donacion.js          # Lógica de donaciones
│   │
│   └── 📁 images/                  # Imágenes del sitio
│       ├── abuelo-1.jpg
│       ├── abuelo-2.jpg
│       ├── ...
│       └── logo.png
│
├── 📁 donacion/                    # Páginas de resultado PayU
│   ├── 📄 exito.html               # Pago exitoso
│   └── 📄 error.html               # Pago fallido/rechazado
│
└── 📁 database/                    # Scripts de base de datos
    ├── 📄 schema.sql               # Estructura de tablas (DDL)
    └── 📄 seed.sql                 # Datos de prueba (DML)
```

---

## ✨ Características Principales

### 1. Galería de Abuelos

**Funcionalidades:**
- ✅ Listado paginado de abuelos
- ✅ Filtrado por género (Masculino/Femenino/Todos)
- ✅ Búsqueda por nombre o ciudad
- ✅ Tarjetas responsivas con foto y descripción
- ✅ Carga asíncrona con Fetch API

**Archivos involucrados:**
- `index.html` - Vista
- `assets/js/app.js` - Lógica frontend
- `api/controllers/AbuelosController.php` - Backend
- Tabla: `abuelos`

### 2. Sistema de Donaciones

**Tipos de donación:**
- 💰 **Personas Naturales**: Donaciones individuales
- 🏢 **Empresas**: Donaciones corporativas

**Montos predefinidos:**
- $20,000 COP
- $50,000 COP
- $100,000 COP
- Monto personalizado

**Tipos de donación:**
- 🔄 Única (one-time)
- 📅 Mensual (recurrente)

**Archivos involucrados:**
- `donar.html` - Vista
- `assets/js/donacion.js` - Lógica frontend
- `api/controllers/DonacionesController.php` - Backend
- Tablas: `donaciones_personas`, `donaciones_empresas`

### 3. Integración con PayU

**Métodos de pago soportados:**
- 💳 Tarjetas de Crédito (Visa, Mastercard, Amex)
- 🏦 PSE (Débito bancario Colombia)
- 💵 Efectivo (Efecty, Baloto, etc.)

**Flujo del pago:**
```
1. Usuario llena formulario donación
2. Selecciona método de pago
3. Se crea transacción en BD
4. Se redirige a PayU
5. Usuario completa pago en PayU
6. PayU notifica resultado (confirmation URL)
7. PayU redirige usuario (response URL)
8. Se actualiza estado de transacción
9. Se muestra página éxito/error
```

**Archivos involucrados:**
- `api/services/PayUService.php` - Cliente PayU
- `api/controllers/PayUController.php` - Endpoints PayU
- `donacion/exito.html` - Pago exitoso
- `donacion/error.html` - Pago fallido
- Tabla: `transacciones_payu`

### 4. Formulario de Contacto

**Campos:**
- Nombre completo
- Email
- Teléfono
- Mensaje

**Validaciones:**
- Email válido
- Teléfono formato colombiano (10 dígitos)
- Mensaje mínimo 10 caracteres
- Sanitización contra XSS

**Archivos involucrados:**
- Sección en `index.html`
- `assets/js/app.js`
- `api/controllers/ContactoController.php`
- Tabla: `mensajes_contacto`

### 5. Cumpleaños Destacados

Muestra los cumpleaños del mes actual de los abuelos.

**Archivos involucrados:**
- Sección en `index.html`
- `assets/js/app.js`
- `api/controllers/AbuelosController.php`
- Tabla: `cumpleanos`

### 6. Patrocinadores

Muestra logos de empresas patrocinadoras con niveles:
- 🥇 Oro
- 🥈 Plata
- 🥉 Bronce

**Archivos involucrados:**
- Sección en `index.html`
- `assets/js/app.js`
- `api/controllers/AbuelosController.php` (endpoint)
- Tabla: `patrocinadores`

---

## 🗄️ Base de Datos

### Diagrama ER (Texto)

```
abuelos
├── id (PK)
├── nombre
├── edad
├── ciudad
├── descripcion
├── genero
├── foto_url
└── fecha_registro

donaciones_personas
├── id (PK)
├── nombre_donante
├── email
├── telefono
├── monto
├── tipo_donacion
├── mensaje
├── estado
└── fecha_donacion

donaciones_empresas
├── id (PK)
├── nombre_empresa
├── nit
├── contacto_nombre
├── contacto_email
├── contacto_telefono
├── monto
├── tipo_donacion
├── estado
└── fecha_donacion

mensajes_contacto
├── id (PK)
├── nombre
├── email
├── telefono
├── mensaje
├── leido
└── fecha_envio

cumpleanos
├── id (PK)
├── abuelo_id (FK → abuelos)
├── fecha_cumpleanos
├── edad_cumple
└── destacado

patrocinadores
├── id (PK)
├── nombre
├── logo_url
├── sitio_web
├── descripcion
├── nivel (oro/plata/bronce)
└── activo

transacciones_payu
├── id (PK)
├── referencia_codigo
├── tipo_donante (persona/empresa)
├── donacion_id
├── estado_pol
├── response_code
├── reference_sale
├── value
├── tax
├── currency
├── firma
├── description
├── email_buyer
├── transaction_date
└── fecha_transaccion
```

### Scripts SQL

**Ubicación**: `database/`

**schema.sql** - Crea todas las tablas
- 7 tablas con sus relaciones
- Índices para optimizar consultas
- Valores por defecto

**seed.sql** - Datos de prueba
- 12 abuelos ficticios
- 3 patrocinadores de ejemplo
- 5 cumpleaños destacados

### Comandos Útiles

**Exportar base de datos:**
```bash
# Desde phpMyAdmin: Exportar → SQL
# O desde terminal:
mysqldump -u root caminemos_juntos > backup.sql
```

**Importar base de datos:**
```bash
# Desde phpMyAdmin: Importar → Seleccionar archivo
# O desde terminal:
mysql -u root caminemos_juntos < backup.sql
```

**Ver últimas donaciones:**
```sql
SELECT * FROM donaciones_personas ORDER BY fecha_donacion DESC LIMIT 10;
SELECT * FROM donaciones_empresas ORDER BY fecha_donacion DESC LIMIT 10;
```

**Ver mensajes no leídos:**
```sql
SELECT * FROM mensajes_contacto WHERE leido = 0 ORDER BY fecha_envio DESC;
```

**Ver transacciones exitosas:**
```sql
SELECT * FROM transacciones_payu WHERE estado_pol = '4' ORDER BY fecha_transaccion DESC;
```

---

## 🔌 API Endpoints

### Base URL

```
Local: http://localhost/caminemos-juntos-php/api
Producción: https://tudominio.com/api
```

### Endpoints Disponibles

#### 1. Health Check
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
    "timestamp": "2025-10-10T18:00:00-05:00"
  },
  "message": null
}
```

#### 2. Listar Abuelos
```http
GET /api/abuelos?pagina=1&limite=10&genero=M
```

**Parámetros Query:**
- `pagina` (opcional, default: 1)
- `limite` (opcional, default: 10, max: 50)
- `genero` (opcional: 'M', 'F', o vacío para todos)

**Respuesta:**
```json
{
  "success": true,
  "data": {
    "abuelos": [
      {
        "id": 1,
        "nombre": "Juan Pérez",
        "edad": 75,
        "ciudad": "Bogotá",
        "descripcion": "...",
        "genero": "M",
        "foto_url": "/assets/images/abuelo-1.jpg",
        "fecha_registro": "2025-01-15 10:30:00"
      },
      // ...
    ],
    "total": 12,
    "pagina": 1,
    "limite": 10,
    "totalPaginas": 2
  }
}
```

#### 3. Buscar Abuelos
```http
GET /api/abuelos/buscar/{termino}?limite=10
```

**Parámetros:**
- `{termino}`: Texto a buscar en nombre o ciudad
- `limite` (query, opcional, default: 10)

**Ejemplo:**
```
GET /api/abuelos/buscar/maria
GET /api/abuelos/buscar/bogota?limite=5
```

#### 4. Cumpleaños del Mes
```http
GET /api/cumpleanos?limite=5
```

**Parámetros Query:**
- `limite` (opcional, default: 5, max: 20)

**Respuesta:**
```json
{
  "success": true,
  "data": {
    "cumpleanos": [
      {
        "id": 1,
        "nombre": "María González",
        "edad": 78,
        "fecha_cumpleanos": "2025-10-15",
        "edad_cumple": 79
      },
      // ...
    ]
  }
}
```

#### 5. Listar Patrocinadores
```http
GET /api/patrocinadores?nivel=oro
```

**Parámetros Query:**
- `nivel` (opcional: 'oro', 'plata', 'bronce')

**Respuesta:**
```json
{
  "success": true,
  "data": {
    "patrocinadores": [
      {
        "id": 1,
        "nombre": "Empresa ABC",
        "logo_url": "/assets/images/sponsor-1.png",
        "sitio_web": "https://empresa.com",
        "descripcion": "Patrocinador oro",
        "nivel": "oro",
        "activo": 1
      },
      // ...
    ]
  }
}
```

#### 6. Enviar Mensaje de Contacto
```http
POST /api/contacto
Content-Type: application/json

{
  "nombre": "Juan Pérez",
  "email": "juan@example.com",
  "telefono": "3001234567",
  "mensaje": "Me gustaría más información sobre el programa."
}
```

**Validaciones:**
- `nombre`: requerido, min 3 caracteres
- `email`: requerido, formato email válido
- `telefono`: opcional, 10 dígitos si se proporciona
- `mensaje`: requerido, min 10 caracteres

**Respuesta exitosa:**
```json
{
  "success": true,
  "message": "Mensaje enviado exitosamente. Nos pondremos en contacto pronto."
}
```

#### 7. Crear Donación Personal
```http
POST /api/donaciones/personas
Content-Type: application/json

{
  "nombre_donante": "María García",
  "email": "maria@example.com",
  "telefono": "3009876543",
  "monto": 50000,
  "tipo_donacion": "unica",
  "mensaje": "Donación para el abuelo Juan"
}
```

**Validaciones:**
- `nombre_donante`: requerido, min 3 caracteres
- `email`: requerido, formato email válido
- `telefono`: requerido, 10 dígitos
- `monto`: requerido, numérico, min 5000
- `tipo_donacion`: requerido, 'unica' o 'mensual'
- `mensaje`: opcional

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": {
    "id": 123,
    "codigo_referencia": "DON-PERS-123-1728586800"
  },
  "message": "Donación registrada exitosamente"
}
```

#### 8. Crear Donación Empresarial
```http
POST /api/donaciones/empresas
Content-Type: application/json

{
  "nombre_empresa": "Tech Corp SAS",
  "nit": "900123456-1",
  "contacto_nombre": "Pedro López",
  "contacto_email": "pedro@techcorp.com",
  "contacto_telefono": "3001112222",
  "monto": 500000,
  "tipo_donacion": "mensual"
}
```

**Validaciones similares a donación personal**

#### 9. Crear Pago con PayU
```http
POST /api/payu/create-payment
Content-Type: application/json

{
  "monto": 50000,
  "descripcion": "Donación adopción abuelo",
  "email_pagador": "pagador@example.com",
  "nombre_pagador": "Juan Pérez",
  "telefono_pagador": "3001234567"
}
```

**Respuesta:**
```json
{
  "success": true,
  "data": {
    "checkout_url": "https://checkout.payulatam.com/ppp-web-gateway-payu/...",
    "reference_code": "REF-1728586800-abc123"
  }
}
```

#### 10. Confirmación de Pago (Webhook PayU)
```http
POST /api/payu/confirmation
Content-Type: application/x-www-form-urlencoded

(PayU envía los datos automáticamente)
```

**Este endpoint es llamado por PayU, no por el frontend**

#### 11. Respuesta de Pago (Redirect PayU)
```http
GET /api/payu/response?referenceCode=...&transactionState=...
```

**Este endpoint es llamado cuando PayU redirige al usuario después del pago**

---

## 💳 Integración PayU

### Configuración

**Variables en `.env`:**
```env
PAYU_MERCHANT_ID=508029
PAYU_API_KEY=4Vj8eK4rloUd272L48hsrarnUA
PAYU_API_LOGIN=pRRXKOl8ikMmt9u
PAYU_ACCOUNT_ID=512321
PAYU_TEST_MODE=true
```

### Credenciales Sandbox (Pruebas)

```
Merchant ID: 508029
API Key: 4Vj8eK4rloUd272L48hsrarnUA
API Login: pRRXKOl8ikMmt9u
Account ID: 512321
```

### Tarjetas de Prueba

**Visa:**
```
Número: 4097440000000004
CVV: 123
Fecha: 12/2026 (cualquier futura)
Nombre: APPROVED
```

**Mastercard:**
```
Número: 5471300000000003
CVV: 123
Fecha: 12/2026
Nombre: APPROVED
```

**Para simular rechazo:**
```
Nombre: REJECTED (en lugar de APPROVED)
```

### Estados de Transacción PayU

| Código | Estado | Descripción |
|--------|--------|-------------|
| 4 | APPROVED | Transacción aprobada |
| 5 | EXPIRED | Transacción expirada |
| 6 | DECLINED | Transacción rechazada |
| 7 | PENDING | Pago pendiente |
| 104 | ERROR | Error en transacción |

### Firma de Seguridad

PayU requiere una firma MD5 para validar transacciones:

```php
// Crear firma (envío)
$firma = md5($apiKey . '~' . $merchantId . '~' . $referenceCode . '~' . $amount . '~' . $currency);

// Validar firma (respuesta)
$firmaEsperada = md5($apiKey . '~' . $merchantId . '~' . $referenceCode . '~' . $amount . '~' . $currency . '~' . $estadoPol);
```

### URLs de PayU

**Sandbox (Pruebas):**
```
Checkout: https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/
API: https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi
```

**Producción:**
```
Checkout: https://checkout.payulatam.com/ppp-web-gateway-payu/
API: https://api.payulatam.com/payments-api/4.0/service.cgi
```

### Flujo Completo del Pago

1. **Usuario inicia donación** → `donar.html`
2. **Frontend crea donación** → `POST /api/donaciones/personas`
3. **Frontend solicita pago** → `POST /api/payu/create-payment`
4. **Backend genera firma y URL** → `PayUService.php`
5. **Frontend redirige a PayU** → `window.location.href = checkout_url`
6. **Usuario completa pago en PayU**
7. **PayU notifica al servidor** → `POST /api/payu/confirmation` (webhook)
8. **Backend valida firma y actualiza BD**
9. **PayU redirige al usuario** → `GET /api/payu/response`
10. **Backend muestra resultado** → `donacion/exito.html` o `donacion/error.html`

---

## 🔒 Seguridad

### Medidas Implementadas

#### 1. Validación de Entradas
```php
// Ejemplo en Validator.php
public static function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}
```

#### 2. Sanitización de Datos
```php
public static function sanitizar($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}
```

#### 3. Prevención de SQL Injection
```php
// Uso de PDO con prepared statements
$stmt = $db->prepare("SELECT * FROM abuelos WHERE id = :id");
$stmt->execute(['id' => $id]);
```

#### 4. Headers de Seguridad
```php
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: no-referrer-when-downgrade');
```

#### 5. CORS Controlado
```php
header('Access-Control-Allow-Origin: *'); // En dev
// En producción, especificar dominio:
header('Access-Control-Allow-Origin: https://tudominio.com');
```

#### 6. Validación de Firma PayU
```php
$firmaRecibida = $_POST['firma'] ?? '';
$firmaCalculada = md5($apiKey . '~' . $merchantId . '~' . ...);

if ($firmaRecibida !== $firmaCalculada) {
    // Transacción fraudulenta, rechazar
}
```

#### 7. Variables de Entorno
```php
// Credenciales nunca en el código
$dbPass = $_ENV['DB_PASSWORD'];
$payuKey = $_ENV['PAYU_API_KEY'];
```

#### 8. Protección de Archivos Sensibles
```apache
# En api/.htaccess
<Files ".env">
  Order allow,deny
  Deny from all
</Files>
```

### Buenas Prácticas

✅ **Hacer:**
- Validar TODOS los datos de entrada
- Usar prepared statements para SQL
- Sanitizar antes de mostrar en HTML
- Verificar firmas de PayU
- Usar HTTPS en producción
- Mantener .env fuera de Git
- Logs de errores (no en producción visibles)

❌ **No Hacer:**
- Confiar en datos del cliente sin validar
- Mostrar errores detallados en producción
- Versionar el archivo .env
- Usar concatenación directa en SQL
- Permitir subida de archivos sin validación
- Exponer información de debug

---

## 🛠️ Mantenimiento

### Tareas Regulares

#### Diarias
- [ ] Revisar nuevas donaciones
- [ ] Responder mensajes de contacto
- [ ] Verificar transacciones PayU

#### Semanales
- [ ] Backup de base de datos
- [ ] Revisar logs de errores
- [ ] Actualizar datos de abuelos si hay cambios

#### Mensuales
- [ ] Backup completo del sitio
- [ ] Revisar y actualizar patrocinadores
- [ ] Analizar estadísticas de donaciones
- [ ] Actualizar cumpleaños destacados

#### Anuales
- [ ] Renovar certificado SSL (si no es auto-renovable)
- [ ] Actualizar PHP y MySQL si es necesario
- [ ] Revisar y actualizar dependencias

### Comandos Útiles

**Backup de Base de Datos:**
```bash
# Exportar
mysqldump -u root -p caminemos_juntos > backup_$(date +%Y%m%d).sql

# Importar
mysql -u root -p caminemos_juntos < backup_20251010.sql
```

**Ver Logs de Apache (Laragon):**
```
C:\laragon\logs\apache_error.log
```

**Limpiar transacciones antiguas:**
```sql
DELETE FROM transacciones_payu 
WHERE fecha_transaccion < DATE_SUB(NOW(), INTERVAL 1 YEAR);
```

**Ver donaciones por mes:**
```sql
SELECT 
  DATE_FORMAT(fecha_donacion, '%Y-%m') as mes,
  COUNT(*) as cantidad,
  SUM(monto) as total
FROM donaciones_personas
GROUP BY mes
ORDER BY mes DESC;
```

---

## 📊 Estadísticas y Reportes

### Consultas Útiles

**Total donado (personas):**
```sql
SELECT SUM(monto) as total FROM donaciones_personas WHERE estado = 'completada';
```

**Total donado (empresas):**
```sql
SELECT SUM(monto) as total FROM donaciones_empresas WHERE estado = 'completada';
```

**Donaciones por ciudad:**
```sql
SELECT a.ciudad, COUNT(*) as cantidad
FROM donaciones_personas dp
JOIN abuelos a ON dp.mensaje LIKE CONCAT('%', a.nombre, '%')
GROUP BY a.ciudad;
```

**Métodos de pago más usados:**
```sql
SELECT 
  CASE 
    WHEN description LIKE '%PSE%' THEN 'PSE'
    WHEN description LIKE '%Tarjeta%' THEN 'Tarjeta'
    ELSE 'Efectivo'
  END as metodo,
  COUNT(*) as cantidad
FROM transacciones_payu
WHERE estado_pol = '4'
GROUP BY metodo;
```

---

## 🚀 Mejoras Futuras

### Corto Plazo
- [ ] Panel de administración web
- [ ] Notificaciones por email
- [ ] Certificados de donación PDF
- [ ] WhatsApp Business API

### Mediano Plazo
- [ ] Sistema de autenticación de usuarios
- [ ] Perfil de donante
- [ ] Historial de donaciones
- [ ] Blog de noticias

### Largo Plazo
- [ ] App móvil (React Native / Flutter)
- [ ] Dashboard de analytics
- [ ] Sistema de voluntariado
- [ ] Integración con redes sociales

---

## 📞 Soporte y Contacto

### Recursos Externos

**PayU Colombia:**
- Web: https://www.payu.com.co/
- Documentación: https://developers.payulatam.com/
- Soporte: soporte@payulatam.com

**Hostinger:**
- Web: https://www.hostinger.com/
- Panel: https://hpanel.hostinger.com/
- Soporte: Chat en vivo 24/7

**PHP:**
- Manual: https://www.php.net/manual/es/
- Tutoriales: https://www.w3schools.com/php/

**MySQL:**
- Documentación: https://dev.mysql.com/doc/
- Tutoriales: https://www.mysqltutorial.org/

---

## 📄 Licencia y Derechos

**Propiedad**: Fundación Adopta un Abuelo Colombia  
**Año**: 2025  
**Todos los derechos reservados**

Este sistema ha sido desarrollado exclusivamente para uso de la fundación.  
Prohibida su reproducción, distribución o uso comercial sin autorización.

---

## 🙏 Agradecimientos

Gracias por usar y mantener este sistema.  
Tu trabajo ayuda a mejorar la vida de muchos abuelos en Colombia.

---

**Última actualización**: Octubre 10, 2025  
**Versión del documento**: 1.0

