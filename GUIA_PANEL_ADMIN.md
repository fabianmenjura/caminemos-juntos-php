# 🎯 Guía Rápida - Panel de Administración

## ✅ Panel Completado y Funcionando

---

## 🚀 Acceso Rápido

### URL del Panel

```
Local: http://localhost/caminemos-juntos-php/admin/login.html
Producción: https://caminemosjuntos.org/admin/login.html
```

### Credenciales

```
Usuario: admin
Contraseña: Admin123!
```

⚠️ **IMPORTANTE:** Cambia la contraseña después del primer login.

---

## 📋 Lo que Puedes Hacer

### 1. Dashboard
- Ver estadísticas en tiempo real
- Abuelos disponibles
- Total de donaciones y montos
- Mensajes pendientes
- Pagos exitosos
- Donaciones recientes

### 2. Gestión de Abuelos
- ✅ **Crear** nuevos abuelos con foto, descripción, edad, ciudad
- ✅ **Editar** cualquier abuelo existente
- ✅ **Eliminar** (soft delete - cambia estado a "eliminado")
- ✅ **Filtrar** por estado (disponible, adoptado, eliminado)
- ✅ **Vista de tabla** con foto, nombre, edad, ciudad, género

### 3. Donaciones
- Ver todas las donaciones (personas y empresas)
- Filtrar por tipo
- Ver montos totales
- Estados de las donaciones

### 4. Mensajes
- Ver mensajes de contacto
- Marcar como leídos
- Eliminar mensajes
- Filtrar por leído/no leído
- Badge con cantidad de no leídos

---

## 🛠️ Cómo Usar

### Agregar un Abuelo

1. Inicia sesión en el panel
2. Click en "Abuelos" en el menú lateral
3. Click en "Nuevo Abuelo" (botón verde arriba)
4. Llena el formulario:
   - **Nombre:** Nombre completo del abuelo
   - **Edad:** Entre 60 y 120 años
   - **Ciudad:** Ciudad donde vive
   - **Género:** Masculino o Femenino
   - **Estado:** Disponible o Adoptado
   - **Descripción:** Mínimo 20 caracteres
   - **Foto URL:** Ruta relativa, ej: `assets/images/abuelo-13.jpg`
   - **Fecha Nacimiento:** Opcional
5. Click en "Guardar"

### Editar un Abuelo

1. En la tabla de abuelos, click en el botón azul (ícono de lápiz)
2. Modifica los campos que necesites
3. Click en "Guardar"

### Eliminar un Abuelo

1. Click en el botón rojo (ícono de basura)
2. Confirma la eliminación
3. El abuelo cambia a estado "eliminado" (no se borra de la BD)

### Ver Donaciones

1. Click en "Donaciones" en el menú
2. Filtra por tipo: Todas, Personas, o Empresas
3. Ve la tabla con todas las donaciones

### Gestionar Mensajes

1. Click en "Mensajes" en el menú
2. Los mensajes no leídos aparecen destacados
3. Click en "Marcar como Leído" para procesarlos
4. Click en el ícono de basura para eliminar

---

## 🔒 Seguridad

- ✅ Contraseñas encriptadas con bcrypt
- ✅ Tokens de sesión de 64 caracteres
- ✅ Expiración automática después de 24 horas
- ✅ Todos los logs de actividad registrados
- ✅ Verificación de sesión en cada request

---

## 🐛 Solución de Problemas

### "Not Found" al hacer login

**Solución aplicada:** Los archivos `admin-auth.js` y `admin-api.js` ahora detectan automáticamente la ruta correcta.

Verifica en la consola (F12) que veas:
```
API Base URL: /caminemos-juntos-php/api
```

### No puedo iniciar sesión

1. Verifica las credenciales: `admin` / `Admin123!`
2. Abre F12 → Consola y busca errores
3. Verifica que la BD tenga el usuario:
   ```sql
   SELECT username, email FROM admin_users;
   ```

### Las imágenes de abuelos no se ven

Las rutas de las fotos deben ser **relativas**:
```
✅ Correcto: assets/images/abuelo-1.jpg
❌ Incorrecto: /abuelo-1.jpg
```

---

## 📊 Estadísticas del Dashboard

El dashboard muestra:
- Total de abuelos disponibles (por género)
- Total de donaciones (personas + empresas)
- Montos recaudados
- Mensajes pendientes
- Transacciones PayU exitosas
- Últimas 10 donaciones

---

## 🎓 Tips

1. **Usa el filtro de estado** en abuelos para ver disponibles, adoptados o eliminados
2. **Los badges de colores** indican el estado:
   - 🟢 Verde = Disponible/Completada
   - 🟡 Amarillo = Pendiente
   - 🔵 Azul = Persona/Adoptado
   - 🔴 Rojo = Eliminado/Rechazado
3. **El contador de mensajes** en el menú muestra los no leídos
4. **Todas las acciones quedan registradas** en admin_logs

---

## ✅ Checklist Final

- [x] Base de datos creada (admin_users, admin_sessions, admin_logs)
- [x] Usuario admin creado
- [x] Backend completado (AuthService, AdminController)
- [x] Frontend completado (Login, Dashboard, Abuelos, Donaciones, Mensajes)
- [x] Rutas API configuradas
- [x] Auto-detección de URLs corregida
- [x] Sistema de autenticación funcionando

---

## 🚀 Próximos Pasos

Ahora que el panel funciona:

1. **Inicia sesión** en el panel
2. **Cambia la contraseña** por defecto
3. **Agrega abuelos reales** con sus fotos
4. **Revisa las donaciones** que han llegado
5. **Responde los mensajes** de contacto
6. **Cuando estés listo**, despliega a producción:
   ```bash
   # Tu flujo de despliegue aquí
   ```

---

**¡El panel está listo para usar!** 🎉

Accede a: `http://localhost/caminemos-juntos-php/admin/login.html`

