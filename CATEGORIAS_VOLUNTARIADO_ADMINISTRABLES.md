# 🎯 Categorías de Voluntariado Administrables

## 📋 Resumen

Se implementó un sistema completo para administrar las categorías de la sección "Únete a Nuestro Equipo" desde el panel de administración.

---

## 🗄️ Base de Datos

### Tabla: `categorias_voluntariado`

```sql
CREATE TABLE categorias_voluntariado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT NOT NULL,
    icono VARCHAR(50) DEFAULT 'fa-hands-helping',
    imagen_url VARCHAR(255),
    caracteristicas JSON,
    orden INT DEFAULT 0,
    destacado TINYINT(1) DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    deleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Categorías Iniciales:

1. **Voluntario**
   - Icono: `fa-hands-helping`
   - Características: Visitas semanales, Acompañamiento personalizado, etc.

2. **Tallerista**
   - Icono: `fa-chalkboard-teacher`
   - Características: Talleres mensuales, Enseñanza de habilidades, etc.

3. **Apadrinamiento** (Destacado)
   - Icono: `fa-heart`
   - Características: Apoyo económico mensual, Seguimiento personalizado, etc.

---

## 🔌 API

### Endpoints Públicos:

#### `GET /api/categorias-voluntariado`
Obtiene todas las categorías activas para mostrar en el frontend.

**Respuesta:**
```json
{
  "success": true,
  "data": {
    "categorias": [
      {
        "id": 1,
        "titulo": "Voluntario",
        "slug": "voluntario",
        "descripcion": "Dedica tu tiempo acompañando...",
        "icono": "fa-hands-helping",
        "imagen_url": "assets/images/voluntario.jpg",
        "caracteristicas": ["Visitas semanales", "Acompañamiento personalizado"],
        "orden": 1,
        "destacado": false
      }
    ],
    "total": 3
  }
}
```

### Endpoints Admin:

#### `GET /api/admin/categorias-voluntariado`
Lista todas las categorías (incluidas inactivas).

#### `POST /api/admin/categorias-voluntariado`
Crea una nueva categoría.

**Body:**
```json
{
  "titulo": "Nueva Categoría",
  "slug": "nueva-categoria",
  "descripcion": "Descripción...",
  "icono": "fa-users",
  "imagen_url": "assets/images/nueva.jpg",
  "caracteristicas": ["Característica 1", "Característica 2"],
  "orden": 4,
  "destacado": 0,
  "activo": 1
}
```

#### `PUT /api/admin/categorias-voluntariado/{id}`
Actualiza una categoría existente.

#### `DELETE /api/admin/categorias-voluntariado/{id}`
Elimina (soft delete) una categoría.

---

## 🖥️ Panel de Administración

### Archivo: `admin/categorias-voluntariado.html`

#### Funcionalidades:

✅ **Listar Categorías**
- Tabla con orden, título, slug, icono, estado
- Badges de "Destacado" y "Activo/Inactivo"
- Vista previa del icono FontAwesome

✅ **Crear/Editar Categoría**
- Modal con formulario completo
- Campos:
  - Título *
  - Slug (URL amigable) *
  - Descripción *
  - Icono (FontAwesome)
  - URL de Imagen
  - Características (una por línea)
  - Orden
  - Destacado (switch)
  - Activo (switch)

✅ **Eliminar Categoría**
- Confirmación con SweetAlert2
- Soft delete (no se elimina físicamente)

✅ **Características**
- Se ingresan como texto (una por línea)
- Se convierten automáticamente a JSON array
- Se muestran como lista con checkmarks en el frontend

---

## 🎨 Frontend

### Archivo: `assets/js/app.js`

#### Función: `loadCategoriasVoluntariado()`

Carga las categorías desde la API y las renderiza dinámicamente:

```javascript
async function loadCategoriasVoluntariado() {
    const response = await api.get('categorias-voluntariado');
    
    if (response.success) {
        const categorias = response.data.categorias;
        // Renderiza cada categoría con:
        // - Imagen alternada (izq/der)
        // - Badge "Recomendado" si está destacado
        // - Icono FontAwesome
        // - Lista de características
    }
}
```

### Características del Renderizado:

1. **Alternancia de Imagen:**
   - Categorías impares: Imagen a la izquierda
   - Categorías pares: Imagen a la derecha

2. **Destacado:**
   - Badge "Recomendado" con corazón
   - Clase CSS especial `volunteer-category-featured-horizontal`

3. **Animaciones AOS:**
   - Delay escalonado (100ms * índice)

4. **Fallback:**
   - Si falla la carga, mantiene el contenido estático HTML

---

## 📂 Archivos Creados/Modificados

### Creados (4):

1. ✅ `database/categorias-voluntariado.sql`
   - Script para crear tabla e insertar datos iniciales

2. ✅ `api/controllers/CategoriasVoluntariadoController.php`
   - Controlador público para el frontend

3. ✅ `admin/categorias-voluntariado.html`
   - Página de administración

4. ✅ `CATEGORIAS_VOLUNTARIADO_ADMINISTRABLES.md`
   - Esta documentación

### Modificados (3):

5. ✅ `api/controllers/AdminController.php`
   - Agregados métodos CRUD para categorías

6. ✅ `api/index.php`
   - Agregadas rutas públicas y admin

7. ✅ `assets/js/app.js`
   - Agregada función `loadCategoriasVoluntariado()`

8. ✅ `index.html`
   - Agregado ID al contenedor: `categorias-voluntariado-container`

---

## 🚀 Cómo Usar

### 1. Ejecutar Script SQL:

```bash
mysql -u root -p caminemos_juntos < database/categorias-voluntariado.sql
```

O desde phpMyAdmin:
- Importar `database/categorias-voluntariado.sql`

### 2. Acceder al Panel Admin:

```
http://localhost/caminemos-juntos-php/admin/categorias-voluntariado.html
```

### 3. Gestionar Categorías:

#### Crear Nueva:
1. Clic en "Nueva Categoría"
2. Completar formulario
3. Agregar características (una por línea)
4. Marcar como "Destacado" si aplica
5. Guardar

#### Editar:
1. Clic en botón "Editar" (lápiz)
2. Modificar campos
3. Guardar

#### Eliminar:
1. Clic en botón "Eliminar" (basura)
2. Confirmar

### 4. Ver en el Frontend:

```
http://localhost/caminemos-juntos-php/index.html#voluntarios
```

Las categorías se cargan automáticamente desde la base de datos.

---

## 🎨 Iconos FontAwesome Sugeridos

```
fa-hands-helping      - Voluntario general
fa-heart              - Apadrinamiento
fa-chalkboard-teacher - Tallerista
fa-user-friends       - Acompañamiento
fa-hand-holding-heart - Donante
fa-users              - Equipo
fa-handshake          - Colaboración
fa-gift               - Donación
```

---

## ✨ Características Especiales

### 1. **Orden Personalizado:**
Campo `orden` permite controlar la secuencia de aparición.

### 2. **Destacado:**
Categorías destacadas tienen:
- Badge "Recomendado"
- Estilo visual especial
- Borde dorado

### 3. **Soft Delete:**
Las categorías eliminadas no se borran físicamente, solo se marcan como `deleted = 1`.

### 4. **JSON Características:**
Las características se guardan como JSON array, permitiendo flexibilidad total.

### 5. **Slug Único:**
Cada categoría tiene un slug único para URLs amigables (futuro).

---

## 🧪 Pruebas

### 1. Crear Categoría:
```
Título: Mentor
Slug: mentor
Descripción: Guía a jóvenes voluntarios
Icono: fa-user-graduate
Características:
- Orientación semanal
- Capacitación continua
- Liderazgo de equipo
Destacado: No
Activo: Sí
```

### 2. Verificar en Frontend:
- Debe aparecer en orden especificado
- Icono correcto
- Características como lista

### 3. Editar:
- Cambiar orden
- Marcar como destacado
- Verificar badge "Recomendado"

### 4. Desactivar:
- Cambiar "Activo" a No
- Verificar que NO aparece en frontend
- Sigue visible en admin

---

## 📊 Ventajas del Sistema

✅ **Flexibilidad Total:**
- Agregar/quitar categorías sin tocar código
- Cambiar textos e imágenes desde admin
- Reordenar fácilmente

✅ **Escalable:**
- Soporta ilimitadas categorías
- JSON permite características dinámicas

✅ **Mantenible:**
- Código limpio y separado
- API RESTful estándar
- Soft delete para auditoría

✅ **User-Friendly:**
- Interfaz intuitiva
- SweetAlert para confirmaciones
- Vista previa de iconos

---

## 🔮 Mejoras Futuras (Opcional)

1. **Upload de Imágenes:**
   - Integrar con `UploadController`
   - Preview en el modal

2. **Drag & Drop:**
   - Reordenar categorías arrastrando

3. **Estadísticas:**
   - Cuántos voluntarios por categoría
   - Gráficos en dashboard

4. **Multiidioma:**
   - Traducciones para cada categoría

---

**✨ Sistema de categorías de voluntariado administrables implementado exitosamente!**  
**Última actualización:** Octubre 2025

