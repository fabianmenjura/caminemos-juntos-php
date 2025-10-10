# 🔧 Solución de Problemas Comunes

## ❌ Problema: "Not Found" al cargar abuelos

### Síntomas:
- La página carga pero no muestra los abuelos
- En la consola del navegador aparece error 404
- Mensaje: "The requested URL was not found on this server"

### Causa:
El archivo `api.js` está configurado con una URL base relativa (`/api`) que no funciona cuando el sitio está en un subdirectorio (ej: `/caminemos-juntos-php/`).

### Solución:

La solución ya está implementada en el código actual. El archivo `api.js` ahora detecta automáticamente la ruta correcta.

**Para verificar que funciona:**

1. Abre la consola del navegador (F12)
2. Busca el mensaje: `API Base URL: /caminemos-juntos-php/api`
3. Si ves ese mensaje, todo está correcto

### Solución Manual (si es necesario):

Si por alguna razón el auto-detect no funciona, puedes forzar la URL base:

**Opción 1: Editar api.js**

```javascript
// En assets/js/api.js, línea 97
const api = new API('/caminemos-juntos-php/api'); // Ruta hardcoded
```

**Opción 2: En cada página HTML**

Agrega esto ANTES de cargar `api.js`:

```html
<script>
  // Configurar base URL manualmente
  window.API_BASE_URL = '/caminemos-juntos-php/api';
</script>
<script src="assets/js/api.js"></script>
```

Luego modifica api.js:

```javascript
constructor(baseURL = null) {
    this.baseURL = baseURL || window.API_BASE_URL || '/api';
}
```

---

## ❌ Problema: Error 500 al llamar la API

### Síntomas:
- Error 500 Internal Server Error
- La API no responde

### Causas posibles:
1. Archivo `.env` faltante o mal configurado
2. Credenciales de base de datos incorrectas
3. Error de sintaxis en PHP

### Solución:

**1. Verificar .env:**
```bash
# Debe existir en: api/.env
# Verifica que tenga:
DB_HOST=localhost
DB_NAME=caminemos_juntos
DB_USER=root
DB_PASSWORD=
```

**2. Probar la API directamente:**
```
http://localhost/caminemos-juntos-php/api/health
```

Debe devolver JSON, no un error HTML.

**3. Ver logs de PHP:**
```
C:\laragon\logs\php_error.log
```

---

## ❌ Problema: CORS Error

### Síntomas:
- Error en consola: "blocked by CORS policy"
- La API responde pero el frontend no puede leer la respuesta

### Solución:

Verifica que `api/index.php` tenga estos headers:

```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

---

## ❌ Problema: Base de datos vacía

### Síntomas:
- La API responde pero no hay abuelos
- Array vacío en la respuesta

### Solución:

**1. Verificar datos en phpMyAdmin:**
```sql
SELECT COUNT(*) FROM abuelos;
```

**2. Si está vacía, importar seed.sql:**
```
phpMyAdmin → Importar → database/seed.sql
```

---

## ❌ Problema: PayU devuelve error

### Síntomas:
- Error al procesar pago
- Firma inválida

### Solución:

**1. Verificar credenciales en .env:**
```env
PAYU_MERCHANT_ID=508029
PAYU_API_KEY=4Vj8eK4rloUd272L48hsrarnUA
PAYU_TEST_MODE=true
```

**2. Usar tarjeta de prueba correcta:**
```
Número: 4097440000000004
CVV: 123
Nombre: APPROVED
```

---

## ❌ Problema: Imágenes no cargan

### Síntomas:
- Cuadros rojos con X
- 404 en las imágenes
- Imágenes de placeholder en lugar de fotos reales

### Solución:

**1. Verificar que las imágenes existen:**
```
assets/images/elderly-colombian-woman-smiling-warm.jpg
assets/images/elderly-colombian-man-smiling-kind.jpg
assets/images/elderly-colombian-woman-happy-flowers.jpg
assets/images/elderly-colombian-man-friendly-wise.jpg
assets/images/elderly-colombian-woman-cooking-happy.jpg
assets/images/elderly-colombian-man-dancing-joyful.jpg
```

**2. Verificar rutas en la base de datos:**

Las rutas deben ser **relativas**, no absolutas:

```sql
-- ✅ CORRECTO
SELECT * FROM abuelos WHERE foto_url LIKE 'assets/images/%';

-- ❌ INCORRECTO
SELECT * FROM abuelos WHERE foto_url LIKE '/%';
```

**3. Corregir rutas en la base de datos:**

Si las rutas están mal, ejecuta:
```bash
# En phpMyAdmin o desde terminal
mysql -u root caminemos_juntos
```

Luego:
```sql
-- Ver rutas actuales
SELECT id, nombre, foto_url FROM abuelos;

-- Corregir si empiezan con /
UPDATE abuelos 
SET foto_url = CONCAT('assets/images/', SUBSTRING(foto_url, 2))
WHERE foto_url LIKE '/%';
```

**4. O usa el script de corrección:**
```
database/fix-image-paths.sql
```

**5. Verificar en el navegador:**

El código del frontend ya tiene un sistema de corrección automática que:
- Detecta si la URL empieza con `/` y la corrige
- Muestra un placeholder si la imagen no existe
- Registra las rutas corregidas en la consola

Abre F12 → Consola y verifica que no haya errores 404.

---

## 🔍 Herramientas de Diagnóstico

### Consola del Navegador (F12)

**Ver errores JavaScript:**
```
Consola → Buscar errores en rojo
```

**Ver llamadas a la API:**
```
Red → Filtrar por XHR → Ver requests y responses
```

### Probar API manualmente

**Health Check:**
```
http://localhost/caminemos-juntos-php/api/health
```

**Listar abuelos:**
```
http://localhost/caminemos-juntos-php/api/abuelos
```

**Buscar:**
```
http://localhost/caminemos-juntos-php/api/abuelos/buscar/maria
```

### Verificar Base de Datos

```sql
-- Ver todas las tablas
SHOW TABLES;

-- Contar abuelos
SELECT COUNT(*) FROM abuelos;

-- Ver primeros 5 abuelos
SELECT * FROM abuelos LIMIT 5;

-- Ver últimas donaciones
SELECT * FROM donaciones_personas ORDER BY fecha_donacion DESC LIMIT 5;
```

---

## 📞 Obtener Ayuda

Si el problema persiste:

1. **Abre la consola del navegador** (F12)
2. **Copia el error completo**
3. **Verifica:**
   - ¿Laragon está corriendo?
   - ¿Apache y MySQL están en verde?
   - ¿La URL es correcta?
4. **Consulta los logs:**
   - `C:\laragon\logs\apache_error.log`
   - `C:\laragon\logs\php_error.log`

---

## ✅ Checklist de Verificación

Cuando algo no funciona, verifica en orden:

- [ ] Laragon está corriendo
- [ ] Apache y MySQL activos (íconos verdes)
- [ ] La URL es correcta (`http://localhost/caminemos-juntos-php/`)
- [ ] El archivo `.env` existe en `api/`
- [ ] Las credenciales de BD son correctas
- [ ] La base de datos `caminemos_juntos` existe
- [ ] Las tablas tienen datos
- [ ] La consola del navegador no muestra errores
- [ ] La API responde: `http://localhost/caminemos-juntos-php/api/health`

---

**Última actualización:** Octubre 10, 2025

