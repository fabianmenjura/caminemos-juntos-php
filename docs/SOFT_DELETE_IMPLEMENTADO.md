# Sistema de Soft Delete Implementado

## 📋 Resumen

Se ha implementado un sistema de **"eliminación suave" (soft delete)** en toda la base de datos. Los registros nunca se eliminan físicamente, solo se marcan como eliminados.

---

## 🎯 **¿Qué es Soft Delete?**

En lugar de ejecutar `DELETE FROM tabla WHERE id = X`, ahora se ejecuta:
```sql
UPDATE tabla SET deleted = 1 WHERE id = X
```

### ✅ **Ventajas:**
1. **Recuperación de datos**: Los registros eliminados pueden restaurarse
2. **Auditoría completa**: Se mantiene el historial completo
3. **Integridad referencial**: No rompe relaciones con otras tablas
4. **Análisis de datos**: Se pueden analizar patrones de eliminación
5. **Cumplimiento legal**: Algunos marcos regulatorios lo requieren

---

## 🗄️ **Cambios en Base de Datos**

### Columna Agregada a Todas las Tablas:
```sql
deleted TINYINT(1) DEFAULT 0
```

### Tablas Actualizadas:
- ✅ `abuelos`
- ✅ `donaciones_personas`
- ✅ `donaciones_empresas`
- ✅ `mensajes_contacto`
- ✅ `voluntarios`
- ✅ `testimonios`
- ✅ `patrocinadores`
- ✅ `cumpleanos`

### Índices Creados:
Para optimizar las consultas que filtran por `deleted = 0`:
```sql
CREATE INDEX idx_deleted_[tabla] ON [tabla](deleted);
```

---

## 🔧 **Cambios en el Código**

### AdminController.php - Métodos Actualizados:

#### 1. **deleteAbuelo()**
```php
// Antes:
DELETE FROM abuelos WHERE id = ?

// Ahora:
UPDATE abuelos SET deleted = 1 WHERE id = ?
```

#### 2. **deleteMensaje()**
```php
// Antes:
DELETE FROM mensajes_contacto WHERE id = ?

// Ahora:
UPDATE mensajes_contacto SET deleted = 1 WHERE id = ?
```

#### 3. **deleteVoluntario()**
```php
// Antes:
DELETE FROM voluntarios WHERE id = ?

// Ahora:
UPDATE voluntarios SET deleted = 1 WHERE id = ?
```

#### 4. **deleteTestimonio()**
```php
// Antes:
DELETE FROM testimonios WHERE id = ?

// Ahora:
UPDATE testimonios SET deleted = 1 WHERE id = ?
```

### Consultas SELECT Actualizadas:

Todas las consultas SELECT ahora filtran por `deleted = 0`:

```php
// Abuelos
WHERE estado = '$estado' AND deleted = 0

// Mensajes
WHERE estado = 'nuevo' AND deleted = 0

// Voluntarios
WHERE deleted = 0 AND estado = ?

// Testimonios
WHERE deleted = 0 AND aprobado = TRUE
```

---

## 📝 **Script de Migración**

**Archivo**: `database/add-soft-delete.sql`

Para aplicar en una base de datos existente:

```bash
cd C:\laragon\www\caminemos-juntos-php
mysql -u root caminemos_juntos < database/add-soft-delete.sql
```

O manualmente:
```sql
USE caminemos_juntos;

ALTER TABLE abuelos ADD COLUMN deleted TINYINT(1) DEFAULT 0;
ALTER TABLE donaciones_personas ADD COLUMN deleted TINYINT(1) DEFAULT 0;
ALTER TABLE donaciones_empresas ADD COLUMN deleted TINYINT(1) DEFAULT 0;
ALTER TABLE mensajes_contacto ADD COLUMN deleted TINYINT(1) DEFAULT 0;
ALTER TABLE voluntarios ADD COLUMN deleted TINYINT(1) DEFAULT 0;
ALTER TABLE testimonios ADD COLUMN deleted TINYINT(1) DEFAULT 0;

CREATE INDEX idx_deleted_abuelos ON abuelos(deleted);
-- ... etc
```

---

## 🧪 **Cómo Probar**

### 1. Eliminar un Registro
```
1. Login al admin panel
2. Ir a cualquier sección (Abuelos, Voluntarios, etc.)
3. Clic en el botón "Eliminar" de un registro
4. Confirmar
```

### 2. Verificar en Base de Datos
```sql
-- El registro NO desaparece, solo cambia deleted a 1
SELECT * FROM abuelos WHERE id = X;
-- Resultado: deleted = 1

-- El registro NO aparece en consultas normales
SELECT * FROM abuelos WHERE deleted = 0;
-- Resultado: No incluye el registro eliminado
```

### 3. Verificar en el Admin Panel
- El registro eliminado NO aparece en las listas
- El contador de registros disminuye
- Los logs muestran "marcado como eliminado"

---

## 🔄 **Cómo Restaurar un Registro**

Si necesitas restaurar un registro eliminado:

```sql
UPDATE abuelos SET deleted = 0 WHERE id = X;
```

O puedes crear una función en el admin panel:
```php
public function restoreAbuelo($id) {
    $this->db->execute("UPDATE abuelos SET deleted = 0 WHERE id = ?", [$id]);
    return Response::success(null, 'Abuelo restaurado');
}
```

---

## 📊 **Ver Registros Eliminados**

Para ver todos los registros eliminados (útil para auditorías):

```sql
-- Abuelos eliminados
SELECT * FROM abuelos WHERE deleted = 1;

-- Mensajes eliminados
SELECT * FROM mensajes_contacto WHERE deleted = 1;

-- Voluntarios eliminados
SELECT * FROM voluntarios WHERE deleted = 1;

-- Testimonios eliminados
SELECT * FROM testimonios WHERE deleted = 1;
```

---

## 🚀 **Mejoras Futuras (Opcional)**

### 1. Panel de "Papelera de Reciclaje"
Crear una sección en el admin para:
- Ver registros eliminados
- Restaurar registros
- Eliminar permanentemente (hard delete)

### 2. Auto-limpieza
Eliminar permanentemente registros después de X días:
```sql
DELETE FROM abuelos 
WHERE deleted = 1 
AND updated_at < DATE_SUB(NOW(), INTERVAL 90 DAY);
```

### 3. Campo `deleted_at`
Agregar timestamp de cuándo se eliminó:
```sql
ALTER TABLE abuelos ADD COLUMN deleted_at TIMESTAMP NULL;

-- Al eliminar:
UPDATE abuelos 
SET deleted = 1, deleted_at = NOW() 
WHERE id = ?;
```

### 4. Campo `deleted_by`
Registrar quién eliminó:
```sql
ALTER TABLE abuelos ADD COLUMN deleted_by INT NULL;

-- Al eliminar:
UPDATE abuelos 
SET deleted = 1, deleted_by = ? 
WHERE id = ?;
```

---

## ✅ **Estado Actual**

- ✅ Columna `deleted` agregada a 8 tablas
- ✅ Índices creados para optimización
- ✅ 4 métodos delete actualizados a soft delete
- ✅ Todas las consultas SELECT filtran por `deleted = 0`
- ✅ Logs actualizados con mensajes claros
- ✅ Sistema funcionando en local

---

## 🚀 **Despliegue a Producción**

### Pasos:

1. **Backup de la BD**:
   ```bash
   mysqldump -u usuario -p caminemos_juntos > backup_antes_soft_delete.sql
   ```

2. **Ejecutar script SQL**:
   ```bash
   mysql -u usuario -p caminemos_juntos < database/add-soft-delete.sql
   ```

3. **Desplegar código**:
   ```bash
   git add .
   git commit -m "Implementado sistema de soft delete"
   git push origin main
   ```

4. **En el servidor**:
   ```bash
   cd /path/to/caminemosjuntos.org
   git pull
   ```

---

**✨ Sistema de Soft Delete completamente implementado y funcional ✨**

