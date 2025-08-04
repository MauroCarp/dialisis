@echo off
echo ================================================
echo    CONFIGURACION PARA MIGRACION SQL SERVER -> MYSQL
echo ================================================

echo.
echo 1. Instalando dependencias Python...
pip install -r requirements.txt

echo.
echo 2. Verificando drivers ODBC...
echo.
echo Drivers ODBC instalados:
driverquery | findstr /i "odbc"

echo.
echo ================================================
echo SIGUIENTE PASO:
echo ================================================
echo 1. Ejecuta el script 01_extract_sqlserver_schema.sql en SQL Server Management Studio
echo 2. Guarda los resultados en archivos de texto
echo 3. Modifica las credenciales en migrate_sqlserver_to_mysql.py
echo 4. Ejecuta: python migrate_sqlserver_to_mysql.py
echo.
echo NOTA: Si no tienes ODBC Driver 17 for SQL Server, descargalo de:
echo https://docs.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server
echo.
pause
