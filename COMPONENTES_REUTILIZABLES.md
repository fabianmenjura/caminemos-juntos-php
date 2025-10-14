# 🧩 Componentes Reutilizables - Caminemos Juntos

## 📋 Resumen

Se implementó un sistema de componentes reutilizables en JavaScript para evitar la duplicación de código HTML del **Header**, **Footer**, **WhatsApp Button** y **Spinner** en todas las páginas.

---

## 📂 Archivos

### 1. **`assets/js/components.js`** (NUEVO)

Contiene las funciones para generar dinámicamente:
- ✅ Header (con versión especial para index)
- ✅ Footer (con redes sociales y créditos)
- ✅ Botón de WhatsApp
- ✅ Spinner global

---

## 🎯 Cómo Funciona

### Estructura HTML (Antes):

```html
<body>
    <header class="main-header">
        <!-- 30 líneas de HTML repetido -->
    </header>
    
    <!-- Contenido de la página -->
    
    <footer class="main-footer">
        <!-- 50 líneas de HTML repetido -->
    </footer>
    
    <a href="..." class="whatsapp-button">...</a>
</body>
```

### Estructura HTML (Ahora):

```html
<body>
    <!-- Header Container -->
    <div id="header-container"></div>
    
    <!-- Contenido de la página -->
    
    <!-- Footer Container -->
    <div id="footer-container"></div>
    
    <!-- WhatsApp Button Container -->
    <div id="whatsapp-container"></div>
    
    <!-- Spinner Container -->
    <div id="spinner-container"></div>
    
    <script src="assets/js/components.js"></script>
    <script>
        initComponents('nombre-pagina');
    </script>
</body>
```

**Resultado:** El HTML se inyecta dinámicamente al cargar la página.

---

## 🔧 Uso en las Páginas

### `index.html`

```html
<script src="assets/js/components.js"></script>
<script>
    initComponents('index'); // Header especial con botón Donar
</script>
```

### `abuelos.html`

```html
<script src="assets/js/components.js"></script>
<script>
    initComponents('abuelos'); // Marca "Abuelos" como activo
</script>
```

### `donar.html`

```html
<script src="assets/js/components.js"></script>
<script>
    initComponents('donar'); // Marca "Donar" como activo
</script>
```

---

## 🎨 Configuración Centralizada

Todos los datos están en un objeto `CONFIG`:

```javascript
const CONFIG = {
    siteName: 'Caminemos Juntos',
    logo: 'assets/images/placeholder-logo.png',
    phone: '+57 321 9951293',
    email: 'contacto@caminemosjuntos.org',
    whatsappNumber: '573219951293'
};
```

**Ventaja:** Si cambias el teléfono o email, solo lo actualizas aquí y se refleja en todas las páginas.

---

## ✨ Funciones Disponibles

### `initComponents(currentPage)`

Inicializa todos los componentes de una vez:

```javascript
initComponents('index');
// Carga: Header + Footer + WhatsApp + Spinner + AOS
```

### `loadHeader(currentPage)`

Carga el header con navegación activa:

```javascript
loadHeader('abuelos'); 
// Marca "Abuelos" como activo en el menú
```

**Especial para Index:**
- Logo más grande (50x50px)
- Botón "Donar Ahora" en el navbar

**Para otras páginas:**
- Logo normal (40x40px)
- Navegación estándar

### `loadFooter()`

Carga el footer con:
- 4 columnas (Logo + Enlaces + Contacto + Redes Sociales)
- Créditos del desarrollador
- Copyright

### `loadWhatsAppButton()`

Botón flotante de WhatsApp (bottom-right)

### `loadGlobalSpinner()`

Spinner global con helpers:

```javascript
window.globalSpinner.show('Procesando...');
window.globalSpinner.hide();
```

---

## 🔄 Páginas Actualizadas

| Página | Header | Footer | WhatsApp | Spinner | Estado |
|--------|--------|--------|----------|---------|--------|
| `index.html` | ✅ Especial | ✅ | ✅ | ✅ | ✅ |
| `abuelos.html` | ✅ Estándar | ✅ | ✅ | ✅ | ✅ |
| `donar.html` | ✅ Estándar | ✅ | ✅ | - | ✅ |

---

## 📊 Ahorro de Código

### Antes:
```
index.html:   ~900 líneas (con header/footer duplicado)
abuelos.html: ~250 líneas (con header/footer duplicado)
donar.html:   ~310 líneas (con header/footer duplicado)
---
TOTAL: ~1460 líneas con duplicación
```

### Ahora:
```
index.html:   ~920 líneas (usa containers)
abuelos.html: ~150 líneas (usa containers)
donar.html:   ~280 líneas (usa containers)
components.js: ~230 líneas (código reutilizable)
---
TOTAL: ~1580 líneas SIN duplicación
```

**Beneficios:**
- ✅ Mantenimiento centralizado
- ✅ Cambios en un solo lugar
- ✅ Menos propenso a errores
- ✅ Fácil de actualizar contacto/logo/redes

---

## 🎯 Navegación Activa

El parámetro `currentPage` marca automáticamente el enlace activo:

```javascript
initComponents('abuelos');
// Resultado: <a class="nav-link active">Abuelos</a>
```

Valores válidos:
- `'index'` → Inicio
- `'abuelos'` → Abuelos
- `'donar'` → Donar

---

## 🔗 Enlaces del Footer

Los enlaces del footer son relativos y funcionan desde cualquier página:

```javascript
<a href="index.html">Inicio</a>
<a href="index.html#acerca">Acerca de</a>
<a href="abuelos.html">Ver Todos los Abuelos</a>
<a href="donar.html">Donar</a>
<a href="index.html#voluntarios">Voluntariado</a>
<a href="admin/login.html">Panel Admin</a>
```

---

## 🛠️ Modificar el Header/Footer

### Cambiar Teléfono:

```javascript
// En components.js
const CONFIG = {
    phone: '+57 300 1234567', // ← Aquí
    phoneDisplay: '+57 300 1234567',
    whatsappNumber: '573001234567'
};
```

### Cambiar Logo:

```javascript
const CONFIG = {
    logo: 'assets/images/nuevo-logo.png', // ← Aquí
};
```

### Agregar Enlace al Menú:

Edita `loadHeader()` en `components.js`:

```javascript
<li class="nav-item">
    <a class="nav-link" href="nueva-pagina.html">Nueva Sección</a>
</li>
```

---

## ⚠️ Importante

1. **Orden de Scripts:**
   ```html
   <script src="assets/js/components.js"></script> <!-- PRIMERO -->
   <script src="assets/js/api.js"></script>
   <script>
       initComponents('pagina'); <!-- Después de cargar components.js -->
   </script>
   ```

2. **Containers:**
   Los `<div id="...-container">` deben existir en el HTML antes de llamar `initComponents()`.

3. **AOS:**
   `initComponents()` ya inicializa AOS, no es necesario hacerlo manualmente.

---

## 🎉 Ventajas

✅ **DRY (Don't Repeat Yourself):** Header/Footer en un solo lugar  
✅ **Mantenimiento Fácil:** Cambios reflejados en todas las páginas  
✅ **Consistencia:** Mismo diseño en todo el sitio  
✅ **Escalabilidad:** Fácil agregar nuevas páginas  
✅ **Configuración Centralizada:** Email, teléfono, redes en un objeto  

---

## 📝 Próximos Pasos (Opcional)

Si quieres mejorar aún más:

1. **Usar Templates Modernos:**
   - Implementar `<template>` de HTML5
   - O usar un framework ligero como Alpine.js

2. **Lazy Loading:**
   - Cargar componentes solo cuando sean visibles

3. **Service Worker:**
   - Cache de componentes para mejor rendimiento

---

**✨ Componentes reutilizables implementados exitosamente!**  
**Última actualización:** Octubre 2025

