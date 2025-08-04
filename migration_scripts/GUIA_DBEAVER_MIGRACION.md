# GUÍA COMPLETA: Migración SQL Server -> MySQL con DBeaver

## 📋 **REQUISITOS PREVIOS**

### 1. Drivers necesarios
- **SQL Server JDBC Driver** (ya incluido en DBeaver)
- **MySQL JDBC Driver** (ya incluido en DBeaver)

### 2. Información de conexión
- **SQL Server**: servidor, puerto, usuario, contraseña, base de datos "Hemodialisis"
- **MySQL**: localhost:3306, usuario "root", base de datos "dialisis"

---

## 🔧 **PASO 1: CONFIGURAR CONEXIONES EN DBEAVER**

### Conexión a SQL Server:
1. Abrir DBeaver
2. Click en "Nueva Conexión" (ícono +)
3. Seleccionar "SQL Server"
4. Configurar:
   ```
   Server: localhost (o tu servidor)
   Port: 1433
   Database: Hemodialisis
   Username: tu_usuario
   Password: tu_contraseña
   ```
5. Probar conexión y guardar

### Conexión a MySQL:
1. Nueva conexión -> MySQL
2. Configurar:
   ```
   Server: 127.0.0.1
   Port: 3306
   Database: dialisis
   Username: root
   Password: (tu contraseña MySQL)
   ```
3. Probar conexión y guardar

---

## 📊 **PASO 2: ANALIZAR ESTRUCTURA DE SQL SERVER**

### Exportar esquema SQL Server:
1. Click derecho en la base de datos "Hemodialisis"
2. **Tools** -> **Generate SQL**
3. Seleccionar:
   - ✅ **Structure** (DDL)
   - ✅ **Data** (DML) - si quieres los datos también
   - ✅ **All objects**
4. **Next** -> **Save to file**: `hemodialisis_schema.sql`
5. **Finish**

### Ver tablas y relaciones:
1. Expandir conexión SQL Server
2. Expandir "Hemodialisis" -> "Schemas" -> "dbo" -> "Tables"
3. Para cada tabla importante:
   - Click derecho -> **View Diagram** (para ver relaciones)
   - **Properties** para ver estructura detallada

---

## 🚀 **PASO 3: MÉTODO 1 - EXPORTAR/IMPORTAR CON DBEAVER**

### Exportar datos de SQL Server:
1. Click derecho en la base "Hemodialisis"
2. **Tools** -> **Export Data**
3. Seleccionar formato: **SQL Insert statements**
4. Configurar:
   - **Output**: Folder/File
   - **Settings**:
     - ✅ **Use qualified names**
     - ✅ **Format SQL**
     - **Data format**: `INSERT INTO`
5. **Next** -> Seleccionar todas las tablas
6. **Finish** -> Guardar como `hemodialisis_data.sql`

### Convertir y adaptar el SQL:
1. Abrir `hemodialisis_data.sql` en un editor
2. Reemplazar elementos de SQL Server por MySQL:
   ```sql
   -- Cambiar:
   [dbo].[tabla] -> `tabla`
   IDENTITY(1,1) -> AUTO_INCREMENT
   datetime2 -> DATETIME
   nvarchar -> VARCHAR
   bit -> BOOLEAN
   ```

### Importar a MySQL:
1. Abrir conexión MySQL en DBeaver
2. **Tools** -> **Execute Script**
3. Seleccionar el archivo `hemodialisis_data.sql` modificado
4. **Execute**

---

## 🔄 **PASO 4: MÉTODO 2 - MIGRACIÓN DIRECTA CON DBEAVER**

### Usando la herramienta de migración:
1. Click derecho en la conexión MySQL
2. **Tools** -> **Data Transfer**
3. **Source**: Seleccionar conexión SQL Server
4. **Target**: Conexión MySQL ya está seleccionada
5. **Settings**:
   - **Transfer type**: `Data and Structure`
   - **Processor**: `SQL Server to MySQL`
6. **Next** -> Seleccionar tablas a migrar
7. **Mapping**: Revisar y ajustar mapeo de tipos de datos
8. **Next** -> **Start**

### Configuraciones avanzadas:
- **Data extraction**: `Use result set streaming` (para tablas grandes)
- **Data loading**: `Insert ignore` (ignorar duplicados)
- **Performance**: Ajustar `Batch size` según tu hardware

---

## 📝 **PASO 5: VERIFICACIÓN POST-MIGRACIÓN**

### Verificar estructura:
```sql
-- En MySQL, ejecutar:
USE dialisis;

-- Ver todas las tablas
SHOW TABLES;

-- Ver estructura de una tabla específica
DESCRIBE pacientes;

-- Contar registros
SELECT COUNT(*) FROM pacientes;
```

### Verificar datos:
```sql
-- Comparar conteos entre SQL Server y MySQL
SELECT 
    'pacientes' as tabla,
    COUNT(*) as registros 
FROM pacientes
UNION ALL
SELECT 
    'obrassociales' as tabla,
    COUNT(*) as registros 
FROM obrassociales;
```

### Verificar relaciones:
```sql
-- Verificar foreign keys
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_NAME IS NOT NULL;
```

---

## ⚠️ **PROBLEMAS COMUNES Y SOLUCIONES**

### Error de codificación:
```sql
-- Al inicio del script SQL:
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
```

### Problemas con fechas:
```sql
-- SQL Server usa formato diferente
-- Antes: '2023-12-25 00:00:00.000'
-- MySQL: '2023-12-25 00:00:00'
```

### Auto increment no funciona:
```sql
-- Asegurar que el campo ID tenga:
ALTER TABLE pacientes MODIFY id INT AUTO_INCREMENT;
```

### Foreign keys fallan:
```sql
-- Desactivar temporalmente:
SET FOREIGN_KEY_CHECKS = 0;
-- ... insertar datos ...
SET FOREIGN_KEY_CHECKS = 1;
```

---

## 🎯 **PASO 6: VALIDACIÓN FINAL**

### Script de validación:
```sql
-- Ejecutar en ambas bases para comparar
SELECT 
    'SQL Server' as origen,
    COUNT(*) as total_pacientes 
FROM Hemodialisis.dbo.pacientes;

-- En MySQL:
SELECT 
    'MySQL' as origen,
    COUNT(*) as total_pacientes 
FROM dialisis.pacientes;
```

### Verificar integridad referencial:
```sql
-- Verificar que no hay registros huérfanos
SELECT COUNT(*) FROM pacientes p
LEFT JOIN localidades l ON p.id_localidad = l.id
WHERE l.id IS NULL AND p.id_localidad IS NOT NULL;
```

---

## 📚 **RECURSOS ADICIONALES**

### Documentación:
- [DBeaver Data Transfer](https://dbeaver.com/docs/wiki/Data-transfer/)
- [SQL Server to MySQL Migration](https://dev.mysql.com/doc/workbench/en/wb-migration.html)

### Herramientas alternativas:
- **MySQL Workbench** (Migration Wizard)
- **phpMyAdmin** (para importar SQL)
- **Navicat** (Premium, con herramientas de migración)

---

## ✅ **CHECKLIST DE MIGRACIÓN**

- [ ] Conexiones configuradas en DBeaver
- [ ] Esquema de SQL Server exportado
- [ ] Estructura creada en MySQL
- [ ] Datos migrados
- [ ] Conteos verificados
- [ ] Foreign keys funcionando
- [ ] Aplicación Laravel conectada
- [ ] Modelos de Laravel funcionando
- [ ] Backup de seguridad creado

---

**¡Tu base de datos estará lista para usarse con Laravel y Filament!**
