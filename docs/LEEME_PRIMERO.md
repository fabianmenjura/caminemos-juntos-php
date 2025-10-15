# 📚 LÉEME PRIMERO - Índice de Documentación

**Proyecto: Adopta un Abuelo Colombia**  
Sistema Web de Donaciones con PayU

---

## 🎯 ¿Por Dónde Empezar?

### Si eres NUEVO en el proyecto:
1. Lee este documento completo (3 minutos)
2. Sigue la guía apropiada según tu caso

### Si ya conoces el proyecto:
- Ve directamente al documento que necesites (índice abajo)

---

## 📖 Documentación Disponible

### 1. 📄 README.md
**Para quién**: Todos  
**Cuándo leerlo**: Primero, después de este documento  
**Contenido**:
- Resumen del proyecto
- Requisitos del sistema
- Características principales
- Guía rápida de instalación
- API endpoints básicos
- Estructura del proyecto
- FAQ y troubleshooting

**📌 Empieza aquí si**: Necesitas una visión general del proyecto

---

### 2. 💻 INSTALACION_LOCAL.md
**Para quién**: Desarrolladores, testers  
**Cuándo leerlo**: Cuando vayas a instalar en tu PC  
**Contenido**:
- Instalación paso a paso en Laragon
- Creación de base de datos
- Configuración del .env
- Pruebas de funcionamiento
- Solución de problemas comunes
- Guía visual con capturas

**📌 Lee esto si**: Vas a trabajar en el proyecto localmente

**⏱️ Tiempo estimado**: 15-30 minutos

---

### 3. 🌐 INSTALACION_HOSTINGER.md
**Para quién**: Administradores, devops  
**Cuándo leerlo**: Cuando vayas a subir a producción  
**Contenido**:
- Preparación de archivos
- Creación de BD en Hostinger
- Subida de archivos al servidor
- Configuración de .env en producción
- Configuración de SSL
- Configuración de PayU producción
- Verificación completa

**📌 Lee esto si**: Vas a desplegar el sitio en Hostinger

**⏱️ Tiempo estimado**: 30-45 minutos

---

### 4. 📘 PROYECTO_COMPLETO.md
**Para quién**: Desarrolladores avanzados, mantenedores  
**Cuándo leerlo**: Cuando necesites entender a fondo el sistema  
**Contenido**:
- Arquitectura completa del sistema
- Stack tecnológico detallado
- Estructura de carpetas explicada
- Todas las características en detalle
- Esquema de base de datos
- API endpoints completos
- Integración PayU paso a paso
- Medidas de seguridad
- Mantenimiento y mejoras futuras

**📌 Lee esto si**: 
- Necesitas modificar el código
- Vas a agregar nuevas funcionalidades
- Estás haciendo mantenimiento
- Necesitas entender cómo funciona todo

**⏱️ Tiempo estimado**: 1-2 horas (lectura completa)

---

### 5. ⚙️ api/config.example.env
**Para quién**: Todos los que instalen el proyecto  
**Cuándo usarlo**: Al configurar el .env  
**Contenido**:
- Ejemplo de todas las variables de entorno
- Comentarios explicativos
- Valores por defecto
- Credenciales de sandbox PayU
- Tarjetas de prueba

**📌 Usa esto para**: Crear tu archivo .env correctamente

---

## 🗺️ Mapa de Uso por Rol

### 👨‍💻 Eres Desarrollador
```
1. LEEME_PRIMERO.md (este archivo) ✅
2. README.md (visión general)
3. INSTALACION_LOCAL.md (setup)
4. PROYECTO_COMPLETO.md (referencia técnica)
```

### 🚀 Vas a Hacer Deploy
```
1. LEEME_PRIMERO.md (este archivo) ✅
2. README.md (visión general)
3. INSTALACION_HOSTINGER.md (paso a paso)
4. PROYECTO_COMPLETO.md → Sección "Integración PayU" (configurar producción)
```

### 🔧 Haces Mantenimiento
```
1. PROYECTO_COMPLETO.md → Sección "Mantenimiento"
2. README.md → Sección "Troubleshooting"
3. Código fuente (con documentación inline)
```

### 📊 Necesitas Reportes
```
1. PROYECTO_COMPLETO.md → Sección "Estadísticas y Reportes"
2. Acceso directo a phpMyAdmin
```

---

## ⚡ Guías Rápidas

### Instalación Local (Ultra Rápida)

```bash
# 1. Instalar Laragon
# 2. Copiar proyecto a C:\laragon\www\caminemos-juntos-php
# 3. Crear BD "caminemos_juntos" en phpMyAdmin
# 4. Importar database/schema.sql
# 5. Importar database/seed.sql
# 6. Copiar api/config.example.env a api/.env
# 7. Abrir http://localhost/caminemos-juntos-php/
```

**Detalles completos**: `INSTALACION_LOCAL.md`

### Instalación Hostinger (Ultra Rápida)

```bash
# 1. Crear BD en Hostinger y anotar credenciales
# 2. Subir proyecto.zip a public_html
# 3. Extraer y mover archivos a raíz
# 4. Crear api/.env con credenciales reales
# 5. Importar database/schema.sql en phpMyAdmin
# 6. Importar database/seed.sql
# 7. Activar SSL
# 8. Probar https://tudominio.com/
```

**Detalles completos**: `INSTALACION_HOSTINGER.md`

---

## 🎯 Casos de Uso Comunes

### "Necesito agregar un abuelo nuevo"

**Método 1 - phpMyAdmin (rápido):**
```sql
INSERT INTO abuelos (nombre, edad, ciudad, descripcion, genero, foto_url) 
VALUES ('Nombre Completo', 75, 'Bogotá', 'Descripción...', 'M', '/assets/images/abuelo-x.jpg');
```

**Método 2 - Panel Admin (futuro):**
- Proximamente...

**Detalles**: `PROYECTO_COMPLETO.md` → Sección "Base de Datos"

---

### "Quiero cambiar los colores del sitio"

**Archivo**: `assets/css/style.css`

**Líneas a modificar**:
```css
:root {
    --color-primary: #2c5f2d;    /* Verde principal */
    --color-secondary: #97bc62;  /* Verde claro */
    --color-accent: #ff8c42;     /* Naranja */
    --color-dark: #1a1a1a;       /* Gris oscuro */
}
```

**Detalles**: `README.md` → Sección "Personalización"

---

### "Tengo un error y no sé qué hacer"

**1. Identifica el error:**
- Error 500 → Problema con .env o configuración
- Error 404 → Problema con rutas o .htaccess
- Base de datos → Credenciales incorrectas
- PayU → Firma inválida o credenciales

**2. Consulta:**
- `INSTALACION_LOCAL.md` → Sección "Solución de Problemas"
- `INSTALACION_HOSTINGER.md` → Sección "Troubleshooting"
- `README.md` → Sección "Troubleshooting"

**3. Si persiste:**
- Revisa logs: `C:\laragon\logs\` (local) o panel Hostinger
- Verifica configuración paso a paso

---

### "Quiero pasar de pruebas a producción PayU"

**Pasos:**
1. Obtén credenciales reales de PayU: https://www.payu.com.co/
2. Edita `api/.env`:
   ```env
   PAYU_MERCHANT_ID=TU_ID_REAL
   PAYU_API_KEY=TU_KEY_REAL
   PAYU_API_LOGIN=TU_LOGIN_REAL
   PAYU_ACCOUNT_ID=TU_ACCOUNT_REAL
   PAYU_TEST_MODE=false  # ¡IMPORTANTE!
   ```
3. Actualiza las URLs:
   ```env
   PAYU_RESPONSE_URL=https://tudominio.com/api/payu/response.php
   PAYU_CONFIRMATION_URL=https://tudominio.com/api/payu/confirmation.php
   ```
4. Haz una transacción de prueba pequeña

**Detalles**: `INSTALACION_HOSTINGER.md` → "Paso 8: Configuración de Producción PayU"

---

### "Necesito ver las donaciones recibidas"

**Opción 1 - phpMyAdmin:**
```sql
SELECT * FROM donaciones_personas ORDER BY fecha_donacion DESC;
SELECT * FROM donaciones_empresas ORDER BY fecha_donacion DESC;
SELECT * FROM transacciones_payu WHERE estado_pol = '4' ORDER BY fecha_transaccion DESC;
```

**Opción 2 - Panel Admin (futuro):**
- Proximamente...

**Detalles**: `PROYECTO_COMPLETO.md` → Sección "Estadísticas y Reportes"

---

## 🔍 Búsqueda Rápida de Temas

| Necesito información sobre... | Ver documento | Sección |
|-------------------------------|---------------|---------|
| Instalar en mi PC | INSTALACION_LOCAL.md | Todo |
| Subir a Hostinger | INSTALACION_HOSTINGER.md | Todo |
| Cómo funciona la API | PROYECTO_COMPLETO.md | API Endpoints |
| Estructura de carpetas | README.md o PROYECTO_COMPLETO.md | Estructura |
| Base de datos | PROYECTO_COMPLETO.md | Base de Datos |
| PayU configuración | PROYECTO_COMPLETO.md | Integración PayU |
| Problemas comunes | README.md, INSTALACION_*.md | Troubleshooting |
| Seguridad | PROYECTO_COMPLETO.md | Seguridad |
| Mantenimiento | PROYECTO_COMPLETO.md | Mantenimiento |
| Personalizar colores | README.md | Personalización |
| Agregar abuelos | PROYECTO_COMPLETO.md | Base de Datos |
| Ver donaciones | PROYECTO_COMPLETO.md | Estadísticas |

---

## 🆘 Ayuda Rápida

### El sitio no carga (Local)
1. ¿Laragon está corriendo? → Abrir Laragon e "Iniciar todo"
2. ¿La URL es correcta? → `http://localhost/caminemos-juntos-php/`
3. Consulta: `INSTALACION_LOCAL.md` → "Problema 1"

### El sitio no carga (Hostinger)
1. ¿El dominio apunta al hosting? → Panel de Hostinger
2. ¿Los archivos están en public_html? → File Manager
3. ¿El .env está configurado? → Verifica api/.env
4. Consulta: `INSTALACION_HOSTINGER.md` → "Problema 1"

### Error de base de datos
1. Verifica credenciales en `.env`
2. Prueba la conexión en phpMyAdmin
3. Asegúrate de que la BD existe
4. Consulta: Ambas guías de instalación → "Problema 2"

### PayU da error
1. Verifica que las credenciales son correctas
2. Si estás en pruebas, usa `PAYU_TEST_MODE=true`
3. Si estás en producción, usa credenciales reales
4. Consulta: `PROYECTO_COMPLETO.md` → "Integración PayU"

---

## 📞 Recursos Adicionales

### Enlaces Útiles

**PayU Colombia:**
- 🌐 Web: https://www.payu.com.co/
- 📖 Docs: https://developers.payulatam.com/
- 💬 Soporte: soporte@payulatam.com

**Hostinger:**
- 🌐 Panel: https://hpanel.hostinger.com/
- 💬 Chat: Disponible 24/7 en el panel

**Tecnologías:**
- 🐘 PHP: https://www.php.net/manual/es/
- 🗄️ MySQL: https://dev.mysql.com/doc/
- 🌐 JavaScript: https://developer.mozilla.org/es/

---

## ✅ Checklist del Primer Uso

### Para Desarrollo Local:
- [ ] Leí LEEME_PRIMERO.md
- [ ] Leí README.md
- [ ] Instalé Laragon
- [ ] Seguí INSTALACION_LOCAL.md paso a paso
- [ ] El sitio carga en localhost
- [ ] La API responde correctamente
- [ ] Los abuelos se muestran en la galería

### Para Producción:
- [ ] Leí LEEME_PRIMERO.md
- [ ] Leí README.md
- [ ] Tengo cuenta en Hostinger
- [ ] Tengo credenciales de PayU
- [ ] Seguí INSTALACION_HOSTINGER.md paso a paso
- [ ] El sitio carga con HTTPS
- [ ] La API responde correctamente
- [ ] Probé una donación real

---

## 🎓 Niveles de Conocimiento

### Nivel 1 - Principiante
**Debes leer:**
1. LEEME_PRIMERO.md (este) ✅
2. README.md
3. INSTALACION_LOCAL.md (completo)

**Puedes omitir:**
- PROYECTO_COMPLETO.md (por ahora)

### Nivel 2 - Intermedio
**Debes leer:**
1. LEEME_PRIMERO.md ✅
2. README.md
3. INSTALACION_HOSTINGER.md
4. PROYECTO_COMPLETO.md (secciones relevantes)

**Puedes omitir:**
- Detalles técnicos muy avanzados

### Nivel 3 - Avanzado
**Debes leer:**
1. Toda la documentación
2. Código fuente con comentarios
3. Documentación oficial de PayU

**Deberías también:**
- Entender la arquitectura completa
- Poder modificar cualquier parte
- Poder agregar nuevas funcionalidades

---

## 🚀 Próximos Pasos

Después de leer este documento:

1. **Si es tu primera vez**, lee `README.md`
2. **Si vas a instalar en local**, ve a `INSTALACION_LOCAL.md`
3. **Si vas a subir a producción**, ve a `INSTALACION_HOSTINGER.md`
4. **Si necesitas detalles técnicos**, consulta `PROYECTO_COMPLETO.md`

---

## 💡 Consejos Finales

✅ **Haz:**
- Lee la documentación antes de empezar
- Sigue los pasos en orden
- Prueba en local antes de producción
- Haz backups regularmente
- Consulta la documentación cuando tengas dudas

❌ **Evita:**
- Saltar pasos de instalación
- Modificar código sin entenderlo
- Subir a producción sin probar
- Ignorar los errores
- Versionar el archivo .env

---

## 📝 Notas Importantes

⚠️ **IMPORTANTE:**
- El archivo `.env` NO debe subirse a Git
- Las credenciales de PayU Sandbox son solo para pruebas
- Siempre usa HTTPS en producción
- Haz backups antes de cambios importantes

💚 **RECUERDA:**
- Este proyecto ayuda a abuelos reales
- Tu trabajo marca la diferencia
- La documentación está para ayudarte
- No dudes en consultarla

---

**¡Bienvenido al proyecto!** 🎉

Ahora que sabes dónde encontrar todo, ¡es hora de empezar!

Siguiente paso recomendado: **README.md**

---

**Última actualización**: Octubre 10, 2025  
**Versión**: 1.0

