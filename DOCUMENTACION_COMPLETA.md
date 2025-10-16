# 📚 DOCUMENTACIÓN COMPLETA - CAMINEMOS JUNTOS

**Proyecto:** Sistema Web para Hogar Santo Domingo - Chiquinquirá, Boyacá  
**Versión:** 2.0  
**Última actualización:** Octubre 2025  
**Desarrollador:** Fabián Menjura

---

## 📖 ÍNDICE

1. [Resumen Ejecutivo](#resumen-ejecutivo)
2. [Instalación Local](#instalacion-local)
3. [Instalación en Hostinger](#instalacion-hostinger)
4. [Panel de Administración](#panel-administracion)
5. [Arquitectura del Sistema](#arquitectura)
6. [Funcionalidades](#funcionalidades)
7. [Sistema de Notificaciones](#notificaciones)
8. [Sistema de QR Donaciones](#qr-donaciones)
9. [SEO y Optimización](#seo)
10. [Solución de Problemas](#troubleshooting)

---

## 🎯 RESUMEN EJECUTIVO {#resumen-ejecutivo}

### Descripción
Sistema web completo para gestión de abuelos, donaciones, voluntarios y contenido administrable del Hogar Santo Domingo en Chiquinquirá.

### Tecnologías
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla), Bootstrap 5, AOS, SweetAlert2
- **Backend:** PHP 7.4+, MySQL 8.0
- **Servidor:** Apache con mod_rewrite
- **Desarrollo Local:** Laragon
- **Producción:** Hostinger Premium

### Stack Técnico
```
Frontend (Cliente)
├── HTML/CSS/JS Vanilla
├── Bootstrap 5.3
├── FontAwesome 6
├── AOS (Animaciones)
└── SweetAlert2 (Alertas)

Backend (Servidor)
├── PHP 7.4+
├── MySQL 8.0
├── PDO (Database)
└── Apache (.htaccess)

Administración
├── Sistema de autenticación
├── Panel de admin completo
├── Gestión de contenido (CMS)
└── Sistema de notificaciones
```

---

## 💻 INSTALACIÓN LOCAL {#instalacion-local}

### Requisitos Previos
- Laragon (o XAMPP/WAMP)
- PHP 7.4 o superior
- MySQL 8.0 o superior
- Apache con mod_rewrite habilitado

### Pasos de Instalación

#### 1. Clonar el Proyecto
```bash
cd C:\laragon\www
git clone [URL_DEL_REPOSITORIO] caminemos-juntos-php
cd caminemos-juntos-php
```

#### 2. Configurar Base de Datos
```bash
# Abrir phpMyAdmin: http://localhost/phpmyadmin

# Crear base de datos
CREATE DATABASE caminemos_juntos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Usar la base de datos
USE caminemos_juntos;

# Ejecutar scripts en orden:
SOURCE C:/laragon/www/caminemos-juntos-php/database/schema.sql;
SOURCE C:/laragon/www/caminemos-juntos-php/database/seed.sql;
SOURCE C:/laragon/www/caminemos-juntos-php/database/admin-schema.sql;
SOURCE C:/laragon/www/caminemos-juntos-php/database/nuevas-funcionalidades.sql;
SOURCE C:/laragon/www/caminemos-juntos-php/database/add-soft-delete.sql;
SOURCE C:/laragon/www/caminemos-juntos-php/database/hero-slider.sql;
SOURCE C:/laragon/www/caminemos-juntos-php/database/optimizar-edad-cumpleanos.sql;
SOURCE C:/laragon/www/caminemos-juntos-php/database/categorias-voluntariado.sql;
SOURCE C:/laragon/www/caminemos-juntos-php/database/seccion-acerca-de.sql;
SOURCE C:/laragon/www/caminemos-juntos-php/database/configuracion-general.sql;
SOURCE C:/laragon/www/caminemos-juntos-php/database/qr-donaciones.sql;
SOURCE C:/laragon/www/caminemos-juntos-php/database/sistema-notificaciones.sql;
SOURCE C:/laragon/www/caminemos-juntos-php/database/crear-triggers-notificaciones.sql;
```

#### 3. Configurar Variables de Entorno
```bash
# Copiar archivo de ejemplo
cp api/config.example.env api/.env

# Editar api/.env con tus credenciales
DB_HOST=localhost
DB_NAME=caminemos_juntos
DB_USER=root
DB_PASSWORD=

# PayU (Opcional - actualmente deshabilitado)
PAYU_MERCHANT_ID=
PAYU_API_KEY=
PAYU_ACCOUNT_ID=
```

#### 4. Acceder al Sistema

**Frontend:**
```
http://localhost/caminemos-juntos-php/index.html
```

**Admin Panel:**
```
http://localhost/caminemos-juntos-php/admin/login.html

Usuario: admin
Contraseña: Admin123!
```

---

## 🚀 INSTALACIÓN EN HOSTINGER {#instalacion-hostinger}

### Requisitos
- Hosting Premium de Hostinger
- Acceso SSH
- Git instalado

### Pasos

#### 1. Conectar por SSH
```bash
ssh u123456789@yourdomain.com
```

#### 2. Clonar Repositorio
```bash
cd public_html
git clone [URL_REPO] caminemos-juntos-php
cd caminemos-juntos-php
```

#### 3. Configurar Base de Datos
- Crear base de datos desde el panel de Hostinger
- Importar todos los archivos SQL desde phpMyAdmin
- Actualizar `api/.env` con credenciales de producción

#### 4. Configurar .htaccess
El `.htaccess` ya está configurado para detectar automáticamente si está en local o producción.

#### 5. Verificar Permisos
```bash
chmod -R 755 assets/images/uploads
chmod -R 755 logs
```

---

## 👨‍💼 PANEL DE ADMINISTRACIÓN {#panel-administracion}

### Acceso
```
URL: http://tudominio.com/admin/login.html
Usuario: admin
Contraseña: Admin123!
```

### Secciones del Panel (14)

| # | Sección | Descripción |
|---|---------|-------------|
| 1 | 📊 Dashboard | Estadísticas generales |
| 2 | 🔔 Notificaciones | Centro de notificaciones en tiempo real |
| 3 | 👥 Abuelos | CRUD de adultos mayores |
| 4 | 💝 Donaciones | Gestión de donaciones |
| 5 | 📱 QR Donaciones | Administrar códigos QR de pago |
| 6 | ✉️ Mensajes | Mensajes de contacto |
| 7 | 🖼️ Hero Slider | Slider de la página principal |
| 8 | ℹ️ Acerca de | Contenido "Acerca de" |
| 9 | 👫 Voluntarios | Solicitudes de voluntariado |
| 10 | 📋 Categorías Voluntariado | Tipos de voluntariado |
| 11 | 💬 Testimonios | Reseñas de usuarios |
| 12 | 🤝 Patrocinadores | Gestión de sponsors |
| 13 | 🎂 Felicitaciones | Mensajes de cumpleaños |
| 14 | ⚙️ Configuración | Config general del sitio |

### Funcionalidades Principales

#### Upload de Imágenes
- ✅ Optimización automática (resize + compresión)
- ✅ Conversión a JPG
- ✅ Preview antes de guardar
- ✅ Validación de tipo y tamaño

#### Soft Delete
- ✅ No se eliminan registros físicamente
- ✅ Campo `deleted = 1` para marcar como eliminado
- ✅ Auditoría completa

#### Notificaciones en Tiempo Real
- ✅ Campana en topbar con badge
- ✅ Triggers automáticos en MySQL
- ✅ Actualización cada 30 segundos
- ✅ 6 tipos de eventos

---

## 🏗️ ARQUITECTURA DEL SISTEMA {#arquitectura}

### Estructura de Directorios

```
caminemos-juntos-php/
├── index.html              # Página principal
├── abuelos.html           # Galería de abuelos
├── donar.html             # Donaciones con QR
├── admin/                 # Panel de administración
│   ├── login.html
│   ├── dashboard.html
│   ├── [13 páginas más]
│   └── assets/
│       ├── css/admin.css
│       └── js/
│           ├── admin-auth.js
│           ├── admin-api.js
│           └── admin-components.js
├── api/                   # Backend PHP
│   ├── index.php          # Router principal
│   ├── config/            # Configuración
│   ├── controllers/       # 17 controladores
│   ├── helpers/           # Utilidades
│   └── services/          # Servicios
├── assets/                # Recursos públicos
│   ├── css/style.css
│   ├── js/
│   │   ├── api.js
│   │   ├── app.js
│   │   ├── components.js
│   │   └── utils.js
│   └── images/
├── database/              # Scripts SQL
│   ├── schema.sql         # Estructura de tablas
│   ├── seed.sql           # Datos de ejemplo
│   └── [11 scripts más]
├── logs/                  # Archivos de log
└── docs/                  # Documentación (esta carpeta)
```

### Flujo de Datos

```
Usuario → Frontend (HTML/JS)
           ↓
       API Router (api/index.php)
           ↓
       Controlador específico
           ↓
       Base de Datos (MySQL)
           ↓
       Triggers (Notificaciones)
           ↓
       Respuesta JSON
           ↓
       Frontend actualiza UI
```

---

## ✨ FUNCIONALIDADES {#funcionalidades}

### 1. Sistema de Abuelos
- **Frontend:** Galería con filtros y búsqueda
- **Admin:** CRUD completo con upload de fotos
- **Características:**
  - Edad calculada automáticamente desde fecha de nacimiento
  - Estados: Disponible, Adoptado, Inactivo
  - Soft delete
  - Ribbons de cumpleaños

### 2. Sistema de Donaciones con QR
- **Flujo:**
  1. Usuario completa formulario (Persona o Empresa)
  2. Acepta términos y condiciones (obligatorio)
  3. Datos se guardan en BD
  4. Se muestran QR de pago
  5. Usuario escanea y dona
  6. Admin gestiona QR desde el panel

- **Administración de QR:**
  - Upload de códigos QR
  - Información de cuenta
  - Activar/desactivar
  - Ordenamiento

### 3. Sistema de Voluntarios
- **Categorías administrables** desde el panel
- **Formulario público** con dropdown dinámico
- **Gestión en admin:** Ver solicitudes, aprobar, contactar

### 4. Hero Slider
- **Slider administrable** en la página principal
- **Upload de imágenes** con optimización
- **Orden personalizable**
- **Activar/desactivar slides**

### 5. Testimonios y Patrocinadores
- **Testimonios:** Reseñas de usuarios con aprobación
- **Patrocinadores:** Logos con enlaces
- **Destacados:** Opción para resaltar

### 6. Mensajes de Cumpleaños
- **Formulario público** cuando un abuelo cumple años
- **Aprobación en admin** antes de mostrar
- **Notificación automática** al recibir mensaje

### 7. Configuración General
- **Logo y nombre del sitio**
- **Información de contacto**
- **WhatsApp con mensaje predeterminado**
- **Redes sociales**
- **Todo editable desde el admin**

---

## 🔔 SISTEMA DE NOTIFICACIONES {#notificaciones}

### Tipos de Notificaciones

| Evento | Trigger | Icono | Color |
|--------|---------|-------|-------|
| Nueva donación (persona) | Auto | 💝 | Verde |
| Nueva donación (empresa) | Auto | 🏢 | Verde |
| Nuevo mensaje | Auto | ✉️ | Azul |
| Nueva felicitación | Auto | 🎂 | Amarillo |
| Nuevo voluntario | Auto | 👋 | Azul |
| Nuevo testimonio | Auto | ⭐ | Azul |
| Cumpleaños (hoy/próximo) | Manual/CRON | 🎂 | Amarillo |

### Características
- ✅ Campana en topbar de todas las páginas
- ✅ Badge rojo con contador
- ✅ Dropdown con últimas 10 notificaciones
- ✅ Actualización automática cada 30 segundos
- ✅ Click para marcar como leída y redirigir
- ✅ Página completa de notificaciones

### Triggers MySQL
```sql
-- Triggers creados automáticamente:
- notif_nueva_donacion_persona
- notif_nueva_donacion_empresa
- notif_nuevo_mensaje
- notif_nueva_felicitacion
- notif_nuevo_voluntario
- notif_nuevo_testimonio
```

### Generar Notificaciones de Cumpleaños
```sql
-- Ejecutar diariamente (manual o CRON)
CALL generar_notificaciones_cumpleanos();
```

---

## 📱 SISTEMA DE QR DONACIONES {#qr-donaciones}

### Flujo Completo

**Usuario (Frontend):**
1. Entra a `donar.html`
2. Completa formulario (Persona o Empresa):
   - Nombre, Email, Teléfono, Ciudad (obligatorios)
   - Monto, Mensaje (opcionales)
   - ☑ Acepta términos (obligatorio)
3. Click "Continuar a Donación"
4. Ve QR disponibles (Nequi, Bancolombia, etc.)
5. Escanea QR con su app
6. Realiza transferencia

**Admin:**
1. Ir a "QR Donaciones"
2. Click "Nuevo QR"
3. Completar:
   - Título: "Nequi - Hogar Santo Domingo"
   - Descripción: "Escanea con tu app de Nequi"
   - Imagen: Subir QR generado
   - Info Cuenta: Número, titular, etc.
   - Orden: 1, 2, 3...
   - Activo: ✓
4. Guardar
5. QR aparece automáticamente en `donar.html`

### Generar Códigos QR

**Opción 1: Generadores Online**
- https://www.qr-code-generator.com/
- https://www.qr-monkey.com/

**Opción 2: Apps Bancarias**
- Nequi: Mi cuenta → Generar QR
- Bancolombia: Recibir dinero → QR

### Ventajas vs PayU
- ✅ Sin comisiones (PayU cobra 3.5% + IVA)
- ✅ Instantáneo
- ✅ Familiar para colombianos
- ✅ Sin integración compleja

---

## 🎨 COMPONENTES REUTILIZABLES

### Frontend (`assets/js/components.js`)
- **Header:** Navbar con página activa
- **Footer:** Dinámico desde BD
- **WhatsApp Button:** Con mensaje predeterminado
- **Global Spinner:** Loading overlay

### Admin (`admin/assets/js/admin-components.js`)
- **Sidebar:** Menú con 14 opciones
- **Notificaciones:** Campana con dropdown
- **Auth:** Verificación de sesión

### Uso
```javascript
// Frontend
initComponents('nombre-pagina');

// Admin
await initAdminComponents('nombre-pagina');
```

---

## 🗄️ BASE DE DATOS

### Tablas Principales (20)

**Gestión de Abuelos:**
- `abuelos` - Datos de adultos mayores
- `mensajes_cumpleanos` - Felicitaciones

**Donaciones:**
- `donaciones_personas` - Donaciones de particulares
- `donaciones_empresas` - Donaciones corporativas
- `qr_donaciones` - Códigos QR de pago

**Comunicación:**
- `mensajes_contacto` - Formulario de contacto
- `voluntarios` - Solicitudes de voluntariado
- `testimonios` - Reseñas de usuarios

**Contenido Administrable:**
- `hero_slider` - Slider principal
- `categorias_voluntariado` - Tipos de voluntariado
- `seccion_acerca_de` - Título y descripción
- `caracteristicas_acerca_de` - Features
- `patrocinadores` - Sponsors
- `configuracion_general` - Config del sitio
- `redes_sociales` - Links sociales

**Administración:**
- `admin_users` - Usuarios admin
- `admin_sessions` - Sesiones activas
- `admin_logs` - Registro de actividad
- `notificaciones` - Sistema de notificaciones

**Pagos (Legacy):**
- `transacciones_payu` - Transacciones PayU (no usado actualmente)

---

## 🔍 SEO Y OPTIMIZACIÓN {#seo}

### Meta Tags Implementados
- ✅ Title y Description únicos por página
- ✅ Keywords relevantes
- ✅ Open Graph (Facebook, WhatsApp)
- ✅ Schema.org (Organization, DonateAction)
- ✅ Geo Tags (Chiquinquirá, Boyacá)
- ✅ Canonical URLs

### Archivos SEO
- `robots.txt` - Directivas para crawlers
- `sitemap.xml` - Mapa del sitio
- `.htaccess` - URL amigables

### Optimizaciones
- ✅ Imágenes optimizadas (resize + compresión)
- ✅ GZIP compression
- ✅ Browser caching
- ✅ Lazy loading de imágenes
- ✅ AOS para animaciones

---

## 🔧 SOLUCIÓN DE PROBLEMAS {#troubleshooting}

### Error: API retorna 404
**Causa:** Rutas no configuradas correctamente  
**Solución:**
```apache
# Verificar .htaccess
RewriteEngine On
RewriteRule ^api/(.*)$ api/index.php/$1 [L,QSA]
```

### Error: Imágenes no cargan
**Causa:** Rutas absolutas vs relativas  
**Solución:** Usar rutas relativas: `assets/images/foto.jpg`

### Error: Admin no autentica
**Causa:** Headers de Authorization no pasan  
**Solución:**
```apache
# En api/.htaccess
RewriteCond %{HTTP:Authorization} .
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
```

### Error: Notificaciones no aparecen
**Causa:** Triggers no creados  
**Solución:**
```bash
# Ejecutar
php verificar-triggers.php

# Debe mostrar 6 triggers
# Si no, ejecutar crear-triggers-notificaciones.sql
```

### Error: QR no guarda
**Causa:** Validación de campos  
**Solución:** Todos los campos requeridos deben tener valores

---

## 📊 ESTADÍSTICAS DEL PROYECTO

### Líneas de Código
- **Frontend:** ~3,500 líneas (HTML + CSS + JS)
- **Backend:** ~4,000 líneas (PHP)
- **SQL:** ~1,200 líneas
- **Total:** ~8,700 líneas

### Archivos
- **HTML:** 18 páginas
- **PHP:** 22 archivos
- **JavaScript:** 6 archivos
- **CSS:** 2 archivos
- **SQL:** 13 scripts

### Funcionalidades
- **Páginas públicas:** 3 (index, abuelos, donar)
- **Páginas admin:** 15
- **Endpoints API:** 45+
- **Tablas BD:** 20
- **Triggers:** 6

---

## 🎨 PALETA DE COLORES

```css
--primary-color: #eaa33a;        /* Naranja dorado */
--primary-dark: #c8842d;         /* Naranja oscuro */
--primary-light: #f4c16f;        /* Naranja claro */
--secondary-color: #d97638;      /* Naranja terracota */
--accent-color: #8b6f47;         /* Marrón cálido */
--success-color: #7cb342;        /* Verde natural */
```

---

## 📞 CONTACTO Y SOPORTE

**Desarrollador:** Fabián Menjura  
**LinkedIn:** [linkedin.com/in/fabián-esneider-m-05a3501a9](https://www.linkedin.com/in/fabián-esneider-m-05a3501a9)

**Cliente:** Hogar Santo Domingo  
**Ubicación:** Chiquinquirá, Boyacá, Colombia

---

## 📝 CHANGELOG

### v2.0 (Octubre 2025)
- ✅ Migración completa a PHP/HTML/JS
- ✅ Sistema de notificaciones en tiempo real
- ✅ QR de donaciones administrables
- ✅ Componentes reutilizables
- ✅ Buscadores individuales por tabla
- ✅ SweetAlert2 en toda la aplicación
- ✅ Favicon unificado
- ✅ Paleta de colores cálida

### v1.0 (Septiembre 2025)
- Sistema inicial con Vue.js + Express
- Migración a Laravel
- Migración final a PHP puro

---

## 🚀 ROADMAP FUTURO

### Funcionalidades Planeadas
- [ ] Email automático al recibir donaciones
- [ ] Dashboard con gráficas (Chart.js)
- [ ] Exportar donaciones a Excel
- [ ] Certificados de donación en PDF
- [ ] Reactivar PayU para tarjetas (opcional)
- [ ] App móvil (React Native)
- [ ] Sistema de mensajería interna
- [ ] Calendario de eventos

---

## 📄 LICENCIA

Proyecto privado desarrollado para Hogar Santo Domingo.  
Todos los derechos reservados © 2025

---

**✨ Proyecto desarrollado con ❤️ para ayudar a los abuelos de Chiquinquirá**



