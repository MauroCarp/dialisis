# Script PowerShell para migración SQL Server -> MySQL
# Requiere SQL Server PowerShell Module y MySQL .NET Connector

param(
    [string]$SqlServerInstance = "localhost",
    [string]$SqlServerDatabase = "Hemodialisis",
    [string]$MySqlServer = "127.0.0.1",
    [string]$MySqlDatabase = "dialisis",
    [string]$MySqlUser = "root",
    [string]$MySqlPassword = ""
)

Write-Host "🚀 Iniciando migración SQL Server -> MySQL" -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Cyan

# Función para convertir tipos de datos
function Convert-SqlServerTypeToMySQL {
    param([string]$SqlServerType, [int]$MaxLength, [int]$Precision, [int]$Scale)
    
    switch ($SqlServerType.ToLower()) {
        "int" { return "INT" }
        "bigint" { return "BIGINT" }
        "smallint" { return "SMALLINT" }
        "tinyint" { return "TINYINT" }
        "bit" { return "BOOLEAN" }
        "decimal" { 
            if ($Scale -gt 0) { return "DECIMAL($Precision,$Scale)" }
            else { return "DECIMAL($Precision)" }
        }
        "numeric" { 
            if ($Scale -gt 0) { return "DECIMAL($Precision,$Scale)" }
            else { return "DECIMAL($Precision)" }
        }
        "money" { return "DECIMAL(19,4)" }
        "smallmoney" { return "DECIMAL(10,4)" }
        "float" { return "DOUBLE" }
        "real" { return "FLOAT" }
        "datetime" { return "DATETIME" }
        "datetime2" { return "DATETIME" }
        "smalldatetime" { return "DATETIME" }
        "date" { return "DATE" }
        "time" { return "TIME" }
        "char" { 
            if ($MaxLength -gt 0) { return "CHAR($MaxLength)" }
            else { return "CHAR(255)" }
        }
        "varchar" { 
            if ($MaxLength -eq -1) { return "TEXT" }
            elseif ($MaxLength -gt 0) { return "VARCHAR($MaxLength)" }
            else { return "VARCHAR(255)" }
        }
        "nchar" { 
            if ($MaxLength -gt 0) { return "CHAR($MaxLength)" }
            else { return "CHAR(255)" }
        }
        "nvarchar" { 
            if ($MaxLength -eq -1) { return "TEXT" }
            elseif ($MaxLength -gt 0) { return "VARCHAR($MaxLength)" }
            else { return "VARCHAR(255)" }
        }
        "text" { return "TEXT" }
        "ntext" { return "TEXT" }
        "uniqueidentifier" { return "VARCHAR(36)" }
        default { 
            Write-Warning "Tipo no reconocido: $SqlServerType, usando TEXT"
            return "TEXT" 
        }
    }
}

try {
    # Importar módulos necesarios
    Write-Host "📦 Cargando módulos..." -ForegroundColor Yellow
    
    # Verificar SQL Server PowerShell
    if (!(Get-Module -ListAvailable -Name SqlServer)) {
        Write-Host "❌ Módulo SqlServer no encontrado. Instalando..." -ForegroundColor Red
        Install-Module -Name SqlServer -Force -AllowClobber
    }
    Import-Module SqlServer -Force

    # Conectar a SQL Server
    Write-Host "🔌 Conectando a SQL Server..." -ForegroundColor Yellow
    $SqlConnection = New-Object System.Data.SqlClient.SqlConnection
    $SqlConnection.ConnectionString = "Server=$SqlServerInstance;Database=$SqlServerDatabase;Integrated Security=True;"
    $SqlConnection.Open()
    
    Write-Host "✅ Conectado a SQL Server: $SqlServerInstance/$SqlServerDatabase" -ForegroundColor Green

    # Obtener lista de tablas
    Write-Host "📋 Obteniendo estructura de tablas..." -ForegroundColor Yellow
    
    $TablesQuery = @"
    SELECT TABLE_NAME 
    FROM INFORMATION_SCHEMA.TABLES 
    WHERE TABLE_TYPE = 'BASE TABLE'
    ORDER BY TABLE_NAME
"@

    $SqlCommand = New-Object System.Data.SqlClient.SqlCommand($TablesQuery, $SqlConnection)
    $SqlAdapter = New-Object System.Data.SqlClient.SqlDataAdapter($SqlCommand)
    $TablesDataSet = New-Object System.Data.DataSet
    $SqlAdapter.Fill($TablesDataSet) | Out-Null
    
    $Tables = $TablesDataSet.Tables[0] | Select-Object -ExpandProperty TABLE_NAME
    Write-Host "📊 Encontradas $($Tables.Count) tablas" -ForegroundColor Green

    # Generar scripts de creación para MySQL
    Write-Host "🏗️ Generando scripts de creación para MySQL..." -ForegroundColor Yellow
    
    $MySqlScript = @"
-- Script generado automáticamente para migración SQL Server -> MySQL
-- Base de datos origen: $SqlServerDatabase
-- Base de datos destino: $MySqlDatabase
-- Fecha: $(Get-Date)

USE `$MySqlDatabase`;

SET FOREIGN_KEY_CHECKS = 0;

"@

    foreach ($Table in $Tables) {
        Write-Host "  📝 Procesando tabla: $Table" -ForegroundColor Cyan
        
        # Obtener columnas de la tabla
        $ColumnsQuery = @"
        SELECT 
            COLUMN_NAME,
            DATA_TYPE,
            CHARACTER_MAXIMUM_LENGTH,
            NUMERIC_PRECISION,
            NUMERIC_SCALE,
            IS_NULLABLE,
            COLUMN_DEFAULT
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_NAME = '$Table'
        ORDER BY ORDINAL_POSITION
"@

        $SqlCommand.CommandText = $ColumnsQuery
        $ColumnsDataSet = New-Object System.Data.DataSet
        $SqlAdapter.Fill($ColumnsDataSet) | Out-Null
        
        $Columns = $ColumnsDataSet.Tables[0]
        
        # Obtener primary keys
        $PrimaryKeysQuery = @"
        SELECT kcu.COLUMN_NAME
        FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
        JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
            ON tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
        WHERE tc.TABLE_NAME = '$Table' AND tc.CONSTRAINT_TYPE = 'PRIMARY KEY'
"@

        $SqlCommand.CommandText = $PrimaryKeysQuery
        $PrimaryKeysDataSet = New-Object System.Data.DataSet
        $SqlAdapter.Fill($PrimaryKeysDataSet) | Out-Null
        
        $PrimaryKeys = $PrimaryKeysDataSet.Tables[0] | Select-Object -ExpandProperty COLUMN_NAME

        # Generar CREATE TABLE
        $MySqlScript += "`n-- Tabla: $Table`n"
        $MySqlScript += "CREATE TABLE IF NOT EXISTS ``$($Table.ToLower())`` (`n"
        
        $ColumnDefinitions = @()
        foreach ($Column in $Columns) {
            $ColumnName = $Column.COLUMN_NAME.ToLower()
            $DataType = $Column.DATA_TYPE
            $MaxLength = if ($Column.CHARACTER_MAXIMUM_LENGTH -eq [DBNull]::Value) { 0 } else { [int]$Column.CHARACTER_MAXIMUM_LENGTH }
            $Precision = if ($Column.NUMERIC_PRECISION -eq [DBNull]::Value) { 0 } else { [int]$Column.NUMERIC_PRECISION }
            $Scale = if ($Column.NUMERIC_SCALE -eq [DBNull]::Value) { 0 } else { [int]$Column.NUMERIC_SCALE }
            $IsNullable = $Column.IS_NULLABLE -eq 'YES'
            
            $MySqlType = Convert-SqlServerTypeToMySQL -SqlServerType $DataType -MaxLength $MaxLength -Precision $Precision -Scale $Scale
            $NullClause = if ($IsNullable) { "NULL" } else { "NOT NULL" }
            
            # Auto increment para columnas ID
            $AutoIncrement = ""
            if ($ColumnName -eq "id" -and $DataType -match "int") {
                $AutoIncrement = "AUTO_INCREMENT"
            }
            
            $ColumnDefinitions += "  ``$ColumnName`` $MySqlType $NullClause $AutoIncrement".Trim()
        }
        
        $MySqlScript += ($ColumnDefinitions -join ",`n")
        
        # Agregar primary key
        if ($PrimaryKeys.Count -gt 0) {
            $PkColumns = ($PrimaryKeys | ForEach-Object { "``$($_.ToLower())``" }) -join ", "
            $MySqlScript += ",`n  PRIMARY KEY ($PkColumns)"
        }
        
        $MySqlScript += "`n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;`n"
    }

    $MySqlScript += "`nSET FOREIGN_KEY_CHECKS = 1;`n"

    # Guardar script de estructura
    $StructureScriptPath = "mysql_structure.sql"
    $MySqlScript | Out-File -FilePath $StructureScriptPath -Encoding UTF8
    Write-Host "✅ Script de estructura guardado: $StructureScriptPath" -ForegroundColor Green

    # Generar scripts de datos
    Write-Host "📦 Generando scripts de datos..." -ForegroundColor Yellow
    
    $DataScript = "-- Datos para migración`nUSE ``$MySqlDatabase``;`n`nSET FOREIGN_KEY_CHECKS = 0;`n"
    
    foreach ($Table in $Tables) {
        Write-Host "  📊 Extrayendo datos de: $Table" -ForegroundColor Cyan
        
        # Obtener datos
        $DataQuery = "SELECT * FROM [$Table]"
        $SqlCommand.CommandText = $DataQuery
        $DataSet = New-Object System.Data.DataSet
        $SqlAdapter.Fill($DataSet) | Out-Null
        
        if ($DataSet.Tables[0].Rows.Count -eq 0) {
            Write-Host "    ⚪ Tabla vacía" -ForegroundColor Gray
            continue
        }
        
        $DataScript += "`n-- Datos de tabla: $Table`n"
        
        # Generar INSERTs
        $ColumnNames = ($DataSet.Tables[0].Columns | ForEach-Object { "``$($_.ColumnName.ToLower())``" }) -join ", "
        
        foreach ($Row in $DataSet.Tables[0].Rows) {
            $Values = @()
            foreach ($Item in $Row.ItemArray) {
                if ($Item -eq [DBNull]::Value -or $Item -eq $null) {
                    $Values += "NULL"
                } elseif ($Item -is [string]) {
                    $EscapedValue = $Item.Replace("'", "''").Replace("\", "\\")
                    $Values += "'$EscapedValue'"
                } elseif ($Item -is [datetime]) {
                    $Values += "'$($Item.ToString('yyyy-MM-dd HH:mm:ss'))'"
                } elseif ($Item -is [bool]) {
                    $Values += if ($Item) { "1" } else { "0" }
                } else {
                    $Values += "$Item"
                }
            }
            
            $ValuesString = $Values -join ", "
            $DataScript += "INSERT INTO ``$($Table.ToLower())`` ($ColumnNames) VALUES ($ValuesString);`n"
        }
        
        Write-Host "    ✅ $($DataSet.Tables[0].Rows.Count) registros procesados" -ForegroundColor Green
    }
    
    $DataScript += "`nSET FOREIGN_KEY_CHECKS = 1;`n"
    
    # Guardar script de datos
    $DataScriptPath = "mysql_data.sql"
    $DataScript | Out-File -FilePath $DataScriptPath -Encoding UTF8
    Write-Host "✅ Script de datos guardado: $DataScriptPath" -ForegroundColor Green

    Write-Host "`n🎉 Migración completada exitosamente!" -ForegroundColor Green
    Write-Host "📁 Archivos generados:" -ForegroundColor Cyan
    Write-Host "  - $StructureScriptPath (estructura de tablas)" -ForegroundColor White
    Write-Host "  - $DataScriptPath (datos)" -ForegroundColor White
    Write-Host "`n📝 Próximos pasos:" -ForegroundColor Yellow
    Write-Host "  1. Ejecutar $StructureScriptPath en MySQL" -ForegroundColor White
    Write-Host "  2. Ejecutar $DataScriptPath en MySQL" -ForegroundColor White
    Write-Host "  3. Verificar la migración" -ForegroundColor White

} catch {
    Write-Host "❌ Error durante la migración: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host $_.ScriptStackTrace -ForegroundColor Red
} finally {
    if ($SqlConnection.State -eq 'Open') {
        $SqlConnection.Close()
        Write-Host "🔌 Conexión SQL Server cerrada" -ForegroundColor Gray
    }
}
