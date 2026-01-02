# 💻 Guía de Instalación Local - Paso a Paso

Esta guía te llevará desde cero hasta tener el proyecto funcionando en tu computadora.

## 📋 Prerrequisitos

Antes de empezar, necesitas:

- ✅ Windows 10/11
- ✅ Conexión a Internet
- ✅ Navegador web moderno (Chrome, Firefox, Edge)
- ✅ Permisos de administrador en tu PC

---

## 🔧 Paso 1: Instalar Laragon

### 1.1 Descargar Laragon

1. Ve a https://laragon.org/download/
2. Descarga **Laragon Full** (incluye PHP, MySQL, Apache)
3. Tamaño aproximado: ~150 MB

### 1.2 Instalar Laragon

1. Ejecuta el instalador descargado
2. Acepta los términos y condiciones
3. Ruta de instalación recomendada: `C:\laragon`
4. Click en "Instalar"
5. Espera a que termine (2-3 minutos)
6. Click en "Finalizar"

### 1.3 Iniciar Laragon

1. Abre Laragon desde el escritorio
2. Click derecho en el ícono de Laragon en la bandeja del sistema
3. Selecciona "Iniciar todo"
4. Espera a que Apache y MySQL se pongan en verde

**✅ Verificación**: Abre http://localhost en tu navegador. Debe aparecer la página de Laragon.

---

## 📦 Paso 2: Copiar el Proyecto

### 2.1 Obtener los Archivos

**Opción A: Si tienes el proyecto en una carpeta**
```
1. Abre C:\laragon\www\
2. Copia la carpeta "caminemos-juntos-php" aquí
```

**Opción B: Si tienes un .zip**
```
1. Extrae el .zip
2. Copia la carpeta resultante a C:\laragon\www\
3. Renómbrala a "caminemos-juntos-php" (sin espacios)
```

### 2.2 Verificar la Estructura

Tu carpeta debe verse así:
```
C:\laragon\www\caminemos-juntos-php\
├── frontend/
│   ├── index.html
│   ├── donar.html
│   ├── admin/
│   └── assets/
├── backend/
├── database/
└── .htaccess
```

**✅ Verificación**: Abre http://localhost/caminemos-juntos-php/ en tu navegador. Debe cargar algo (aunque sin datos aún).

---

## 🗄️ Paso 3: Crear la Base de Datos

### 3.1 Abrir phpMyAdmin

1. Con Laragon corriendo, abre tu navegador
2. Ve a: http://localhost/phpmyadmin
3. Si pide usuario/contraseña:
   - Usuario: `root`
   - Contraseña: (dejar vacío)
4. Click en "Iniciar sesión"

### 3.2 Crear la Base de Datos

1. En phpMyAdmin, click en "Nueva" (en el menú izquierdo)
2. Nombre de la base de datos: `caminemos_juntos`
3. Cotejamiento: `utf8mb4_general_ci`
4. Click en "Crear"

### 3.3 Importar la Estructura (schema.sql)

1. Click en la base de datos `caminemos_juntos` que acabas de crear
2. Ve a la pestaña "Importar" (arriba)
3. Click en "Seleccionar archivo"
4. Navega a: `C:\laragon\www\caminemos-juntos-php\database\schema.sql`
5. Click en "Abrir"
6. Scroll hasta abajo y click en "Continuar"
7. Espera el mensaje de éxito (debe decir algo como "Importación finalizada con éxito")

### 3.4 Importar los Datos de Prueba (seed.sql)

1. Mantente en la base de datos `caminemos_juntos`
2. Ve nuevamente a "Importar"
3. Selecciona el archivo: `C:\laragon\www\caminemos-juntos-php\database\seed.sql`
4. Click en "Continuar"
5. Espera el mensaje de éxito

### 3.5 Verificar los Datos

1. En el menú izquierdo, click en `caminemos_juntos`
2. Debes ver 7 tablas:
   - `abuelos`
   - `cumpleanos`
   - `donaciones_empresas`
   - `donaciones_personas`
   - `mensajes_contacto`
   - `patrocinadores`
   - `transacciones_payu`
3. Click en `abuelos` → Click en "Examinar"
4. Debes ver 12 abuelos listados

**✅ Verificación**: Si ves los 12 abuelos, ¡perfecto!

---

## ⚙️ Paso 4: Configurar el Archivo .env

### 4.1 Navegar a la Carpeta backend

1. Abre el Explorador de Windows
2. Ve a: `C:\laragon\www\caminemos-juntos-php\backend`

### 4.2 Crear el Archivo .env

**Opción A: Copiar desde el ejemplo**
1. Busca el archivo `config.example.env`
2. Click derecho → Copiar
3. Click derecho en un espacio vacío → Pegar
4. Renombra la copia a `.env` (solo punto y env, sin extensión)

**Opción B: Crear manualmente**
1. Click derecho en un espacio vacío
2. Nuevo → Documento de texto
3. Renombra a `.env` (¡importante! debe empezar con punto)
4. Si Windows te advierte sobre cambiar la extensión, confirma "Sí"

### 4.3 Editar el Archivo .env

1. Click derecho en `.env` → Abrir con → Bloc de notas
2. Copia y pega este contenido:

```env
# Configuración de la Aplicación
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost/caminemos-juntos-php

# Configuración de Base de Datos
DB_HOST=localhost
DB_NAME=caminemos_juntos
DB_USER=root
DB_PASSWORD=

# Configuración de PayU (Modo Sandbox - Pruebas)
PAYU_MERCHANT_ID=508029
PAYU_API_KEY=4Vj8eK4rloUd272L48hsrarnUA
PAYU_API_LOGIN=pRRXKOl8ikMmt9u
PAYU_ACCOUNT_ID=512321
PAYU_TEST_MODE=true

# URLs de PayU
PAYU_RESPONSE_URL=http://localhost/caminemos-juntos-php/api/payu/response.php
PAYU_CONFIRMATION_URL=http://localhost/caminemos-juntos-php/api/payu/confirmation.php
```

3. Guarda el archivo (Ctrl + S)
4. Cierra el Bloc de notas

**✅ Verificación**: El archivo `.env` debe estar en `C:\laragon\www\caminemos-juntos-php\api\.env`

---

## 🔍 Paso 5: Verificar que Todo Funcione

### 5.1 Probar la API

1. Abre tu navegador
2. Ve a: http://localhost/caminemos-juntos-php/api/health
3. Debes ver algo como:
```json
{
  "success": true,
  "data": {
    "status": "OK",
    "message": "API funcionando correctamente",
    "timestamp": "2025-10-10T..."
  }
}
```

### 5.2 Probar la Lista de Abuelos

1. Ve a: http://localhost/caminemos-juntos-php/api/abuelos
2. Debes ver un JSON con la lista de 12 abuelos

### 5.3 Probar el Sitio Web

1. Ve a: http://localhost/caminemos-juntos-php/
2. Debes ver:
   - ✅ La página principal con el diseño
   - ✅ La galería de abuelos cargando
   - ✅ Las imágenes visibles
   - ✅ Los botones funcionando

### 5.4 Probar el Formulario de Donación

1. Click en "Donar" o ve a: http://localhost/caminemos-juntos-php/donar.html
2. Llena el formulario con datos de prueba
3. Selecciona "Tarjeta de crédito"
4. Click en "Continuar al pago"
5. Debes ser redirigido a la página de PayU (en modo sandbox)

**Tarjeta de prueba para PayU:**
```
Número: 4097440000000004
CVV: 123
Fecha: 12/2025 (cualquier fecha futura)
Nombre: APPROVED (escribe literalmente "APPROVED")
```

---

## 🐛 Solución de Problemas Comunes

### Problema 1: "No se puede conectar a localhost"

**Síntoma**: La página no carga, error de conexión

**Solución**:
1. Verifica que Laragon esté corriendo
2. En Laragon, verifica que Apache y MySQL estén en verde
3. Si están en rojo, click en "Iniciar todo"
4. Si el problema persiste, reinicia Laragon

---

### Problema 2: "Error 404 - Página no encontrada"

**Síntoma**: Aparece "404 Not Found"

**Solución**:
1. Verifica la URL: debe ser `http://localhost/caminemos-juntos-php/`
2. Verifica que la carpeta esté en `C:\laragon\www\caminemos-juntos-php\`
3. El nombre debe ser exactamente así (sin espacios, con guiones)

---

### Problema 3: "Error 500 - Internal Server Error"

**Síntoma**: Página blanca o error 500

**Solución**:
1. Verifica que el archivo `.env` existe en `api/`
2. Abre el archivo y verifica que no tiene errores de sintaxis
3. Asegúrate de que `DB_PASSWORD` está vacío (sin nada después del `=`)
4. En Laragon, click derecho → Apache → Reiniciar

---

### Problema 4: "La API no devuelve datos"

**Síntoma**: La API responde pero sin abuelos

**Solución**:
1. Verifica en phpMyAdmin que la tabla `abuelos` tiene datos
2. Si está vacía, reimporta `database/seed.sql`
3. Verifica las credenciales de BD en `.env`

---

### Problema 5: "Las imágenes no se ven"

**Síntoma**: Cuadros rotos donde deberían estar las fotos

**Solución**:
1. Verifica que la carpeta `assets/images/` existe
2. Verifica que contiene las imágenes
3. Presiona Ctrl + F5 en el navegador (recarga forzada)

---

### Problema 6: "PayU da error al pagar"

**Síntoma**: Error al intentar hacer un pago de prueba

**Solución**:
1. Verifica que `PAYU_TEST_MODE=true` en `.env`
2. Usa las tarjetas de prueba oficiales de PayU
3. En el campo "Nombre", escribe exactamente "APPROVED" para aprobar o "REJECTED" para rechazar

---

## 🧪 Pruebas Adicionales

### Probar el Formulario de Contacto

1. Ve a la sección de contacto en el sitio
2. Llena el formulario:
   ```
   Nombre: Juan Pérez
   Email: juan@example.com
   Teléfono: 3001234567
   Mensaje: Prueba de contacto
   ```
3. Click en "Enviar mensaje"
4. Verifica en phpMyAdmin → `mensajes_contacto` que se guardó

### Probar la Búsqueda de Abuelos

1. En la página principal, busca el campo de búsqueda
2. Escribe un nombre o ciudad (ej: "María" o "Bogotá")
3. Los resultados deben filtrarse en tiempo real

### Probar los Filtros por Género

1. En la galería, busca los botones de filtro
2. Click en "Hombres" o "Mujeres"
3. La galería debe mostrar solo ese género

---

## 📊 Monitoreo y Logs

### Ver los Datos en la Base de Datos

**Donaciones de personas:**
```sql
SELECT * FROM donaciones_personas ORDER BY fecha_donacion DESC;
```

**Donaciones de empresas:**
```sql
SELECT * FROM donaciones_empresas ORDER BY fecha_donacion DESC;
```

**Transacciones de PayU:**
```sql
SELECT * FROM transacciones_payu ORDER BY fecha_transaccion DESC;
```

**Mensajes de contacto:**
```sql
SELECT * FROM mensajes_contacto ORDER BY fecha_envio DESC;
```

### Ver Logs de Errores

Los logs de PHP y Apache están en:
```
C:\laragon\logs\
```

---

## 🎉 ¡Listo!

Si llegaste hasta aquí y todo funciona, ¡felicitaciones! 🎊

Tu proyecto está completamente funcional en local.

### Siguiente Paso: Subir a Hostinger

Cuando estés listo para poner el sitio en producción, consulta:
- `INSTALACION_HOSTINGER.md` - Guía paso a paso para Hostinger

---

## 📞 ¿Necesitas Ayuda?

Si algo no funciona:
1. Lee nuevamente este documento
2. Revisa la sección de "Solución de Problemas"
3. Consulta el archivo `README.md` para más información técnica
4. Contacta al administrador del proyecto

---

**¡Feliz desarrollo!** 🚀

