# 🚀 Guía Rápida de Despliegue

## ⚡ Despliegue Rápido desde Windows

### Opción 1: Script Automático (Recomendado)

```bash
# Simplemente ejecuta:
deploy-to-production.bat
```

El script hace automáticamente:
1. Agrega todos los cambios
2. Te pide un mensaje de commit
3. Cambia a rama production
4. Hace merge con develop
5. Push a GitHub

### Opción 2: Manual

```bash
# 1. Agregar cambios
git add .

# 2. Commit
git commit -m "Tu mensaje aquí"

# 3. Cambiar a production
git checkout production

# 4. Merge
git merge develop

# 5. Push
git push origin production

# 6. Volver a develop
git checkout develop
```

---

## 🌐 Despliegue en Hostinger

### Si configuraste Webhook

✅ **Automático** - Los cambios se despliegan solos al hacer push

### Si NO tienes Webhook

```bash
# Conectarte por SSH
ssh -p 65002 u123456789@hostinger-ip

# Ejecutar despliegue
cd ~/public_html
bash deploy.sh
```

---

## 📋 Checklist Pre-Despliegue

Antes de hacer push:

- [ ] Código probado en local
- [ ] `.env` NO está en los cambios
- [ ] Commit message descriptivo
- [ ] Sin errores en consola

---

## 🔧 Configuración Inicial (Solo Primera Vez)

### 1. Generar SSH Key

```powershell
ssh-keygen -t rsa -b 4096 -C "fabianesneider39@gmail.com"
# Guardar en: C:\Users\TU_USUARIO\.ssh\id_rsa_hostinger
```

### 2. Copiar la clave pública

```powershell
Get-Content $env:USERPROFILE\.ssh\id_rsa_hostinger.pub
```

### 3. Agregar en Hostinger

Panel Hostinger → SSH Access → Manage SSH Keys → Add SSH Key

### 4. Probar conexión

```bash
ssh -p 65002 u123456789@tu-ip-hostinger
```

### 5. Clonar en Hostinger (Primera vez)

```bash
# Conectado por SSH
cd ~
mv public_html public_html.backup  # Backup del anterior
git clone -b production https://github.com/fabianmenjura/caminemos-juntos-php.git public_html
cd public_html
```

### 6. Crear .env en servidor

```bash
cd ~/public_html/api
nano .env
```

Pega tus credenciales de producción y guarda (Ctrl+O, Enter, Ctrl+X)

### 7. Configurar webhook (Opcional)

**a) Generar token secreto:**

```bash
openssl rand -hex 32
```

**b) Editar webhook.php:**

```bash
nano webhook.php
```

Cambia `WEBHOOK_SECRET` por tu token.

**c) En GitHub:**

Repo → Settings → Webhooks → Add webhook
- URL: `https://tudominio.com/webhook.php`
- Content type: `application/json`
- Secret: Tu token generado
- Events: Just the push event

---

## 🐛 Solución Rápida de Problemas

### "Permission denied (publickey)"

```bash
# Usa la clave específica
ssh -i C:\Users\TU_USUARIO\.ssh\id_rsa_hostinger -p 65002 usuario@ip
```

### "Los cambios no se reflejan"

```bash
# En Hostinger, ejecuta:
cd ~/public_html
bash deploy.sh

# Limpia caché del navegador: Ctrl + F5
```

### "Webhook no funciona"

```bash
# Ver logs
ssh -p 65002 usuario@ip
cat /tmp/webhook_deploy.log
```

---

## 📞 Enlaces Útiles

- **Repositorio:** https://github.com/fabianmenjura/caminemos-juntos-php.git
- **Documentación completa:** Ver `DESPLIEGUE_GIT.md`
- **Hostinger SSH:** Panel → Avanzado → SSH Access

---

## ✅ Comandos Útiles

```bash
# Ver estado
git status

# Ver últimos commits
git log --oneline -5

# Ver diferencias
git diff

# Deshacer cambios
git checkout -- archivo.php

# Ver ramas
git branch -a

# Cambiar de rama
git checkout nombre-rama
```

---

**¡Listo para desplegar!** 🎉

Recuerda: Siempre prueba en local antes de hacer push a producción.

