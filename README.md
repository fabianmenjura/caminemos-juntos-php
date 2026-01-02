# 🏠 Caminemos Juntos - Hogar Santo Domingo

> Sistema web completo para la gestión y promoción del Hogar Santo Domingo en Chiquinquirá, Boyacá.

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange.svg)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-Private-red.svg)]()

---

## 📋 Descripción

Sistema web para conectar personas generosas con adultos mayores que necesitan compañía, apoyo económico y cariño. Incluye:

- 🏠 **Sitio Web Público** - Información, galería de abuelos, donaciones
- 👨‍💼 **Panel de Administración** - CMS completo para gestionar todo el contenido
- 💝 **Sistema de Donaciones** - QR codes para Nequi, Bancolombia, Daviplata
- 🔔 **Notificaciones en Tiempo Real** - Alertas automáticas para el admin
- 📱 **Responsive** - Funciona en móvil, tablet y desktop

---

## 🚀 Inicio Rápido

### Instalación Local (5 minutos)

```bash
# 1. Clonar repositorio
git clone [URL] caminemos-juntos-php
cd caminemos-juntos-php

# 2. Importar base de datos
mysql -u root caminemos_juntos < database/INSTALAR_BD_COMPLETA.sql
# O alternativamente: database/ejecutar-todos-los-scripts.sql

# 3. Configurar .env
cp backend/config.example.env backend/.env

# 4. Abrir en el navegador
http://localhost/caminemos-juntos-php/
```

### Acceso al Admin

```
URL: http://localhost/caminemos-juntos-php/admin/login.html
Usuario: admin
Contraseña: Admin123!
```

---

## 📁 Estructura del Proyecto

```
caminemos-juntos-php/
├── frontend/          # 🎨 Frontend (HTML, CSS, JS)
│   ├── index.html
│   ├── admin/        # Panel de administración
│   └── assets/       # Recursos estáticos
├── backend/           # ⚙️ Backend (PHP API)
│   ├── controllers/
│   ├── services/
│   └── config/
├── database/          # Scripts SQL
├── docs/              # Documentación
└── logs/              # Logs del sistema
```

**Nota:** Las URLs públicas mantienen `/api/` pero físicamente el código está en `backend/`.

---

## 📚 Documentación

📖 **[Documentación Completa](docs/DOCUMENTACION_COMPLETA.md)** - Todo en un solo lugar

**Guías Específicas:**
- 💻 [Instalación Local](docs/INSTALACION_LOCAL.md)
- 🌐 [Instalación en Hostinger](docs/INSTALACION_HOSTINGER.md)
- 👨‍💼 [Guía del Panel Admin](docs/GUIA_PANEL_ADMIN.md)
- 🔧 [Solución de Problemas](docs/SOLUCION_PROBLEMAS_COMUNES.md)
- 📱 [Sistema de QR Donaciones](docs/QR_DONACIONES_IMPLEMENTADO.md)
- 🔔 [Sistema de Notificaciones](docs/SISTEMA_NOTIFICACIONES.md)

---

## ✨ Características Principales

### 🎯 Funcionalidades Públicas
- ✅ Galería de abuelos con filtros
- ✅ Sistema de donaciones con QR
- ✅ Formulario de contacto
- ✅ Inscripción de voluntarios
- ✅ Testimonios de usuarios
- ✅ Felicitaciones de cumpleaños
- ✅ Hero slider animado
- ✅ Sección "Acerca de" dinámica

### 🛠️ Panel de Administración (14 Secciones)
1. 📊 **Dashboard** - Estadísticas generales
2. 🔔 **Notificaciones** - Alertas en tiempo real
3. 👥 **Abuelos** - CRUD completo + búsqueda
4. 💝 **Donaciones** - Gestión + filtros
5. 📱 **QR Donaciones** - Administrar códigos QR
6. ✉️ **Mensajes** - Formulario de contacto
7. 🖼️ **Hero Slider** - Slider principal
8. ℹ️ **Acerca de** - Contenido de la sección
9. 👫 **Voluntarios** - Solicitudes
10. 📋 **Categorías Voluntariado** - Tipos
11. 💬 **Testimonios** - Aprobar/destacar
12. 🤝 **Patrocinadores** - Gestión de sponsors
13. 🎂 **Felicitaciones** - Mensajes de cumpleaños
14. ⚙️ **Configuración** - Settings generales

### 🔔 Sistema de Notificaciones
- Campana en topbar de todas las páginas
- Badge con contador de notificaciones no leídas
- Triggers automáticos para nuevos eventos:
  - Nueva donación
  - Nuevo mensaje
  - Nuevo voluntario
  - Nueva felicitación
  - Nuevo testimonio
- Actualización automática cada 30 segundos

---

## 🛠️ Stack Tecnológico

### Frontend
- HTML5, CSS3, JavaScript (Vanilla)
- Bootstrap 5.3
- FontAwesome 6
- AOS (Animate On Scroll)
- SweetAlert2

### Backend
- PHP 7.4+
- MySQL 8.0
- Apache + mod_rewrite

### Herramientas
- Laragon (desarrollo)
- Git (control de versiones)
- phpMyAdmin (gestión BD)

---

## 📁 Estructura del Proyecto

```
caminemos-juntos-php/
├── README.md              # Documentación principal
├── .htaccess              # Configuración Apache
├── sitemap.xml            # SEO
├── robots.txt             # SEO
├── index.html             # Página principal
├── abuelos.html           # Galería de abuelos
├── donar.html             # Donaciones con QR
├── donacion/              # Páginas de resultado de donación
├── admin/                 # Panel de administración (15 páginas)
├── api/                   # Backend PHP (17 controladores)
├── assets/                # CSS, JS, imágenes
├── database/              # Scripts SQL (ver database/README.md)
├── docs/                  # Documentación completa
├── archive/               # Archivos de referencia histórica
└── logs/                  # Archivos de log
```

Para más detalles sobre la estructura, consulta [docs/ESTRUCTURA_PROYECTO.md](docs/ESTRUCTURA_PROYECTO.md)

---

## 🔐 Seguridad

- ✅ Autenticación con tokens JWT
- ✅ Sesiones seguras
- ✅ Logs de actividad de admin
- ✅ Sanitización de inputs
- ✅ Prepared statements (PDO)
- ✅ Headers de seguridad
- ✅ HTTPS (producción)

---

## 🤝 Contribuir

Este es un proyecto privado. Para sugerencias o reportar bugs, contactar al desarrollador.

---

## 📄 Licencia

Proyecto privado © 2025 Hogar Santo Domingo  
Desarrollado por Fabián Menjura

---

## 🙏 Agradecimientos

Gracias a todos los que apoyan la labor del Hogar Santo Domingo en Chiquinquirá.

**¡Cada donación cuenta! ¡Cada voluntario hace la diferencia!** ❤️

---

**Hecho con ❤️ para los abuelos de Chiquinquirá** 🇨🇴

