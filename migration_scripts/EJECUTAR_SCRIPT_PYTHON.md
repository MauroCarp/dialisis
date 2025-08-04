# 🚀 GUÍA RÁPIDA: Ejecutar Script de Migración Python

## PASO 1: Configurar Credenciales SQL Server

Abre el archivo `migrate_sqlserver_to_mysql.py` y modifica estas líneas (línea 31-37):

```python
SQL_SERVER_CONFIG = {
    'server': 'localhost',  # O tu servidor SQL Server
    'database': 'Hemodialisis',
    'username': '',  # Déjalo vacío para Windows Authentication
    'password': '',  # Déjalo vacío para Windows Authentication
    'driver': '{ODBC Driver 17 for SQL Server}'
}
```

### Opciones de conexión:

#### A) Windows Authentication (Recomendado):
```python
'username': '',  # Vacío
'password': '',  # Vacío
```

#### B) SQL Server Authentication:
```python
'username': 'tu_usuario',  # Tu usuario SQL Server
'password': 'tu_contraseña',  # Tu contraseña SQL Server
```

#### C) Si usas SQL Server Express:
```python
'server': 'localhost\\SQLEXPRESS',  # Nota las dobles barras
```

## PASO 2: Verificar Driver ODBC

Ejecuta en PowerShell para ver qué drivers tienes:
```powershell
Get-OdbcDriver | Where-Object {$_.Name -like "*SQL Server*"}
```

Drivers comunes:
- `{ODBC Driver 18 for SQL Server}` (más nuevo)
- `{ODBC Driver 17 for SQL Server}`
- `{SQL Server Native Client 11.0}` (más antiguo)

## PASO 3: Ejecutar el Script

```powershell
cd C:\wamp64\www\dialisis\migration_scripts
py migrate_sqlserver_to_mysql.py
```

## PASO 4: Verificar Resultados

El script creará:
- `migration.log` - Log detallado
- Tablas en MySQL con todos los datos

## ⚠️ PROBLEMAS COMUNES

### Error: "Driver not found"
- Instalar ODBC Driver 17/18 for SQL Server desde Microsoft

### Error: "Login failed"
- Verificar credenciales
- Asegurar que SQL Server acepta conexiones remotas
- Verificar que el usuario tiene permisos

### Error: "Database not found"
- Verificar que la base "Hemodialisis" existe
- Verificar el nombre exacto (case sensitive)

## ✅ VERIFICACIÓN RÁPIDA

Después de la migración, ejecuta en MySQL:
```sql
USE dialisis;
SHOW TABLES;
SELECT COUNT(*) FROM pacientes;  -- Debe tener datos
```
