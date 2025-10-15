# 📸 Sistema de Upload de Imágenes

## ✨ Características

- ✅ Upload desde el panel de administración
- ✅ **Optimización automática** (redimensiona a max 800x800px)
- ✅ **Compresión** (calidad 85%, ~100KB por imagen)
- ✅ **Conversión** (todas las imágenes se convierten a JPG)
- ✅ Preview antes y después de subir
- ✅ Nombres únicos (evita sobrescritura)
- ✅ Validación de tamaño (máx 5MB antes de optimizar)
- ✅ Validación de tipo (JPG, PNG, GIF, WEBP)

---

## 🎯 Cómo Usar

### Desde el Panel Admin:

1. Ve a **Abuelos** → Click en "Nuevo Abuelo" o edita uno existente
2. En el formulario, verás la sección "Foto del Abuelo"
3. Click en "Seleccionar archivo" o arrastra una imagen
4. Verás un **preview inmediato** de la imagen
5. Click en el botón **"Subir"**
6. Espera unos segundos (se está optimizando)
7. ✅ Listo! La ruta se guardará automáticamente
8. Continúa llenando el resto del formulario
9. Click en "Guardar"

---

## 🔧 Especificaciones Técnicas

### Optimización Automática

**Entrada (lo que subes):**
- Formatos: JPG, PNG, GIF, WEBP
- Tamaño: Hasta 5MB
- Dimensiones: Cualquiera

**Salida (lo que se guarda):**
- Formato: **JPG** (siempre)
- Tamaño: **~50-150KB** (dependiendo de la complejidad)
- Dimensiones: **Max 800x800px** (mantiene proporción)
- Calidad: **85%** (balance perfecto calidad/peso)

### Ejemplo:

```
Original:  foto-abuelo.png (2.5MB, 3000x4000px)
          ↓
Optimizada: abuelo-1728586800-a1b2c3d4.jpg (98KB, 600x800px)
```

---

## 📁 Ubicación de las Imágenes

```
Ruta física: /assets/images/
Formato nombre: abuelo-[timestamp]-[random].jpg
Ejemplo: abuelo-1728586800-a1b2c3d4.jpg
```

**Ruta en BD:**
```
assets/images/abuelo-1728586800-a1b2c3d4.jpg
```

---

## 🛡️ Seguridad

- ✅ Requiere autenticación (solo usuarios logueados)
- ✅ Validación de tipo MIME real (no solo extensión)
- ✅ Nombres aleatorios (evita predecir rutas)
- ✅ Permisos 644 (lectura pública, escritura solo servidor)
- ✅ Tamaño máximo limitado
- ✅ Conversión a JPG (evita exploits en PNG/GIF)

---

## 🐛 Troubleshooting

### "Error al subir imagen"

**Causa:** Permisos de escritura en `/assets/images/`

**Solución:**
```bash
# En el servidor
chmod 755 assets/images/
```

### "La imagen es demasiado grande"

**Causa:** Archivo mayor a 5MB

**Solución:**
1. Usa una imagen más pequeña
2. O comprime antes de subir con: https://tinypng.com/

### "Tipo de archivo no permitido"

**Causa:** No es una imagen válida

**Solución:** Solo sube JPG, PNG, GIF o WEBP

---

## 📊 Logs

Todos los uploads quedan registrados en:

```
logs/app.log - Uploads exitosos
logs/error.log - Errores de upload
```

Ejemplo de log:
```
[2025-10-10 19:30:15] [INFO] POST /api/upload/image
Message: Imagen subida y optimizada
Context: {
    "user_id": 1,
    "filename": "abuelo-1728586800-a1b2c3d4.jpg",
    "original_size": 2500000,
    "optimized_size": 98000,
    "path": "assets/images/abuelo-1728586800-a1b2c3d4.jpg"
}
```

---

## 🎨 Mejores Prácticas

### Fotos Recomendadas:

- ✅ **Formato cuadrado o retrato** (más espacio para descripción)
- ✅ **Buena iluminación**
- ✅ **Fondo neutro**
- ✅ **Rostro visible claramente**
- ✅ **Sonrisa natural**
- ✅ **Calidad mínima:** 800x800px (se optimiza automáticamente)

### No Recomendado:

- ❌ Fotos muy oscuras
- ❌ Fondos muy cargados
- ❌ Muy baja calidad (<300x300px)
- ❌ Fotos borrosas

---

## 🚀 API Endpoint

```http
POST /api/upload/image
Headers: Authorization: Bearer {token}
Content-Type: multipart/form-data
Body: FormData con campo "image"

Response:
{
    "success": true,
    "data": {
        "url": "assets/images/abuelo-1728586800-a1b2c3d4.jpg",
        "filename": "abuelo-1728586800-a1b2c3d4.jpg",
        "size": 98000
    },
    "message": "Imagen subida y optimizada exitosamente"
}
```

---

## ✅ Ventajas del Sistema

1. **Automático:** Solo selecciona y sube, todo lo demás es automático
2. **Rápido:** Optimiza en segundos
3. **Liviano:** Reduce peso en ~90% sin perder calidad visible
4. **Uniforme:** Todas las imágenes en JPG con misma calidad
5. **Seguro:** Validación y sanitización completa

---

**¡El sistema de imágenes está listo!** 

Ahora cuando crees o edites un abuelo:
1. Selecciona una foto
2. Click en "Subir"
3. Se optimiza automáticamente
4. Guarda el abuelo

La imagen pesará ~100KB y se verá perfecta en el sitio. 🎉📸
