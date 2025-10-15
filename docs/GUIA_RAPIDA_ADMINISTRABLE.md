# 🚀 Guía Rápida - Secciones Administrables

## ✅ Checklist de Instalación

### 1. **Ejecutar Scripts SQL** (IMPORTANTE)

Abre **phpMyAdmin** o **consola MySQL** y ejecuta:

```sql
-- Opción A: Ejecutar todo de una vez
USE caminemos_juntos;
SOURCE C:/laragon/www/caminemos-juntos-php/database/categorias-voluntariado.sql;
SOURCE C:/laragon/www/caminemos-juntos-php/database/seccion-acerca-de.sql;
SOURCE C:/laragon/www/caminemos-juntos-php/database/configuracion-general.sql;
```

**O ejecuta cada archivo manualmente:**

#### En phpMyAdmin:
```
1. Seleccionar base de datos "caminemos_juntos"
2. Ir a tab "SQL"
3. Clic "Import" → Seleccionar archivo
4. Importar en este orden:
   - database/categorias-voluntariado.sql
   - database/seccion-acerca-de.sql
   - database/configuracion-general.sql
```

---

### 2. **Verificar que se Crearon las Tablas**

```sql
-- Ver todas las tablas
SHOW TABLES;

-- Deberías ver:
-- categorias_voluntariado
-- seccion_acerca_de
-- caracteristicas_acerca_de
-- configuracion_general
-- redes_sociales
```

---

### 3. **Verificar Datos Iniciales**

```sql
-- Categorías de voluntariado (debe tener 3)
SELECT * FROM categorias_voluntariado;

-- Acerca de (debe tener 2 registros: titulo, descripcion)
SELECT * FROM seccion_acerca_de;

-- Características (debe tener 3: Compañía, Alimentación, Medicamentos)
SELECT * FROM caracteristicas_acerca_de;

-- Configuración general (debe tener ~9 registros)
SELECT * FROM configuracion_general;

-- Redes sociales (debe tener 3: Facebook, Instagram, WhatsApp)
SELECT * FROM redes_sociales;
```

---

## 🎯 Qué es Administrable Ahora

### ✅ **Secciones del Sitio:**

#### 1. **Hero Slider** (Ya existía)
- Imágenes del carousel
- Títulos y subtítulos
- Enlaces

**Admin:** `admin/hero-slider.html`

---

#### 2. **Acerca de** (NUEVO)
- Título: "¿Qué es Caminemos Juntos?"
- Descripción
- Cards/Características (Compañía, Alimentación, Medicamentos)

**Admin:** `admin/acerca-de.html`

**Frontend:** `index.html#acerca`

---

#### 3. **Categorías de Voluntariado** (NUEVO)
- Voluntario
- Tallerista
- Apadrinamiento
- Cualquier categoría nueva

**Admin:** `admin/categorias-voluntariado.html`

**Frontend:** `index.html#voluntarios` (cards + select del formulario)

---

#### 4. **Testimonios** (Ya existía)
- Reseñas de usuarios
- Calificaciones
- Aprobación

**Admin:** `admin/testimonios.html`

---

#### 5. **Patrocinadores** (Ya existía)
- Logos de empresas
- Enlaces

**Admin:** `admin/patrocinadores.html`

---

#### 6. **Configuración General** (NUEVO)

**Admin:** `admin/configuracion.html`

##### Tab 1: General
- ✅ Nombre del sitio
- ✅ Descripción
- ✅ Logo (con upload)

##### Tab 2: Contacto
- ✅ Email
- ✅ Teléfono
- ✅ Dirección
- ✅ **WhatsApp:**
  - Número
  - **Mensaje predeterminado** ← NUEVO

##### Tab 3: Redes Sociales
- ✅ Facebook
- ✅ Instagram
- ✅ WhatsApp
- ✅ **Agregar nuevas** (TikTok, YouTube, LinkedIn, etc.)

---

## 🔄 Flujo de Configuración Dinámica

```
┌─────────────────────────────────────────┐
│ 1. Admin cambia configuración           │
│    - Email: nuevo@email.com             │
│    - WhatsApp: Mensaje personalizado    │
│    - Agrega TikTok a redes sociales     │
└─────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────┐
│ 2. Se guarda en BD                      │
│    - configuracion_general (clave-valor)│
│    - redes_sociales                     │
└─────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────┐
│ 3. Usuario visita index.html            │
│    - components.js carga configuración  │
│    - GET /api/configuracion             │
└─────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────┐
│ 4. Se renderiza dinámicamente           │
│    ✅ Footer: nuevo@email.com           │
│    ✅ Redes: FB, IG, WA, TikTok         │
│    ✅ WhatsApp: Mensaje personalizado   │
└─────────────────────────────────────────┘
```

---

## 🧪 Pruebas Completas

### Test 1: Footer con Email Administrable

```
1. Ejecutar SQL de configuración
2. Admin → Configuración → Contacto
3. Cambiar email a: "hola@caminemosjuntos.org"
4. [Guardar Contacto]
5. Abrir: index.html
6. Scroll al footer
7. Verificar: "hola@caminemosjuntos.org" ✅
```

---

### Test 2: Redes Sociales Administrables

```
1. Admin → Configuración → Redes Sociales
2. Clic "Nueva Red Social"

Nombre: TikTok
Icono: fa-tiktok
URL: https://tiktok.com/@caminemosjuntos
Orden: 4
☑ Activo

[Guardar]

3. Refrescar index.html
4. Scroll al footer → Sección "Síguenos"
5. Verificar: Icono de TikTok aparece ✅
```

---

### Test 3: WhatsApp con Mensaje Predeterminado

```
1. Admin → Configuración → Contacto
2. Mensaje de WhatsApp:
   "¡Hola! Quiero ser voluntario en Caminemos Juntos. ¿Cómo puedo empezar? 😊"
   
3. [Guardar Contacto]
4. Abrir: index.html
5. Clic en botón WhatsApp flotante (abajo derecha)
6. Resultado: WhatsApp abre con mensaje prellenado ✅
```

**Screenshot esperado en WhatsApp:**
```
┌─────────────────────────────────────┐
│ Caminemos Juntos                    │
├─────────────────────────────────────┤
│ ¡Hola! Quiero ser voluntario en     │
│ Caminemos Juntos. ¿Cómo puedo       │
│ empezar? 😊                          │
│                                     │
│ [Enviar]                            │
└─────────────────────────────────────┘
```

---

### Test 4: Logo Administrable

```
1. Admin → Configuración → General
2. Subir nuevo logo (JPG/PNG)
3. Ver preview
4. [Guardar General]
5. Refrescar index.html
6. Verificar:
   ✅ Header: Nuevo logo
   ✅ Footer: Nuevo logo
```

---

## 🐛 Si NO Funciona

### Problema: Footer sigue mostrando datos viejos

**Causa:** Los scripts SQL no se ejecutaron

**Solución:**
```sql
-- Verificar si existen las tablas
SHOW TABLES LIKE 'configuracion_general';

-- Si retorna vacío, ejecutar:
SOURCE database/configuracion-general.sql;
```

---

### Problema: "Error al cargar configuración" en consola

**Causa:** La API no puede conectar a la BD

**Solución:**
```
1. Verificar en consola (F12):
   GET http://localhost/caminemos-juntos-php/api/configuracion

2. Si da 404:
   - Verificar que api/controllers/ConfiguracionController.php existe
   - Verificar que api/index.php tiene las rutas

3. Si da 500:
   - Ver logs/error.log
   - Verificar conexión a BD
```

---

### Problema: WhatsApp no tiene mensaje

**Causa:** El mensaje no se guardó en BD

**Solución:**
```sql
-- Verificar mensaje
SELECT * FROM configuracion_general WHERE clave = 'whatsapp_mensaje';

-- Si está vacío, insertar:
INSERT INTO configuracion_general (clave, valor) 
VALUES ('whatsapp_mensaje', '¡Hola! Me gustaría obtener más información sobre Caminemos Juntos.')
ON DUPLICATE KEY UPDATE valor = '¡Hola! Me gustaría obtener más información sobre Caminemos Juntos.';
```

---

## 📊 Archivos Importantes

### SQL:
- `database/categorias-voluntariado.sql`
- `database/seccion-acerca-de.sql`
- `database/configuracion-general.sql`

### Admin:
- `admin/configuracion.html` ← Gestión de contacto/redes
- `admin/acerca-de.html` ← Gestión de "Acerca de"
- `admin/categorias-voluntariado.html` ← Gestión de voluntariado

### Frontend:
- `assets/js/components.js` ← Header, Footer, WhatsApp dinámicos
- `assets/js/app.js` ← Carga de secciones

---

## ✨ Resultado Final

**Después de ejecutar los SQLs y configurar:**

### Footer (100% Administrable):
```
┌───────────────────────────────────────────────┐
│ Caminemos Juntos Chiquinquirá                 │
│ [Logo dinámico]                               │
│ Descripción dinámica...                       │
│                                               │
│ Enlaces Rápidos    Contacto      Síguenos     │
│ - Inicio           ✉️ Email       📘 Facebook  │
│ - Abuelos          📞 Teléfono    📷 Instagram │
│ - Donar            📍 Dirección   💬 WhatsApp  │
│                                   🎵 TikTok    │
│                                   ▶️ YouTube   │
└───────────────────────────────────────────────┘
```

### WhatsApp Button:
```
[💬] ← Botón flotante
↓
Clic → Abre WhatsApp con:
"¡Hola! Quiero ser voluntario..."
```

---

**🎉 Todo administrable desde el panel!**  
**✨ Ejecuta los SQLs y prueba el sistema completo!**
