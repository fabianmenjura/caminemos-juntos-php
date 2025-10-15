# 🖼️ Hero Slider - Sistema Implementado

## ✅ **¿Qué se implementó?**

Se ha agregado un **slider dinámico con transiciones** en la sección Hero del index, que muestra múltiples imágenes/mensajes rotando automáticamente. El contenido es **100% administrable** desde el panel de administración.

---

## 🎯 **Características**

### Frontend (Público)
- ✅ Carousel de Bootstrap con transición **fade**
- ✅ Cambio automático cada 5 segundos
- ✅ Controles prev/next
- ✅ Indicadores (puntos) para navegación directa
- ✅ Imágenes de fondo con overlay degradado
- ✅ Texto y botones sobre cada imagen
- ✅ Totalmente responsive

### Backend (Admin)
- ✅ CRUD completo desde panel admin
- ✅ Subir imágenes con sistema de upload existente
- ✅ Orden personalizable
- ✅ Activar/desactivar slides
- ✅ Soft delete (no eliminación física)

---

## 🗄️ **Base de Datos**

### Tabla: `hero_slider`

```sql
CREATE TABLE hero_slider (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    subtitulo TEXT NULL,
    imagen_url VARCHAR(255) NOT NULL,
    orden INT DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    enlace_url VARCHAR(255) NULL,
    deleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Datos iniciales:
- 3 slides con imágenes de abuelos existentes
- Títulos y subtítulos inspiracionales
- Orden: 1, 2, 3

---

## 📡 **API Endpoints**

### Público:
```
GET /api/hero-slider
```
Retorna todos los slides activos ordenados

### Admin (requiere autenticación):
```
GET    /api/admin/hero-slider
POST   /api/admin/hero-slider
PUT    /api/admin/hero-slider/{id}
DELETE /api/admin/hero-slider/{id}
```

---

## 💻 **Archivos Modificados/Creados**

### Creados:
1. ✅ `database/hero-slider.sql` - Script de migración
2. ✅ `api/controllers/HeroSliderController.php` - Controlador público
3. ✅ `HERO_SLIDER_IMPLEMENTADO.md` - Esta documentación

### Modificados:
1. ✅ `index.html` - Hero section actualizado con carousel
2. ✅ `assets/css/style.css` - Estilos del slider
3. ✅ `assets/js/App.js` - Función `loadHeroSlider()`
4. ✅ `api/index.php` - Ruta pública `/api/hero-slider`
5. ✅ `api/controllers/AdminController.php` - Métodos CRUD admin

---

## 🎨 **CSS del Slider**

### Características visuales:
- Imagen de fondo a pantalla completa
- Overlay degradado morado/azul (colores del brand)
- Transición fade suave (0.8s)
- Controles con sombras
- Indicadores circulares con efecto scale
- Texto con text-shadow para legibilidad

---

## 🧪 **Cómo Probar**

### 1. Frontend:
```
http://localhost/caminemos-juntos-php/
```
- Deberías ver el slider con 3 imágenes rotando
- Los controles < > deberían funcionar
- Los puntos indicadores deberían cambiar
- Transición suave entre slides

### 2. API Pública:
```bash
curl http://localhost/caminemos-juntos-php/api/hero-slider
```

Respuesta:
```json
{
    "success": true,
    "data": {
        "slides": [
            {
                "id": 1,
                "titulo": "Regala Compañía y Esperanza",
                "subtitulo": "Miles de adultos mayores...",
                "imagen_url": "assets/images/...",
                "orden": 1,
                "enlace_url": null
            }
        ],
        "total": 3
    }
}
```

### 3. Admin Panel (Próximo paso):
Crear página `admin/hero-slider.html` con:
- Lista de slides
- Formulario crear/editar
- Upload de imágenes
- Cambiar orden
- Activar/desactivar

---

## 🚀 **Próximos Pasos**

### Crear página de administración:

```
admin/hero-slider.html
```

Con funcionalidades:
1. ✅ Ver todos los slides
2. ✅ Crear nuevo slide
3. ✅ Editar slide existente
4. ✅ Subir imagen
5. ✅ Cambiar orden (drag & drop opcional)
6. ✅ Activar/desactivar
7. ✅ Eliminar (soft delete)

---

## 📝 **Ejemplo de Uso Admin**

### Crear nuevo slide:

```javascript
// POST /api/admin/hero-slider
{
    "titulo": "Haz la Diferencia Hoy",
    "subtitulo": "Tu donación cambia vidas",
    "imagen_url": "assets/images/hero-4.jpg",
    "orden": 4,
    "activo": 1,
    "enlace_url": "#donar"
}
```

### Actualizar slide:

```javascript
// PUT /api/admin/hero-slider/1
{
    "titulo": "Nuevo Título",
    "orden": 2,
    "activo": 0
}
```

### Eliminar slide:

```javascript
// DELETE /api/admin/hero-slider/1
// Respuesta: Slide eliminado (soft delete)
```

---

## 🎯 **Ventajas del Sistema**

1. **Dinámico**: Cambiar contenido sin tocar código
2. **Flexible**: Título, subtítulo, imagen, enlace personalizables
3. **Ordenable**: Control total del orden de aparición
4. **Seguro**: Soft delete permite recuperar slides
5. **Escalable**: Agregar infinitos slides
6. **Performance**: Solo carga slides activos

---

## 🔧 **Personalización**

### Cambiar intervalo del slider:

```html
<!-- En index.html, línea 55 -->
<div ... data-bs-interval="5000">
<!-- Cambiar 5000 a ms deseados (3000 = 3 segundos) -->
```

### Cambiar velocidad de transición:

```css
/* En assets/css/style.css */
.carousel-fade .carousel-item {
    transition: opacity 0.8s ease-in-out;
    /* Cambiar 0.8s por duración deseada */
}
```

### Cambiar color del overlay:

```css
/* En assets/css/style.css */
.hero-overlay {
    background: linear-gradient(
        135deg, 
        rgba(102, 126, 234, 0.9) 0%,  /* Color 1 */
        rgba(118, 75, 162, 0.85) 100%  /* Color 2 */
    );
}
```

---

## ✨ **Estado Actual**

- ✅ Base de datos creada
- ✅ API pública funcionando
- ✅ API admin funcionando
- ✅ Frontend con slider funcionando
- ✅ CSS y animaciones aplicadas
- ⏳ Falta: Página de administración (`admin/hero-slider.html`)

---

**🎉 Hero Slider implementado y funcional en el frontend!**

**📝 Siguiente: Crear interfaz de administración para gestionar slides.**

