# 🚀 Guía de Despliegue con Git + SSH a Hostinger

Esta guía te permite desplegar automáticamente desde GitHub a Hostinger usando SSH.

---

## 📋 Requisitos Previos

- ✅ Cuenta de GitHub con repositorio creado
- ✅ Acceso SSH a Hostinger (plan Premium o superior)
- ✅ Git instalado en tu PC local
- ✅ Proyecto funcionando en local

**Repositorio:** https://github.com/fabianmenjura/caminemos-juntos-php.git

---

## 🔧 Paso 1: Preparar el Repositorio Local

### 1.1 Inicializar Git (si no está inicializado)

```bash
cd C:\laragon\www\caminemos-juntos-php
git init
```

### 1.2 Agregar el repositorio remoto

```bash
git remote add origin https://github.com/fabianmenjura/caminemos-juntos-php.git
```

### 1.3 Verificar que .gitignore está configurado

Asegúrate de que `.gitignore` incluye:
```
.env
api/.env
*.log
tmp/
vendor/
```

### 1.4 Hacer el primer commit

```bash
# Ver archivos a subir
git status

# Agregar todos los archivos
git add .

# Hacer commit
git commit -m "Initial commit: Proyecto Adopta un Abuelo Colombia"

# Crear rama production
git branch -M production

# Subir a GitHub
git push -u origin production
```

**⚠️ IMPORTANTE:** Verifica que **NO** se haya subido el archivo `.env` con las credenciales.

---

## 🔑 Paso 2: Configurar SSH en Hostinger

### 2.1 Generar clave SSH (si no la tienes)

En tu PC local:

```bash
# Windows PowerShell
ssh-keygen -t rsa -b 4096 -C "fabianesneider39@gmail.com"

# Guardar en: C:\Users\TU_USUARIO\.ssh\id_rsa_hostinger
# No pongas contraseña (Enter, Enter)
```

### 2.2 Copiar la clave pública

```bash
# Ver la clave pública
Get-Content C:\Users\$env:USERNAME\.ssh\id_rsa_hostinger.pub
```

Copia TODO el contenido (empieza con `ssh-rsa`).

### 2.3 Agregar la clave SSH en Hostinger

1. Accede al panel de Hostinger
2. Ve a **"Avanzado"** → **"SSH Access"**
3. Click en **"Manage SSH Keys"**
4. Click en **"Add SSH Key"**
5. Pega la clave pública
6. Click en **"Add"**

### 2.4 Probar la conexión SSH

```bash
# Reemplaza con tu información de Hostinger
ssh -p 65002 u123456789@123.45.67.89
```

(El puerto y la IP te los da Hostinger en "SSH Access")

Si funciona, verás el prompt de Hostinger.

---

## 📦 Paso 3: Configurar Git en Hostinger

### 3.1 Conectarte por SSH

```bash
ssh -p 65002 u123456789@123.45.67.89
```

### 3.2 Navegar a tu public_html

```bash
cd ~/public_html
```

### 3.3 Clonar el repositorio

**Opción A: Primera vez (no hay archivos)**

```bash
# Renombrar public_html actual (backup)
cd ~
mv public_html public_html.backup

# Clonar repositorio
git clone -b production https://github.com/fabianmenjura/caminemos-juntos-php.git public_html

# Entrar
cd public_html
```

**Opción B: Ya tienes archivos (actualizar)**

```bash
cd ~/public_html

# Inicializar git si no está
git init

# Agregar remoto
git remote add origin https://github.com/fabianmenjura/caminemos-juntos-php.git

# Descargar cambios
git fetch origin production

# Aplicar cambios (cuidado: sobrescribe archivos locales)
git reset --hard origin/production
```

### 3.4 Crear el archivo .env en Hostinger

```bash
cd ~/public_html/api
nano .env
```

Pega tu configuración de producción:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_HOST=localhost
DB_NAME=u123456789_caminemos
DB_USER=u123456789_admin
DB_PASSWORD=TU_CONTRASEÑA_REAL

PAYU_MERCHANT_ID=TU_MERCHANT_ID
PAYU_API_KEY=TU_API_KEY
PAYU_API_LOGIN=TU_API_LOGIN
PAYU_ACCOUNT_ID=TU_ACCOUNT_ID
PAYU_TEST_MODE=false

PAYU_RESPONSE_URL=https://tudominio.com/api/payu/response.php
PAYU_CONFIRMATION_URL=https://tudominio.com/api/payu/confirmation.php
```

Guardar: `Ctrl + O`, Enter, `Ctrl + X`

### 3.5 Configurar permisos

```bash
cd ~/public_html

# Archivos: 644
find . -type f -exec chmod 644 {} \;

# Carpetas: 755
find . -type d -exec chmod 755 {} \;

# .env más seguro: 600
chmod 600 api/.env

# Asegurar que .htaccess es ejecutable
chmod 644 .htaccess
```

---

## 🔄 Paso 4: Script de Despliegue Automático

### 4.1 Crear script de despliegue en Hostinger

```bash
cd ~/public_html
nano deploy.sh
```

Contenido del script:

```bash
#!/bin/bash

# Script de despliegue automático
# Ejecutar: bash deploy.sh

echo "🚀 Iniciando despliegue..."

# Ir al directorio del proyecto
cd ~/public_html

# Guardar cambios locales (si hay)
echo "📦 Guardando cambios locales..."
git stash

# Descargar últimos cambios de GitHub
echo "⬇️ Descargando cambios desde GitHub..."
git fetch origin production

# Aplicar cambios
echo "✅ Aplicando cambios..."
git reset --hard origin/production

# Restaurar cambios locales importantes
echo "🔧 Verificando configuración..."

# Verificar que .env existe
if [ ! -f "api/.env" ]; then
    echo "⚠️ ADVERTENCIA: Falta api/.env - Debes crearlo manualmente"
fi

# Configurar permisos
echo "🔐 Configurando permisos..."
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod 600 api/.env 2>/dev/null

echo "✅ Despliegue completado!"
echo "🌐 Verifica tu sitio: https://tudominio.com"
```

Guardar y hacer ejecutable:

```bash
chmod +x deploy.sh
```

### 4.2 Usar el script

Cada vez que quieras actualizar producción:

```bash
cd ~/public_html
bash deploy.sh
```

---

## 🤖 Paso 5: Automatizar con Webhook (Opcional)

### 5.1 Crear webhook en Hostinger

```bash
cd ~/public_html
nano webhook.php
```

Contenido:

```php
<?php
// Webhook para despliegue automático desde GitHub
// URL: https://tudominio.com/webhook.php

// Token secreto (genera uno aleatorio)
$secret = 'TU_TOKEN_SECRETO_AQUI';

// Verificar token
$headers = getallheaders();
$signature = $headers['X-Hub-Signature-256'] ?? '';

if (empty($signature)) {
    http_response_code(403);
    die('Forbidden');
}

// Obtener payload
$payload = file_get_contents('php://input');

// Verificar firma
$hash = 'sha256=' . hash_hmac('sha256', $payload, $secret);
if (!hash_equals($hash, $signature)) {
    http_response_code(403);
    die('Invalid signature');
}

// Log
file_put_contents('/tmp/webhook.log', date('Y-m-d H:i:s') . " - Webhook recibido\n", FILE_APPEND);

// Ejecutar despliegue
$output = shell_exec('cd ~/public_html && bash deploy.sh 2>&1');

// Responder
http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Deployment triggered',
    'output' => $output
]);
?>
```

### 5.2 Configurar webhook en GitHub

1. Ve a tu repositorio: https://github.com/fabianmenjura/caminemos-juntos-php
2. Settings → Webhooks → Add webhook
3. Payload URL: `https://tudominio.com/webhook.php`
4. Content type: `application/json`
5. Secret: El mismo token que pusiste en `webhook.php`
6. Events: "Just the push event"
7. Click "Add webhook"

**Ahora:** Cada vez que hagas `git push`, se desplegará automáticamente.

---

## 📝 Flujo de Trabajo Diario

### Desarrollo Local

```bash
# 1. Hacer cambios en tu código local
# 2. Probar que funciona en local

# 3. Agregar cambios
git add .

# 4. Hacer commit
git commit -m "Descripción de los cambios"

# 5. Subir a GitHub
git push origin production
```

### Si configuraste webhook:
✅ Los cambios se desplegan automáticamente en producción

### Si NO tienes webhook:
Conectarte por SSH y ejecutar:

```bash
ssh -p 65002 u123456789@123.45.67.89
cd ~/public_html
bash deploy.sh
```

---

## 🔒 Seguridad

### Archivos a NO versionar

- ✅ `.env` (credenciales)
- ✅ `*.log` (logs)
- ✅ Backups de base de datos
- ✅ Archivos temporales

### Verificar antes de push

```bash
# Ver qué archivos se subirán
git status

# Si ves .env, ¡NO HAGAS PUSH!
# Agregarlo al .gitignore:
echo ".env" >> .gitignore
echo "api/.env" >> .gitignore
git add .gitignore
git commit -m "Add .env to gitignore"
```

### Si subiste .env por error

```bash
# Remover del historial
git rm --cached .env
git rm --cached api/.env
git commit -m "Remove .env from repository"
git push origin production --force
```

Luego cambia TODAS las contraseñas y tokens.

---

## 🐛 Troubleshooting

### Error: "Permission denied (publickey)"

**Solución:** La clave SSH no está configurada correctamente.

1. Verifica que la clave pública está en Hostinger
2. Usa el comando completo con la clave:

```bash
ssh -i C:\Users\TU_USUARIO\.ssh\id_rsa_hostinger -p 65002 u123456789@123.45.67.89
```

### Error: "git: command not found" en Hostinger

**Solución:** Git no está instalado en el servidor.

Contacta soporte de Hostinger para habilitar Git.

### Error: Los cambios no se reflejan

**Solución:**

1. Verifica que hiciste push: `git log` en local
2. Ejecuta el deploy: `bash deploy.sh` en Hostinger
3. Limpia caché del navegador: Ctrl + F5

### El webhook no funciona

**Solución:**

1. Verifica el log: `cat /tmp/webhook.log`
2. Revisa que el token secreto coincide
3. Prueba manualmente: `bash deploy.sh`

---

## 📊 Comandos Útiles

### Ver estado del repositorio

```bash
git status
git log --oneline -5
git remote -v
```

### Ver diferencias antes de commit

```bash
git diff
git diff --staged
```

### Deshacer cambios

```bash
# Deshacer cambios no commiteados
git checkout -- archivo.php

# Deshacer último commit (mantiene cambios)
git reset --soft HEAD~1

# Deshacer último commit (elimina cambios)
git reset --hard HEAD~1
```

### Ver logs del webhook

```bash
# En Hostinger
cat /tmp/webhook.log
tail -f /tmp/webhook.log  # En tiempo real
```

---

## ✅ Checklist de Despliegue

Antes de hacer push a producción:

- [ ] Código probado en local
- [ ] `.env` NO está en el commit
- [ ] Commit message descriptivo
- [ ] Base de datos actualizada (si hay cambios)
- [ ] Imágenes y assets actualizados
- [ ] Sin errores en consola del navegador
- [ ] API responde correctamente
- [ ] PayU configurado (credenciales correctas)

---

## 📞 Soporte

**GitHub Docs:** https://docs.github.com/  
**Hostinger SSH:** https://www.hostinger.com/tutorials/ssh  
**Git Docs:** https://git-scm.com/doc

---

## 🎉 ¡Listo!

Ahora tienes un flujo de despliegue moderno:

```
Código Local → Git Push → GitHub → Webhook → Hostinger (Producción)
```

**Próximos pasos:**
1. Haz tu primer push
2. Verifica que aparece en GitHub
3. Si configuraste webhook, verifica que se desplegó en producción
4. Disfruta del despliegue automático 🚀

---

**Desarrollado por:** Fabián Esneider Menjura Sánchez  
**Email:** fabianesneider39@gmail.com  
**Año:** 2025

