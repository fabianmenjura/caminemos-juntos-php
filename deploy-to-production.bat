@echo off
REM Script para desplegar a producción desde Windows
REM Ejecutar: deploy-to-production.bat

echo.
echo ========================================
echo  DESPLIEGUE A PRODUCCION
echo ========================================
echo.

REM Verificar que estamos en la rama correcta
echo [1/5] Verificando rama actual...
git branch --show-current
echo.

REM Agregar cambios
echo [2/5] Agregando cambios...
git add .
echo.

REM Solicitar mensaje de commit
set /p COMMIT_MSG="Ingresa el mensaje del commit: "
if "%COMMIT_MSG%"=="" set COMMIT_MSG=Update: Changes for production

REM Hacer commit
echo.
echo [3/5] Haciendo commit: %COMMIT_MSG%
git commit -m "%COMMIT_MSG%"
echo.

REM Cambiar a production y merge
echo [4/5] Cambiando a rama production y mergeando...
git checkout production
git merge develop
echo.

REM Push a GitHub
echo [5/5] Subiendo a GitHub (production)...
git push origin production
echo.

REM Volver a develop
git checkout develop

echo.
echo ========================================
echo  DESPLIEGUE COMPLETADO!
echo ========================================
echo.
echo Cambios subidos a GitHub (rama production)
echo.
echo PROXIMOS PASOS:
echo 1. Si configuraste webhook: Los cambios se desplegaran automaticamente
echo 2. Si NO tienes webhook: Conectate por SSH y ejecuta: bash deploy.sh
echo.
echo Verifica tu sitio: https://tudominio.com
echo.
pause

