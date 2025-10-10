#!/bin/bash

# ========================================
# Script de Despliegue Automático
# Proyecto: Adopta un Abuelo Colombia
# ========================================

echo ""
echo "🚀 Iniciando despliegue en producción..."
echo "=========================================="
echo ""

# Colores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Ir al directorio del proyecto
cd ~/public_html || { echo -e "${RED}❌ Error: No se pudo acceder a public_html${NC}"; exit 1; }

echo -e "${YELLOW}📂 Directorio actual:${NC} $(pwd)"
echo ""

# Guardar cambios locales (si hay)
echo -e "${YELLOW}📦 Guardando cambios locales...${NC}"
git stash
echo ""

# Mostrar rama actual
CURRENT_BRANCH=$(git branch --show-current)
echo -e "${YELLOW}🌿 Rama actual:${NC} $CURRENT_BRANCH"
echo ""

# Descargar últimos cambios de GitHub
echo -e "${YELLOW}⬇️  Descargando cambios desde GitHub (rama production)...${NC}"
git fetch origin production

# Verificar si hay cambios
LOCAL=$(git rev-parse HEAD)
REMOTE=$(git rev-parse origin/production)

if [ $LOCAL = $REMOTE ]; then
    echo -e "${GREEN}✅ Ya estás actualizado. No hay cambios nuevos.${NC}"
else
    echo -e "${YELLOW}📥 Aplicando cambios...${NC}"
    
    # Aplicar cambios
    git reset --hard origin/production
    
    echo -e "${GREEN}✅ Cambios aplicados exitosamente${NC}"
fi
echo ""

# Verificar archivos críticos
echo -e "${YELLOW}🔧 Verificando configuración...${NC}"

if [ ! -f "api/.env" ]; then
    echo -e "${RED}⚠️  ADVERTENCIA: Falta api/.env${NC}"
    echo -e "${YELLOW}   Debes crearlo manualmente con tus credenciales de producción${NC}"
else
    echo -e "${GREEN}✅ api/.env existe${NC}"
fi

if [ ! -f "index.html" ]; then
    echo -e "${RED}❌ ERROR: Falta index.html${NC}"
    exit 1
else
    echo -e "${GREEN}✅ index.html existe${NC}"
fi

if [ ! -d "api" ]; then
    echo -e "${RED}❌ ERROR: Falta carpeta api/${NC}"
    exit 1
else
    echo -e "${GREEN}✅ Carpeta api/ existe${NC}"
fi
echo ""

# Configurar permisos
echo -e "${YELLOW}🔐 Configurando permisos...${NC}"

# Archivos: 644
find . -type f -name "*.php" -exec chmod 644 {} \;
find . -type f -name "*.html" -exec chmod 644 {} \;
find . -type f -name "*.css" -exec chmod 644 {} \;
find . -type f -name "*.js" -exec chmod 644 {} \;

# Carpetas: 755
find . -type d -exec chmod 755 {} \;

# .env más seguro: 600
if [ -f "api/.env" ]; then
    chmod 600 api/.env
    echo -e "${GREEN}✅ api/.env protegido (permisos 600)${NC}"
fi

# .htaccess: 644
if [ -f ".htaccess" ]; then
    chmod 644 .htaccess
    echo -e "${GREEN}✅ .htaccess configurado${NC}"
fi

echo ""

# Mostrar información del último commit
echo -e "${YELLOW}📝 Último commit desplegado:${NC}"
git log -1 --pretty=format:"   %h - %an, %ar : %s" && echo ""
echo ""

# Verificar URL
echo -e "${YELLOW}🌐 URLs a verificar:${NC}"
echo "   • API Health: https://$(hostname)/api/health"
echo "   • Sitio web: https://$(hostname)/"
echo ""

echo "=========================================="
echo -e "${GREEN}✅ Despliegue completado exitosamente!${NC}"
echo "=========================================="
echo ""
echo -e "${YELLOW}📋 Próximos pasos:${NC}"
echo "   1. Verifica que el sitio funcione correctamente"
echo "   2. Revisa que las imágenes cargan"
echo "   3. Prueba el formulario de donación"
echo "   4. Verifica la integración con PayU"
echo ""
echo -e "${YELLOW}📊 Monitoreo:${NC}"
echo "   • Ver logs: tail -f ~/logs/error.log"
echo "   • Ver último despliegue: git log -1"
echo ""

exit 0

