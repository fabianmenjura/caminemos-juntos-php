# Scripts SQL - Base de Datos

Esta carpeta contiene todos los scripts SQL necesarios para configurar la base de datos del proyecto.

## 🚀 Instalación Rápida

Para una instalación completa, ejecuta en este orden:

1. **`INSTALAR_BD_COMPLETA.sql`** - Script maestro que ejecuta todos los scripts necesarios (RECOMENDADO)

O ejecuta manualmente en este orden:

### Orden de Ejecución Manual

1. **`schema.sql`** - Estructura base de tablas principales
2. **`admin-schema.sql`** - Sistema de administración (usuarios, permisos)
3. **`seed.sql`** - Datos de ejemplo iniciales
4. **`sistema-notificaciones.sql`** - Sistema de notificaciones
5. **`crear-triggers-notificaciones.sql`** - Triggers automáticos para notificaciones
6. **`hero-slider.sql`** - Tabla para slider principal
7. **`seccion-acerca-de.sql`** - Sección "Acerca de"
8. **`add-imagen-acerca-de.sql`** - Campo de imagen para "Acerca de"
9. **`categorias-voluntariado.sql`** - Categorías de voluntariado
10. **`nuevas-funcionalidades.sql`** - Voluntarios, testimonios
11. **`qr-donaciones.sql`** - Sistema de QR para donaciones
12. **`configuracion-general.sql`** - Configuración general del sitio
13. **`seed-patrocinadores.sql`** - Datos de ejemplo para patrocinadores
14. **`add-soft-delete.sql`** - Agregar soft delete a tablas existentes
15. **`optimizar-edad-cumpleanos.sql`** - Optimizaciones para sistema de cumpleaños
16. **`fix-image-paths.sql`** - Corrección de rutas de imágenes (si es necesario)

## 📋 Descripción de Scripts

### Scripts Principales

#### `INSTALAR_BD_COMPLETA.sql`
**Script maestro** que ejecuta todos los scripts necesarios en el orden correcto.  
**Uso:** Ejecuta este script para una instalación completa de la base de datos.

#### `ejecutar-todos-los-scripts.sql`
Script alternativo que ejecuta todos los scripts.  
**Uso:** Alternativa al script maestro.

#### `schema.sql`
Crea las tablas principales del sistema:
- `abuelos` - Adultos mayores
- `donaciones_personas` - Donaciones de personas
- `donaciones_empresas` - Donaciones de empresas
- `mensajes_contacto` - Mensajes del formulario de contacto
- `transacciones_payu` - Transacciones de PayU

#### `admin-schema.sql`
Sistema de administración:
- `usuarios` - Usuarios del panel admin
- `actividad_admin` - Log de actividad

#### `seed.sql`
Datos de ejemplo:
- 12 abuelos de ejemplo
- Datos de prueba para donaciones
- Mensajes de ejemplo

### Scripts de Funcionalidades

#### `sistema-notificaciones.sql`
Crea la tabla `notificaciones` para el sistema de alertas.

#### `crear-triggers-notificaciones.sql`
Crea 6 triggers automáticos que generan notificaciones cuando:
- Se crea una nueva donación
- Se recibe un nuevo mensaje
- Se registra un nuevo voluntario
- Se envía una felicitación de cumpleaños
- Se recibe un nuevo testimonio
- Se crea un nuevo abuelo

#### `hero-slider.sql`
Crea la tabla `hero_slider` para gestionar el slider principal.

#### `seccion-acerca-de.sql`
Crea tablas para la sección "Acerca de":
- `seccion_acerca_de` - Contenido principal
- `caracteristicas_acerca_de` - Características destacadas

#### `add-imagen-acerca-de.sql`
Agrega el campo `imagen_principal` a la sección "Acerca de".

#### `categorias-voluntariado.sql`
Crea la tabla `categorias_voluntariado` para tipos de voluntariado administrables.

#### `nuevas-funcionalidades.sql`
Crea tablas para nuevas funcionalidades:
- `voluntarios` - Solicitudes de voluntariado
- `testimonios` - Testimonios de usuarios

#### `qr-donaciones.sql`
Crea la tabla `qr_donaciones` para códigos QR de donaciones (Nequi, Bancolombia, Daviplata).

#### `configuracion-general.sql`
Crea la tabla `configuracion_general` para settings del sitio y `redes_sociales` para enlaces.

#### `seed-patrocinadores.sql`
Inserta datos de ejemplo para patrocinadores.

### Scripts de Optimización y Corrección

#### `add-soft-delete.sql`
Agrega el campo `deleted` (soft delete) a tablas que no lo tienen.

#### `optimizar-edad-cumpleanos.sql`
Optimiza el cálculo de edad y sistema de cumpleaños.

#### `fix-image-paths.sql`
Corrige rutas de imágenes si hay problemas de paths.

## 🔄 Actualización de Base de Datos Existente

Si ya tienes una base de datos instalada y quieres agregar nuevas funcionalidades:

1. **Solo nuevas funcionalidades:**
   - Ejecuta solo los scripts específicos que necesites
   - Ejemplo: `nuevas-funcionalidades.sql` para agregar voluntarios y testimonios

2. **Verificar dependencias:**
   - Algunos scripts dependen de otros
   - Revisa los comentarios en cada script

## ⚠️ Notas Importantes

- **Backup:** Siempre haz un backup antes de ejecutar scripts en producción
- **Orden:** Respeta el orden de ejecución para evitar errores
- **Datos existentes:** Los scripts usan `IF NOT EXISTS` para no sobrescribir datos
- **Producción:** Usa `INSTALAR_BD_COMPLETA.sql` solo en instalaciones nuevas

## 📊 Estructura de Tablas

Después de ejecutar todos los scripts, tendrás aproximadamente **20 tablas**:

### Tablas Principales
- `abuelos`
- `donaciones_personas`
- `donaciones_empresas`
- `mensajes_contacto`
- `transacciones_payu`

### Tablas de Administración
- `usuarios`
- `actividad_admin`
- `notificaciones`

### Tablas de Contenido
- `hero_slider`
- `seccion_acerca_de`
- `caracteristicas_acerca_de`
- `categorias_voluntariado`
- `voluntarios`
- `testimonios`
- `patrocinadores`
- `qr_donaciones`
- `configuracion_general`
- `redes_sociales`
- `mensajes_cumpleanos`

## 🆘 Solución de Problemas

### Error: "Table already exists"
- Los scripts usan `CREATE TABLE IF NOT EXISTS`, así que es seguro ejecutarlos múltiples veces
- Si persiste, verifica que la tabla no tenga estructura diferente

### Error: "Unknown column"
- Ejecuta los scripts en el orden indicado
- Algunos scripts dependen de modificaciones previas

### Datos perdidos
- Los scripts de estructura NO eliminan datos
- Los scripts de seed solo insertan si no existen

## 📞 Más Información

Para más detalles sobre la estructura de la base de datos, consulta:
- `docs/DOCUMENTACION_COMPLETA.md` - Sección "Base de Datos"
- `docs/ESTRUCTURA_PROYECTO.md` - Estructura completa del proyecto

