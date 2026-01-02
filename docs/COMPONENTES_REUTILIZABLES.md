# 🔧 Componentes Reutilizables

Este documento describe los componentes reutilizables creados para eliminar código duplicado en el proyecto.

## 📍 Ubicación

Todos los componentes están en: `frontend/assets/js/components.js`

---

## 🎯 Componentes Disponibles

### 1. **Header Component** (`loadHeader`)

Genera el navbar/navegación del sitio.

**Uso:**
```javascript
loadHeader('index');  // Para página principal
loadHeader('abuelos'); // Para página de abuelos
loadHeader('donar');   // Para página de donaciones
```

**Características:**
- Se adapta automáticamente según la página
- Usa configuración dinámica desde la API
- Marca la página actual como activa

---

### 2. **Footer Component** (`loadFooter`)

Genera el footer completo del sitio.

**Uso:**
```javascript
loadFooter();
```

**Características:**
- Enlaces rápidos
- Información de contacto
- Redes sociales (desde configuración)
- Copyright y desarrollador

---

### 3. **WhatsApp Button** (`loadWhatsAppButton`)

Genera el botón flotante de WhatsApp.

**Uso:**
```javascript
loadWhatsAppButton();
```

**Características:**
- Número de WhatsApp desde configuración
- Mensaje predefinido personalizable
- Botón flotante con animación

---

### 4. **Global Spinner** (`loadGlobalSpinner`)

Genera un spinner de carga global.

**Uso:**
```javascript
loadGlobalSpinner();

// Mostrar spinner
window.globalSpinner.show('Cargando datos...');

// Ocultar spinner
window.globalSpinner.hide();
```

---

### 5. **Hero Section** (`loadHeroSection`) ⭐ NUEVO

Genera secciones hero reutilizables.

**Uso:**
```javascript
loadHeroSection({
    title: 'Título Principal',
    subtitle: 'Subtítulo descriptivo',
    image: 'assets/images/imagen.jpg',
    imageAlt: 'Texto alternativo',
    buttons: [
        { 
            text: 'Botón 1', 
            href: '#seccion', 
            class: 'btn-primary', 
            icon: 'fas fa-heart' 
        },
        { 
            text: 'Botón 2', 
            href: 'otra-pagina.html', 
            class: 'btn-outline-light', 
            icon: 'fas fa-arrow-right' 
        }
    ],
    minHeight: 500  // Altura mínima en px
});
```

**Ejemplo Real:**
```javascript
loadHeroSection({
    title: 'Nuestros Adultos Mayores',
    subtitle: 'Conoce las historias de adultos mayores que buscan compañía',
    image: 'assets/images/elderly-colombian-woman-happy-flowers.jpg',
    imageAlt: 'Adultos mayores felices',
    buttons: [
        { 
            text: 'Ver Adultos Mayores', 
            href: '#todos-abuelos', 
            class: 'btn-light', 
            icon: 'fas fa-heart' 
        },
        { 
            text: 'Donar Ahora', 
            href: 'donar.html', 
            class: 'btn-outline-light', 
            icon: 'fas fa-hand-holding-heart' 
        }
    ],
    minHeight: 500
});
```

---

### 6. **Head Generator** (`generateHead`) ⭐ NUEVO

Genera el HTML del `<head>` común.

**Uso:**
```javascript
const headHTML = generateHead({
    title: 'Título de la Página',
    description: 'Meta description',
    keywords: 'palabras clave',
    canonical: 'https://caminemosjuntos.org/pagina',
    ogImage: 'https://caminemosjuntos.org/imagen.jpg',
    includeAOS: true,
    includeSweetAlert: true,
    customCSS: '<style>...</style>'
});
```

**Nota:** Esta función está disponible pero se recomienda mantener el `<head>` estático en HTML para mejor SEO.

---

### 7. **Scripts Generator** (`generateScripts`) ⭐ NUEVO

Genera los scripts comunes al final del body.

**Uso:**
```javascript
const scriptsHTML = generateScripts({
    includeBootstrap: true,
    includeAOS: true,
    includeSweetAlert: true,
    includeComponents: true,
    includeAPI: false,
    includeApp: false,
    customScripts: '<script>...</script>'
});
```

**Nota:** Similar a `generateHead`, se recomienda mantener scripts estáticos en HTML.

---

## 🚀 Inicialización Completa

Para inicializar todos los componentes en una página:

```javascript
// Al cargar la página
document.addEventListener('DOMContentLoaded', async () => {
    // Inicializar componentes básicos (header, footer, WhatsApp, spinner)
    await initComponents('abuelos'); // Pasa el nombre de la página
    
    // Cargar hero section si es necesario
    loadHeroSection({
        title: 'Mi Título',
        subtitle: 'Mi subtítulo',
        image: 'assets/images/imagen.jpg',
        imageAlt: 'Descripción'
    });
    
    // Tu código personalizado aquí
});
```

---

## 📝 Estructura HTML Mínima

Para usar los componentes, tu HTML debe tener estos contenedores:

```html
<!DOCTYPE html>
<html lang="es-CO">
<head>
    <!-- Meta tags específicos de la página -->
    <title>Mi Página</title>
    <!-- CDN links (Bootstrap, Font Awesome, etc.) -->
</head>
<body>
    <!-- Header Container -->
    <div id="header-container"></div>
    
    <!-- Hero Section Container (opcional) -->
    <div id="hero-container"></div>
    
    <!-- Tu contenido aquí -->
    
    <!-- Footer Container -->
    <div id="footer-container"></div>
    
    <!-- WhatsApp Button Container -->
    <div id="whatsapp-container"></div>
    
    <!-- Spinner Container -->
    <div id="spinner-container"></div>
    
    <!-- Scripts -->
    <script src="assets/js/components.js"></script>
    <script>
        initComponents('nombre-pagina');
    </script>
</body>
</html>
```

---

## ✅ Archivos Actualizados

Los siguientes archivos ya usan los componentes reutilizables:

- ✅ `frontend/index.html` - Usa header, footer, WhatsApp
- ✅ `frontend/abuelos.html` - Usa header, footer, WhatsApp, **hero section**
- ✅ `frontend/donar.html` - Usa header, footer, WhatsApp, **hero section**

---

## 🔄 Configuración Dinámica

Los componentes cargan configuración desde la API:

```javascript
// Se carga automáticamente en initComponents()
CONFIG = {
    siteName: 'Caminemos Juntos',
    logo: 'assets/images/placeholder-logo.png',
    phone: '+57 321 9951293',
    email: 'contacto@caminemosjuntos.org',
    whatsappNumber: '573219951293',
    // ... más configuración
}
```

Esta configuración se obtiene de: `/api/configuracion`

---

## 📚 Próximos Pasos

Para agregar más componentes reutilizables:

1. Identificar código duplicado
2. Crear función en `components.js`
3. Documentar el uso
4. Actualizar archivos HTML para usar el componente

**Ejemplos de componentes que podrían crearse:**
- Card de abuelo
- Formulario de contacto
- Sección de testimonios
- Galería de imágenes

---

## 🐛 Troubleshooting

### El header no aparece
- Verifica que existe `<div id="header-container"></div>`
- Verifica que `components.js` está cargado
- Verifica que llamaste `initComponents()`

### La hero section no se muestra
- Verifica que existe `<div id="hero-container"></div>`
- Verifica que llamaste `loadHeroSection()` después de `initComponents()`
- Revisa la consola del navegador por errores

### Los estilos no se aplican
- Verifica que `style.css` está cargado
- Verifica que las clases CSS existen
- Revisa que los paths de imágenes son correctos

---

**Última actualización:** 2025-01-27  
**Mantenido por:** Equipo de Desarrollo
