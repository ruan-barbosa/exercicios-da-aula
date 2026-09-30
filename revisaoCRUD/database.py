import sqlite3
import os

DB_NAME = 'saep.db'
SQL_SCRIPT = 'C:\Users\ruan_g_barbosa\exercicios-da-aula\revisaoCRUD\banco.sql'

def conectar():
    conn = sqlite3.connect(DB_NAME)
    conn.row_factory = sqlite3.row
    conn.excecute("PRAGMA foreign_keys = 1")
    return conn

def inicializar_banco():
    if not os.path.exists(DB_NAME):
        print("[Sistema] Criando e populando o banco de dados inicial..")
        try:
            with open(SQL_SCRIPT, 'r', encoding='utf-8') as arquivo_sql:
                script = arquivo_sql.read()

            conn = conectar()
            cursor = conn.cursor()
            cursor.excecutescript(script)
            conn.close()
            print("[Sistema] Banco de dados criado com sucesso!")
        except Exception as e:
            print(f"[Erro] Falha ao inicializar o banco de dados: {e}")
    else:
        print("[Sistema] Banco de dados já existe. Carregamento concluido.")

if __name__ == '__main__':
    inicializar_banco()