# ✅ Configuración de Despliegue Completada

## 🎉 ¡Todo Listo para Producción!

Tu proyecto está configurado con un sistema de despliegue moderno y automatizado.

---

## 📦 Lo que se Configuró

### 1. Repositorio Git

✅ **GitHub Repository:** https://github.com/fabianmenjura/caminemos-juntos-php.git  
✅ **Rama principal:** `production`  
✅ **Rama de desarrollo:** `develop`

### 2. Archivos Creados

| Archivo | Propósito |
|---------|-----------|
| `.gitignore` | Protege archivos sensibles (.env, logs) |
| `deploy-to-production.bat` | Script Windows para despliegue rápido |
| `deploy.sh` | Script bash para ejecutar en Hostinger |
| `webhook.php` | Webhook para despliegue automático desde GitHub |
| `DESPLIEGUE_GIT.md` | Guía completa paso a paso |
| `GUIA_RAPIDA_DESPLIEGUE.md` | Comandos rápidos de referencia |

### 3. Commits Realizados

```bash
✅ c700a90 - Add deployment guide and fix image paths - Ready for production
✅ ef22b66 - Add deployment scripts and webhook for automated deployment
✅ 9b2c6b6 - Update README with deployment information and repository link
```

---

## 🚀 Cómo Desplegar Ahora

### Opción 1: Script Automático (MÁS FÁCIL)

Simplemente ejecuta desde tu proyecto:

```bash
deploy-to-production.bat
```

El script te pedirá un mensaje de commit y hará todo automáticamente.

### Opción 2: Manual

```bash
# 1. Haz tus cambios en el código
# 2. Agrega y commit
git add .
git commit -m "Descripción de tus cambios"

# 3. Sube a GitHub
git checkout production
git merge develop
git push origin production
```

---

## 🔧 Próximos Pasos en Hostinger

Para completar la configuración en Hostinger, sigue estos pasos:

### Paso 1: Configurar SSH

```bash
# 1. Genera una clave SSH (si no la tienes)
ssh-keygen -t rsa -b 4096 -C "fabianesneider39@gmail.com"

# 2. Copia la clave pública
Get-Content $env:USERPROFILE\.ssh\id_rsa_hostinger.pub

# 3. Agrégala en Hostinger:
#    Panel → Avanzado → SSH Access → Manage SSH Keys → Add SSH Key
```

### Paso 2: Conectar y Clonar

```bash
# 1. Conectarte por SSH (Hostinger te da el puerto e IP)
ssh -p 65002 tu-usuario@tu-ip-hostinger

# 2. Hacer backup del public_html actual
cd ~
mv public_html public_html.backup

# 3. Clonar el repositorio
git clone -b production https://github.com/fabianmenjura/caminemos-juntos-php.git public_html

# 4. Entrar al directorio
cd public_html
```

### Paso 3: Configurar .env

```bash
# Crear el archivo .env con credenciales de producción
cd ~/public_html/api
nano .env
```

Contenido (ajusta tus valores):

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_HOST=localhost
DB_NAME=u123456789_caminemos
DB_USER=u123456789_admin
DB_PASSWORD=TU_CONTRASEÑA_MYSQL

PAYU_MERCHANT_ID=TU_MERCHANT_ID_REAL
PAYU_API_KEY=TU_API_KEY_REAL
PAYU_API_LOGIN=TU_API_LOGIN_REAL
PAYU_ACCOUNT_ID=TU_ACCOUNT_ID_REAL
PAYU_TEST_MODE=false

PAYU_RESPONSE_URL=https://tudominio.com/api/payu/response.php
PAYU_CONFIRMATION_URL=https://tudominio.com/api/payu/confirmation.php
```

Guardar: `Ctrl + O`, Enter, `Ctrl + X`

### Paso 4: Configurar Permisos

```bash
cd ~/public_html

# Hacer deploy.sh ejecutable
chmod +x deploy.sh

# Configurar permisos
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod 600 api/.env
```

### Paso 5: Importar Base de Datos

```bash
# 1. En el panel de Hostinger, crea la base de datos
#    Panel → Bases de datos MySQL → Crear nueva

# 2. Accede a phpMyAdmin
#    Panel → phpMyAdmin → Importar

# 3. Sube estos archivos:
#    - database/schema.sql
#    - database/seed.sql
```

### Paso 6: Configurar Webhook (Opcional pero Recomendado)

**a) Generar token secreto:**

```bash
openssl rand -hex 32
```

**b) Editar webhook.php en el servidor:**

```bash
nano ~/public_html/webhook.php

# Cambia esta línea:
define('WEBHOOK_SECRET', 'AQUI_VA_TU_TOKEN_GENERADO');
```

**c) Configurar en GitHub:**

1. Ve a https://github.com/fabianmenjura/caminemos-juntos-php
2. Settings → Webhooks → Add webhook
3. Configuración:
   ```
   Payload URL: https://tudominio.com/webhook.php
   Content type: application/json
   Secret: [el token que generaste]
   Events: Just the push event
   ```
4. Click "Add webhook"

---

## 🎯 Flujo de Trabajo Diario

### Con Webhook Configurado

```bash
# 1. Haz cambios en tu código local
# 2. Ejecuta el script
deploy-to-production.bat

# ¡Listo! Los cambios se despliegan automáticamente en producción
```

### Sin Webhook

```bash
# 1. Haz cambios en tu código local
# 2. Ejecuta el script
deploy-to-production.bat

# 3. Conéctate por SSH y ejecuta:
ssh -p 65002 usuario@ip
cd ~/public_html
bash deploy.sh
```

---

## 📊 Verificación

Después de desplegar, verifica:

### 1. API

```
https://tudominio.com/api/health
```

Debe devolver:
```json
{
  "success": true,
  "data": {
    "status": "OK"
  }
}
```

### 2. Sitio Web

```
https://tudominio.com/
```

Verifica:
- ✅ Página carga correctamente
- ✅ Imágenes se muestran
- ✅ Galería de abuelos funciona
- ✅ Formulario de donación funciona
- ✅ SSL activo (candado verde)

### 3. Base de Datos

En phpMyAdmin, verifica:
```sql
SELECT COUNT(*) FROM abuelos;  -- Debe devolver 12
```

---

## 🔒 Seguridad Configurada

✅ **`.gitignore`** - Protege `.env` y archivos sensibles  
✅ **Permisos** - `.env` con permisos 600  
✅ **Validación** - Webhook valida firma de GitHub  
✅ **HTTPS** - Configurado en .htaccess  

---

## 📚 Documentación

Tienes documentación completa para cada escenario:

- **Despliegue rápido:** `GUIA_RAPIDA_DESPLIEGUE.md`
- **Despliegue completo:** `DESPLIEGUE_GIT.md`
- **Instalación local:** `INSTALACION_LOCAL.md`
- **Instalación Hostinger:** `INSTALACION_HOSTINGER.md`
- **Problemas comunes:** `SOLUCION_PROBLEMAS_COMUNES.md`
- **Referencia técnica:** `PROYECTO_COMPLETO.md`

---

## 🎓 Comandos Git Esenciales

```bash
# Ver estado
git status

# Ver historia
git log --oneline -5

# Ver diferencias
git diff

# Deshacer cambios
git checkout -- archivo.php

# Ver ramas
git branch -a

# Cambiar rama
git checkout nombre-rama

# Actualizar desde remoto
git pull origin production
```

---

## 🆘 Soporte

### Recursos en Línea

- **GitHub Docs:** https://docs.github.com/
- **Hostinger SSH:** https://www.hostinger.com/tutorials/ssh
- **Git Tutorial:** https://git-scm.com/book/es/v2

### Archivos de Log

```bash
# Webhook logs (en Hostinger)
cat /tmp/webhook_deploy.log

# Apache error log (en Hostinger)
tail -f ~/logs/error.log
```

---

## ✅ Checklist Final

Antes de considerar completa la configuración:

- [ ] SSH configurado en Hostinger
- [ ] Repositorio clonado en public_html
- [ ] Archivo .env creado con credenciales reales
- [ ] Base de datos importada (schema + seed)
- [ ] Permisos configurados correctamente
- [ ] Webhook configurado (opcional)
- [ ] Sitio web accesible y funcionando
- [ ] API respondiendo correctamente
- [ ] PayU configurado (credenciales reales)
- [ ] SSL activo (HTTPS)

---

## 🎉 ¡Felicitaciones!

Tu sistema de despliegue está listo. Ahora puedes:

✅ Desarrollar en local con confianza  
✅ Desplegar a producción en segundos  
✅ Revertir cambios si algo sale mal  
✅ Mantener un historial completo de cambios  
✅ Trabajar en equipo sin conflictos  

---

**Desarrollado por:** Fabián Esneider Menjura Sánchez  
**Email:** fabianesneider39@gmail.com  
**Repositorio:** https://github.com/fabianmenjura/caminemos-juntos-php.git  
**Año:** 2025

---

**¡Éxito con tu proyecto!** 🚀💚

