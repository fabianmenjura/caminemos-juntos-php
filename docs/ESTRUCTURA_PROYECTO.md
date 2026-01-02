# 📁 Estructura del Proyecto

## 🎯 Organización Frontend/Backend

El proyecto está organizado en una estructura clara que separa el frontend del backend:

```
caminemos-juntos-php/
├── frontend/                    # 🎨 TODO EL FRONTEND
│   ├── index.html              # Página principal
│   ├── abuelos.html            # Galería de abuelos
│   ├── donar.html              # Página de donaciones
│   ├── donacion/               # Páginas de resultado de donación
│   │   ├── exito.html
│   │   └── error.html
│   ├── admin/                  # Panel de administración
│   │   ├── login.html
│   │   ├── dashboard.html
│   │   ├── [todas las páginas admin]
│   │   └── assets/
│   │       ├── css/admin.css
│   │       └── js/
│   └── assets/                 # Recursos públicos
│       ├── css/style.css
│       ├── js/
│       │   ├── api.js
│       │   ├── app.js
│       │   └── components.js
│       └── images/
│
├── backend/                     # ⚙️ TODO EL BACKEND
│   ├── index.php               # Router API principal
│   ├── .htaccess               # Configuración de rutas API
│   ├── config.example.env      # Plantilla de configuración
│   ├── config/
│   │   ├── config.php
│   │   └── database.php
│   ├── controllers/            # Controladores de la API
│   │   ├── AbuelosController.php
│   │   ├── DonacionesController.php
│   │   ├── AdminController.php
│   │   └── [otros controladores]
│   ├── helpers/                # Funciones auxiliares
│   │   ├── Response.php
│   │   └── [otros helpers]
│   └── services/               # Servicios de negocio
│       ├── AuthService.php
│       └── PayUService.php
│
├── database/                    # Scripts SQL
│   ├── schema.sql
│   ├── seed.sql
│   └── [otros scripts]
│
├── docs/                        # Documentación
│   ├── LEEME_PRIMERO.md
│   ├── DOCUMENTACION_COMPLETA.md
│   └── [otra documentación]
│
├── archive/                     # Archivos de referencia
│
├── logs/                        # Logs del sistema
│
├── .htaccess                    # Configuración principal del servidor
├── README.md                    # Documentación principal
└── sitemap.xml                  # Sitemap para SEO
```

---

## 🔄 Rutas y URLs

### URLs Públicas

- **Sitio principal**: `/` → sirve `frontend/index.html`
- **Páginas públicas**: `/abuelos.html`, `/donar.html` → sirven desde `frontend/`
- **API**: `/api/*` → redirige a `backend/index.php`
- **Admin**: `/admin/*` → sirve desde `frontend/admin/`

### Configuración de .htaccess

El archivo `.htaccess` principal:
1. Redirige `/api/*` a `backend/index.php` (manteniendo la URL `/api/`)
2. Sirve archivos estáticos desde `frontend/`
3. Redirige rutas sin extensión a `frontend/index.html` (SPA)

---

## 📝 Convenciones de Rutas

### En Archivos HTML

- **Assets relativos**: `assets/css/style.css` (desde la raíz del frontend)
- **Assets desde admin**: `../assets/images/logo.png` (subir un nivel)
- **API**: Las llamadas usan `/api/...` (absoluta, manejada por .htaccess)

### En JavaScript

- **API Base URL**: Se detecta automáticamente según la ubicación
  - Si está en `/caminemos-juntos-php/` → `/caminemos-juntos-php/api`
  - Si está en raíz → `/api`
- **Assets**: Rutas relativas según la ubicación del archivo

---

## 🚀 Ventajas de esta Estructura

1. **Separación clara**: Frontend y backend están completamente separados
2. **Mantenibilidad**: Fácil encontrar y modificar archivos
3. **Escalabilidad**: Fácil agregar nuevas funcionalidades
4. **URLs limpias**: Las URLs públicas no reflejan la estructura interna
5. **Compatibilidad**: Mantiene las URLs `/api/` existentes

---

## 🔧 Migración desde Estructura Anterior

Si vienes de la estructura anterior donde todo estaba en la raíz:

- ✅ **No hay cambios en URLs públicas** - Todo sigue funcionando igual
- ✅ **Rutas de API no cambian** - Siguen siendo `/api/...`
- ✅ **Rutas de assets funcionan** - Gracias a .htaccess
- ⚠️ **Rutas internas actualizadas** - Los archivos se movieron físicamente

---

## 📚 Más Información

- Ver [README.md](../README.md) para instalación y uso
- Ver [DOCUMENTACION_COMPLETA.md](DOCUMENTACION_COMPLETA.md) para detalles técnicos
- Ver [INSTALACION_LOCAL.md](INSTALACION_LOCAL.md) para configuración local
