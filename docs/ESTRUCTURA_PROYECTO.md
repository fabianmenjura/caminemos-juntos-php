# 📁 ESTRUCTURA DEL PROYECTO

**Última actualización:** Octubre 2025

---

## 🎯 ARCHIVOS PRINCIPALES EN LA RAÍZ

```
caminemos-juntos-php/
├── README.md                    # 👈 LEE ESTO PRIMERO
├── .htaccess                    # Configuración Apache
├── .gitignore                   # Archivos a ignorar en Git
├── index.html                   # 🏠 Página principal del sitio
├── abuelos.html                 # 👥 Galería de abuelos
├── donar.html                   # 💝 Página de donaciones con QR
├── robots.txt                   # SEO - Directivas para crawlers
├── sitemap.xml                  # SEO - Mapa del sitio
├── database/                    # Scripts SQL (ver database/README.md)
│   └── INSTALAR_BD_COMPLETA.sql # Script maestro de instalación
├── docs/                        # Documentación completa
│   ├── DOCUMENTACION_COMPLETA.md # Documentación unificada
│   └── ESTRUCTURA_PROYECTO.md   # 📁 Este archivo
└── archive/                     # Archivos de referencia histórica
```

---

## 📂 CARPETAS PRINCIPALES

### `/admin/` - Panel de Administración
```
admin/
├── login.html                   # Inicio de sesión
├── dashboard.html               # Panel principal
├── notificaciones.html          # 🔔 Centro de notificaciones
├── abuelos.html                 # Gestión de abuelos
├── donaciones.html              # Gestión de donaciones
├── qr-donaciones.html           # 📱 Administrar QR
├── mensajes.html                # Mensajes de contacto
├── hero-slider.html             # Slider principal
├── acerca-de.html               # Contenido "Acerca de"
├── voluntarios.html             # Solicitudes de voluntariado
├── categorias-voluntariado.html # Tipos de voluntariado
├── testimonios.html             # Reseñas
├── patrocinadores.html          # Sponsors
├── mensajes-cumpleanos.html     # Felicitaciones
├── configuracion.html           # Settings generales
└── assets/
    ├── css/admin.css            # Estilos del admin
    └── js/
        ├── admin-auth.js        # Autenticación
        ├── admin-api.js         # Cliente API
        └── admin-components.js  # Componentes reutilizables
```

**Total:** 15 páginas HTML + 3 archivos JS

---

### `/api/` - Backend PHP

```
api/
├── index.php                    # 🎯 Router principal
├── .htaccess                    # Reglas de reescritura
├── config.example.env           # Plantilla de configuración
├── config/
│   ├── config.php               # Carga variables .env
│   └── database.php             # Clase Database (Singleton)
├── controllers/                 # 17 controladores
│   ├── AbuelosController.php
│   ├── AdminController.php
│   ├── AuthController.php
│   ├── DonacionesController.php
│   ├── QRDonacionesController.php
│   ├── NotificacionesController.php
│   └── [11 más]
├── helpers/                     # Utilidades
│   ├── Response.php             # Respuestas JSON
│   ├── Validator.php            # Validación de datos
│   ├── Logger.php               # Sistema de logs
│   └── ImageUploader.php        # Procesamiento de imágenes
└── services/
    ├── AuthService.php          # Lógica de autenticación
    └── PayUService.php          # PayU (legacy)
```

**Total:** 1 router + 17 controladores + 4 helpers + 2 services

---

### `/assets/` - Recursos Públicos

```
assets/
├── css/
│   └── style.css                # Estilos principales del sitio
├── js/
│   ├── api.js                   # Cliente API
│   ├── app.js                   # Lógica principal
│   ├── components.js            # Header, Footer, WhatsApp
│   ├── utils.js                 # SweetAlert2, Spinner
│   └── donacion.js              # Lógica de donaciones
└── images/
    ├── placeholder-logo.png     # Logo del sitio
    ├── [imágenes de abuelos]
    └── uploads/                 # 📤 Imágenes subidas (vacía en Git)
```

---

### `/database/` - Scripts SQL

```
database/
├── schema.sql                   # ⭐ Estructura de tablas
├── seed.sql                     # Datos de ejemplo
├── admin-schema.sql             # Sistema de admin
├── nuevas-funcionalidades.sql   # Voluntarios, testimonios
├── add-soft-delete.sql          # Soft delete
├── hero-slider.sql              # Slider
├── optimizar-edad-cumpleanos.sql# Sistema de edad
├── categorias-voluntariado.sql  # Categorías
├── seccion-acerca-de.sql        # Acerca de
├── configuracion-general.sql    # Config del sitio
├── qr-donaciones.sql            # QR codes
├── sistema-notificaciones.sql   # Notificaciones
└── crear-triggers-notificaciones.sql # Triggers
```

**Total:** 13 scripts SQL

**Orden de ejecución:** Usar `database/INSTALAR_BD_COMPLETA.sql` (ver `database/README.md` para detalles)

---

### `/docs/` - Documentación

```
docs/
├── README.md                    # Índice de documentación
├── docs/
│   └── DOCUMENTACION_COMPLETA.md # ⭐ Todo en uno
├── INSTALACION_LOCAL.md
├── INSTALACION_HOSTINGER.md
├── GUIA_PANEL_ADMIN.md
├── QR_DONACIONES_IMPLEMENTADO.md
├── SISTEMA_NOTIFICACIONES.md
├── COMPONENTES_REUTILIZABLES.md
├── PLAN_SEO_COMPLETO.md
├── SOLUCION_PROBLEMAS_COMUNES.md
└── [otros archivos .md]
```

**Total:** ~30 archivos de documentación

---

### `/logs/` - Archivos de Log

```
logs/
├── .gitkeep                     # Mantener carpeta en Git
├── api.log                      # Peticiones a la API
├── error.log                    # Errores generales
├── debug.log                    # Depuración
└── auth.log                     # Autenticación
```

**⚠️ Estos archivos NO se suben a Git (.gitignore)**

---

## 🔑 ARCHIVOS CLAVE

### Frontend
| Archivo | Descripción |
|---------|-------------|
| `index.html` | Página principal (Hero, Acerca de, Abuelos, Voluntarios, Testimonios, Contacto) |
| `abuelos.html` | Galería completa de abuelos con filtros |
| `donar.html` | Formulario + QR de donaciones |
| `assets/js/components.js` | Header, Footer, WhatsApp reutilizables |
| `assets/js/app.js` | Lógica principal del sitio |

### Backend
| Archivo | Descripción |
|---------|-------------|
| `api/index.php` | Router que maneja TODAS las rutas |
| `api/controllers/AdminController.php` | Controlador principal del admin (1200+ líneas) |
| `api/helpers/Response.php` | Estandarización de respuestas JSON |
| `api/helpers/Logger.php` | Sistema de logging estructurado |

### Admin
| Archivo | Descripción |
|---------|-------------|
| `admin/assets/js/admin-components.js` | Sidebar, notificaciones, buscador |
| `admin/assets/js/admin-auth.js` | Manejo de autenticación |
| `admin/assets/css/admin.css` | Estilos completos del admin |

---

## 📊 ESTADÍSTICAS

### Código
- **Líneas de código:** ~8,700
- **Archivos HTML:** 18
- **Archivos PHP:** 22
- **Archivos JS:** 9
- **Archivos SQL:** 13
- **Archivos CSS:** 2

### Base de Datos
- **Tablas:** 20
- **Triggers:** 6
- **Stored Procedures:** 1

### Funcionalidades
- **Páginas públicas:** 3
- **Páginas admin:** 15
- **Endpoints API:** 45+
- **Tipos de notificaciones:** 6

---

## 🗂️ ORGANIZACIÓN POR FUNCIONALIDAD

### Sistema de Abuelos
```
Frontend:
- abuelos.html
- assets/js/app.js (función loadAbuelos)

Backend:
- api/controllers/AbuelosController.php
- api/controllers/AdminController.php (getAbuelos, createAbuelo, updateAbuelo, deleteAbuelo)

Admin:
- admin/abuelos.html

Base de Datos:
- abuelos (tabla)
```

### Sistema de Donaciones
```
Frontend:
- donar.html

Backend:
- api/controllers/DonacionesController.php
- api/controllers/QRDonacionesController.php
- api/controllers/AdminController.php (getDonaciones, updateDonacionEstado)

Admin:
- admin/donaciones.html
- admin/qr-donaciones.html

Base de Datos:
- donaciones_personas
- donaciones_empresas
- qr_donaciones
```

### Sistema de Notificaciones
```
Backend:
- api/controllers/NotificacionesController.php

Admin:
- admin/notificaciones.html
- admin/assets/js/admin-components.js (campana + dropdown)

Base de Datos:
- notificaciones (tabla)
- 6 triggers automáticos
```

---

## 🔄 FLUJO DE TRABAJO RECOMENDADO

### Para Desarrolladores

**1. Setup Inicial:**
```bash
git clone [repo]
mysql < database/INSTALAR_BD_COMPLETA.sql
cp api/config.example.env api/.env
```

**2. Desarrollo:**
```bash
# Hacer cambios
git add .
git commit -m "Descripción"
git push
```

**3. Testing Local:**
```
http://localhost/caminemos-juntos-php/
```

### Para Deployment

**1. Producción (Hostinger):**
```bash
ssh user@server
cd public_html/caminemos-juntos-php
git pull
```

**2. Verificar:**
```
https://tudominio.com/
```

---

## 📝 CONVENCIONES

### Nombres de Archivos
- **HTML:** `nombre-seccion.html` (kebab-case)
- **PHP:** `NombreClase.php` (PascalCase)
- **JS:** `nombre-archivo.js` (kebab-case)
- **SQL:** `descripcion-funcionalidad.sql` (kebab-case)

### Nombres de Funciones
- **PHP:** `camelCase` para métodos
- **JavaScript:** `camelCase` para funciones
- **SQL:** `snake_case` para tablas y columnas

### Commits
```
feat: Nueva funcionalidad
fix: Corrección de bug
docs: Actualización de documentación
style: Cambios de estilo/formato
refactor: Refactorización de código
```

---

## ✅ CHECKLIST DE VERIFICACIÓN

### Instalación Completa
- [ ] Base de datos creada
- [ ] 13 scripts SQL ejecutados
- [ ] 20 tablas creadas
- [ ] 6 triggers verificados
- [ ] Usuario admin creado
- [ ] Frontend carga correctamente
- [ ] Admin panel accesible
- [ ] Notificaciones funcionando

### Antes de Desplegar
- [ ] .env configurado con datos de producción
- [ ] Imágenes optimizadas
- [ ] Logs vacíos
- [ ] .gitignore actualizado
- [ ] Documentación al día

---

**📚 Para más información, consulta [DOCUMENTACION_COMPLETA.md](docs/DOCUMENTACION_COMPLETA.md)**

