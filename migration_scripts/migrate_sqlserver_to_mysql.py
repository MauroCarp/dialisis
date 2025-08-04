#!/usr/bin/env python3
"""
Script de migración de SQL Server a MySQL
Base de datos: Hemodialisis -> dialisis (MySQL)

Requiere:
pip install pyodbc pymysql pandas

Uso:
python migrate_sqlserver_to_mysql.py
"""

import pyodbc
import pymysql
import pandas as pd
import sys
import logging
from datetime import datetime

# Configuración de logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('migration.log'),
        logging.StreamHandler(sys.stdout)
    ]
)

# Configuración de conexiones
SQL_SERVER_CONFIG = {
    'server': 'localhost',  # Cambia por tu servidor SQL Server
    'database': 'Hemodialisis',
    'username': '',  # Tu usuario SQL Server (o usa Windows Authentication)
    'password': '',  # Tu contraseña SQL Server
    'driver': '{ODBC Driver 17 for SQL Server}'  # O el driver que tengas instalado
}

MYSQL_CONFIG = {
    'host': '127.0.0.1',
    'port': 3306,
    'user': 'root',
    'password': '',  # Tu contraseña MySQL
    'database': 'dialisis',
    'charset': 'utf8mb4'
}

# Mapeo de tipos de datos SQL Server -> MySQL
TYPE_MAPPING = {
    'int': 'INT',
    'bigint': 'BIGINT',
    'smallint': 'SMALLINT',
    'tinyint': 'TINYINT',
    'bit': 'BOOLEAN',
    'decimal': 'DECIMAL',
    'numeric': 'DECIMAL',
    'money': 'DECIMAL(19,4)',
    'smallmoney': 'DECIMAL(10,4)',
    'float': 'DOUBLE',
    'real': 'FLOAT',
    'datetime': 'DATETIME',
    'datetime2': 'DATETIME',
    'smalldatetime': 'DATETIME',
    'date': 'DATE',
    'time': 'TIME',
    'timestamp': 'TIMESTAMP',
    'char': 'CHAR',
    'varchar': 'VARCHAR',
    'text': 'TEXT',
    'nchar': 'CHAR',
    'nvarchar': 'VARCHAR',
    'ntext': 'TEXT',
    'binary': 'BINARY',
    'varbinary': 'VARBINARY',
    'image': 'LONGBLOB',
    'uniqueidentifier': 'VARCHAR(36)'
}

class DatabaseMigrator:
    def __init__(self):
        self.sql_server_conn = None
        self.mysql_conn = None
        self.tables_info = {}
        
    def connect_sql_server(self):
        """Conectar a SQL Server"""
        try:
            if SQL_SERVER_CONFIG['username']:
                conn_str = (
                    f"DRIVER={SQL_SERVER_CONFIG['driver']};"
                    f"SERVER={SQL_SERVER_CONFIG['server']};"
                    f"DATABASE={SQL_SERVER_CONFIG['database']};"
                    f"UID={SQL_SERVER_CONFIG['username']};"
                    f"PWD={SQL_SERVER_CONFIG['password']}"
                )
            else:
                # Windows Authentication
                conn_str = (
                    f"DRIVER={SQL_SERVER_CONFIG['driver']};"
                    f"SERVER={SQL_SERVER_CONFIG['server']};"
                    f"DATABASE={SQL_SERVER_CONFIG['database']};"
                    f"Trusted_Connection=yes;"
                )
            
            self.sql_server_conn = pyodbc.connect(conn_str)
            logging.info("✅ Conexión exitosa a SQL Server")
            return True
        except Exception as e:
            logging.error(f"❌ Error conectando a SQL Server: {e}")
            return False
    
    def connect_mysql(self):
        """Conectar a MySQL"""
        try:
            self.mysql_conn = pymysql.connect(**MYSQL_CONFIG)
            logging.info("✅ Conexión exitosa a MySQL")
            return True
        except Exception as e:
            logging.error(f"❌ Error conectando a MySQL: {e}")
            return False
    
    def get_sql_server_schema(self):
        """Obtener esquema de SQL Server"""
        logging.info("📊 Extrayendo esquema de SQL Server...")
        
        # Obtener tablas
        tables_query = """
        SELECT TABLE_NAME 
        FROM INFORMATION_SCHEMA.TABLES 
        WHERE TABLE_TYPE = 'BASE TABLE'
        ORDER BY TABLE_NAME
        """
        
        cursor = self.sql_server_conn.cursor()
        cursor.execute(tables_query)
        tables = [row[0] for row in cursor.fetchall()]
        
        # Obtener columnas para cada tabla
        for table in tables:
            columns_query = """
            SELECT 
                COLUMN_NAME,
                DATA_TYPE,
                CHARACTER_MAXIMUM_LENGTH,
                NUMERIC_PRECISION,
                NUMERIC_SCALE,
                IS_NULLABLE,
                COLUMN_DEFAULT
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_NAME = ?
            ORDER BY ORDINAL_POSITION
            """
            
            cursor.execute(columns_query, (table,))
            columns = cursor.fetchall()
            
            self.tables_info[table] = {
                'columns': columns,
                'primary_keys': self.get_primary_keys(table),
                'foreign_keys': self.get_foreign_keys(table)
            }
        
        logging.info(f"📋 Encontradas {len(tables)} tablas")
        return tables
    
    def get_primary_keys(self, table):
        """Obtener claves primarias de una tabla"""
        query = """
        SELECT kcu.COLUMN_NAME
        FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
        JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
            ON tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
        WHERE tc.TABLE_NAME = ? AND tc.CONSTRAINT_TYPE = 'PRIMARY KEY'
        """
        
        cursor = self.sql_server_conn.cursor()
        cursor.execute(query, (table,))
        return [row[0] for row in cursor.fetchall()]
    
    def get_foreign_keys(self, table):
        """Obtener claves foráneas de una tabla"""
        query = """
        SELECT 
            cp.name AS Parent_Column,
            tr.name AS Referenced_Table,
            cr.name AS Referenced_Column
        FROM sys.foreign_keys fk
        INNER JOIN sys.foreign_key_columns fkc ON fk.object_id = fkc.constraint_object_id
        INNER JOIN sys.tables tp ON fkc.parent_object_id = tp.object_id
        INNER JOIN sys.columns cp ON fkc.parent_object_id = cp.object_id AND fkc.parent_column_id = cp.column_id
        INNER JOIN sys.tables tr ON fkc.referenced_object_id = tr.object_id
        INNER JOIN sys.columns cr ON fkc.referenced_object_id = cr.object_id AND fkc.referenced_column_id = cr.column_id
        WHERE tp.name = ?
        """
        
        cursor = self.sql_server_conn.cursor()
        cursor.execute(query, (table,))
        return cursor.fetchall()
    
    def convert_column_type(self, sql_server_type, max_length, precision, scale):
        """Convertir tipo de datos de SQL Server a MySQL"""
        sql_server_type = sql_server_type.lower()
        
        if sql_server_type in TYPE_MAPPING:
            mysql_type = TYPE_MAPPING[sql_server_type]
            
            # Agregar longitud para tipos que la requieren
            if sql_server_type in ['char', 'varchar', 'nchar', 'nvarchar'] and max_length:
                if max_length == -1:  # MAX en SQL Server
                    mysql_type = 'TEXT'
                else:
                    mysql_type = f"{mysql_type}({max_length})"
            elif sql_server_type in ['decimal', 'numeric'] and precision:
                if scale:
                    mysql_type = f"DECIMAL({precision},{scale})"
                else:
                    mysql_type = f"DECIMAL({precision})"
            elif sql_server_type in ['binary', 'varbinary'] and max_length:
                mysql_type = f"{mysql_type}({max_length})"
                
            return mysql_type
        else:
            logging.warning(f"⚠️ Tipo no mapeado: {sql_server_type}, usando TEXT")
            return 'TEXT'
    
    def create_mysql_tables(self):
        """Crear tablas en MySQL"""
        logging.info("🏗️ Creando tablas en MySQL...")
        
        cursor = self.mysql_conn.cursor()
        
        # Desactivar checks de foreign keys temporalmente
        cursor.execute("SET FOREIGN_KEY_CHECKS = 0")
        
        for table_name, table_info in self.tables_info.items():
            # Crear tabla
            create_sql = f"CREATE TABLE IF NOT EXISTS `{table_name.lower()}` (\n"
            
            columns_sql = []
            for col in table_info['columns']:
                col_name, data_type, max_length, precision, scale, is_nullable, default = col
                
                mysql_type = self.convert_column_type(data_type, max_length, precision, scale)
                nullable = "NULL" if is_nullable == 'YES' else "NOT NULL"
                
                # Auto increment para IDs
                auto_increment = ""
                if col_name.lower() == 'id' and data_type.lower() in ['int', 'bigint']:
                    auto_increment = "AUTO_INCREMENT"
                
                col_sql = f"  `{col_name.lower()}` {mysql_type} {nullable} {auto_increment}"
                columns_sql.append(col_sql.strip())
            
            create_sql += ",\n".join(columns_sql)
            
            # Agregar primary key
            if table_info['primary_keys']:
                pk_columns = [f"`{pk.lower()}`" for pk in table_info['primary_keys']]
                create_sql += f",\n  PRIMARY KEY ({', '.join(pk_columns)})"
            
            create_sql += "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
            
            try:
                cursor.execute(create_sql)
                logging.info(f"✅ Tabla creada: {table_name}")
            except Exception as e:
                logging.error(f"❌ Error creando tabla {table_name}: {e}")
        
        # Reactivar checks de foreign keys
        cursor.execute("SET FOREIGN_KEY_CHECKS = 1")
        self.mysql_conn.commit()
    
    def migrate_data(self):
        """Migrar datos de SQL Server a MySQL"""
        logging.info("📦 Migrando datos...")
        
        for table_name in self.tables_info.keys():
            try:
                # Leer datos de SQL Server
                query = f"SELECT * FROM [{table_name}]"
                df = pd.read_sql(query, self.sql_server_conn)
                
                if df.empty:
                    logging.info(f"⚪ Tabla {table_name} está vacía")
                    continue
                
                # Convertir nombres de columnas a minúsculas
                df.columns = df.columns.str.lower()
                
                # Insertar en MySQL
                cursor = self.mysql_conn.cursor()
                
                # Preparar query de inserción
                columns = ', '.join([f"`{col}`" for col in df.columns])
                placeholders = ', '.join(['%s'] * len(df.columns))
                insert_query = f"INSERT INTO `{table_name.lower()}` ({columns}) VALUES ({placeholders})"
                
                # Insertar por lotes
                batch_size = 1000
                total_rows = len(df)
                
                for i in range(0, total_rows, batch_size):
                    batch = df.iloc[i:i+batch_size]
                    values = [tuple(row) for row in batch.values]
                    cursor.executemany(insert_query, values)
                
                self.mysql_conn.commit()
                logging.info(f"✅ Migrados {total_rows} registros de {table_name}")
                
            except Exception as e:
                logging.error(f"❌ Error migrando tabla {table_name}: {e}")
                self.mysql_conn.rollback()
    
    def run_migration(self):
        """Ejecutar migración completa"""
        logging.info("🚀 Iniciando migración SQL Server -> MySQL")
        
        # Conectar a ambas bases de datos
        if not self.connect_sql_server():
            return False
        
        if not self.connect_mysql():
            return False
        
        try:
            # Obtener esquema
            tables = self.get_sql_server_schema()
            
            # Crear tablas
            self.create_mysql_tables()
            
            # Migrar datos
            self.migrate_data()
            
            logging.info("🎉 Migración completada exitosamente!")
            return True
            
        except Exception as e:
            logging.error(f"❌ Error durante la migración: {e}")
            return False
        
        finally:
            if self.sql_server_conn:
                self.sql_server_conn.close()
            if self.mysql_conn:
                self.mysql_conn.close()

if __name__ == "__main__":
    print("🔄 Migrador SQL Server -> MySQL")
    print("=" * 50)
    
    # Verificar configuración
    print("\n📋 Configuración:")
    print(f"SQL Server: {SQL_SERVER_CONFIG['server']}/{SQL_SERVER_CONFIG['database']}")
    print(f"MySQL: {MYSQL_CONFIG['host']}:{MYSQL_CONFIG['port']}/{MYSQL_CONFIG['database']}")
    
    response = input("\n¿Continuar con la migración? (s/n): ")
    if response.lower() != 's':
        print("❌ Migración cancelada")
        sys.exit(0)
    
    # Ejecutar migración
    migrator = DatabaseMigrator()
    success = migrator.run_migration()
    
    if success:
        print("\n🎉 ¡Migración completada exitosamente!")
    else:
        print("\n❌ Migración falló. Revisa los logs para más detalles.")
