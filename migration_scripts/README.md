# MIGRACIÓN SQL SERVER -> MYSQL

Este directorio contiene todos los scripts y herramientas necesarios para migrar la base de datos **Hemodialisis** de SQL Server a la base de datos **dialisis** en MySQL.

## 📁 ARCHIVOS INCLUIDOS

| Archivo | Descripción |
|---------|-------------|
| `01_extract_sqlserver_schema.sql` | Script SQL para extraer estructura de SQL Server |
| `migrate_sqlserver_to_mysql.py` | Script Python para migración automática |
| `migrate_sqlserver_to_mysql.ps1` | Script PowerShell alternativo |
| `requirements.txt` | Dependencias Python |
| `setup.bat` | Script de instalación para Windows |
| `GUIA_DBEAVER_MIGRACION.md` | Guía completa para usar DBeaver |

## 🚀 MÉTODOS DE MIGRACIÓN

### MÉTODO 1: DBeaver (Recomendado - Más fácil)
1. Seguir la guía en `GUIA_DBEAVER_MIGRACION.md`
2. Configurar conexiones en DBeaver
3. Usar la herramienta de migración integrada

### MÉTODO 2: Script Python (Automático)
```bash
# 1. Instalar dependencias
pip install -r requirements.txt

# 2. Configurar credenciales en migrate_sqlserver_to_mysql.py
# 3. Ejecutar migración
python migrate_sqlserver_to_mysql.py
```

### MÉTODO 3: Script PowerShell
```powershell
# Ejecutar en PowerShell como administrador
.\migrate_sqlserver_to_mysql.ps1
```

### MÉTODO 4: Manual con SQL Scripts
```sql
-- 1. Ejecutar en SQL Server Management Studio
-- El archivo: 01_extract_sqlserver_schema.sql

-- 2. Crear scripts de migración manualmente
-- 3. Ejecutar en MySQL
```

## ⚙️ CONFIGURACIÓN REQUERIDA

### SQL Server:
- Servidor: `localhost` (o tu servidor)
- Base de datos: `Hemodialisis`
- Usuario con permisos de lectura

### MySQL:
- Servidor: `127.0.0.1:3306`
- Base de datos: `dialisis`
- Usuario: `root`
- Contraseña: (configurada en tu WAMP)

## 📋 PASOS RÁPIDOS

### Para usuarios que prefieren DBeaver:
1. 📖 Leer `GUIA_DBEAVER_MIGRACION.md`
2. 🔧 Configurar conexiones
3. 🚀 Ejecutar migración

### Para usuarios técnicos:
1. ⚙️ Ejecutar `setup.bat`
2. 🔧 Configurar credenciales en el script Python
3. 🚀 Ejecutar `python migrate_sqlserver_to_mysql.py`

## 🔍 VERIFICACIÓN POST-MIGRACIÓN

```sql
-- Verificar en MySQL que las tablas se crearon
USE dialisis;
SHOW TABLES;

-- Contar registros en tabla principal
SELECT COUNT(*) FROM pacientes;

-- Verificar estructura de tabla
DESCRIBE pacientes;
```

## ⚠️ CONSIDERACIONES IMPORTANTES

### Tipos de datos convertidos:
- `nvarchar` → `VARCHAR`
- `datetime2` → `DATETIME`
- `bit` → `BOOLEAN`
- `money` → `DECIMAL(19,4)`
- `uniqueidentifier` → `VARCHAR(36)`

### Cambios en nombres:
- Tablas: se convierten a minúsculas
- Columnas: se convierten a minúsculas
- `[dbo].[tabla]` → `tabla`

### Encoding:
- SQL Server: puede usar diferentes encodings
- MySQL: se configura como `utf8mb4`

## 🛠️ SOLUCIÓN DE PROBLEMAS

### Error: "Driver no encontrado"
```bash
# Instalar ODBC Driver para SQL Server
# Descargar de: https://docs.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server
```

### Error: "Conexión rechazada MySQL"
```bash
# Verificar que WAMP esté ejecutándose
# Verificar credenciales en archivo .env del proyecto Laravel
```

### Error: "Foreign key constraint fails"
```sql
-- Desactivar temporalmente foreign keys en MySQL
SET FOREIGN_KEY_CHECKS = 0;
-- ... ejecutar inserts ...
SET FOREIGN_KEY_CHECKS = 1;
```

### Error: "Character set issues"
```sql
-- Configurar charset en MySQL antes de la migración
ALTER DATABASE dialisis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 📞 SOPORTE

Si encuentras problemas:

1. 📋 Verificar prerequisitos
2. 📖 Revisar logs de error
3. 🔍 Consultar la guía de DBeaver
4. 🛠️ Usar scripts alternativos

## ✅ CHECKLIST DE MIGRACIÓN

- [ ] Backup de base de datos SQL Server
- [ ] Base de datos MySQL `dialisis` creada
- [ ] Conexiones configuradas y probadas
- [ ] Migración ejecutada (estructura)
- [ ] Migración ejecutada (datos)
- [ ] Verificación de conteos de registros
- [ ] Verificación de integridad referencial
- [ ] Modelos Laravel actualizados
- [ ] Aplicación funcionando correctamente

## 🎯 RESULTADO ESPERADO

Al finalizar, tendrás:
- ✅ Base de datos `dialisis` en MySQL con toda la estructura
- ✅ Todos los datos migrados desde SQL Server
- ✅ Relaciones entre tablas preservadas
- ✅ Modelos Laravel funcionando correctamente
- ✅ Sistema listo para usar con Filament PHP

---

**¡Tu sistema de hemodiálisis estará listo para funcionar en Laravel + MySQL!**
