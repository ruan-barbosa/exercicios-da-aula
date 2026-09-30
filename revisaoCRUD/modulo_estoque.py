import database
from datetime import datetime

def exibir_produtos_alfabetico():
    """
    Lista os produtos cadastrados ordenados alfabeticamente pelo nome.
    Cumpre a exigência inicial da Entrega 3.7.
    """
    try:
        conn = database.conectar()
        cursor = conn.cursor()
        
        # A cláusula 'ORDER BY nome ASC' garante a ordem alfabética exigida na prova
        query = "SELECT id, nome, estoque_minimo FROM produtos ORDER BY nome ASC"
        cursor.execute(query)
        produtos = cursor.fetchall()
        conn.close()
        
        print("\n" + "-"*50)
        print(f"{'ID':<5} | {'NOME DO PRODUTO (Ordem Alfabética)':<30} | {'EST. MÍN'}")
        print("-" * 50)
        
        for p in produtos:
            print(f"{p['id']:<5} | {p['nome']:<30} | {p['estoque_minimo']}")
        print("-" * 50)
        
        return [p['id'] for p in produtos] # Retorna lista de IDs para validação posterior
        
    except Exception as e:
        print(f"\n[Erro] Falha ao listar produtos: {e}")
        return []

def calcular_estoque_atual(produto_id, conn):
    """
    Calcula a quantidade atual do produto somando as entradas e subtraindo as saídas.
    Essa função é essencial para a verificação do estoque mínimo.
    """
    cursor = conn.cursor()
    query = """
        SELECT 
            SUM(CASE WHEN tipo_movimentacao = 'E' THEN quantidade ELSE 0 END) - 
            SUM(CASE WHEN tipo_movimentacao = 'S' THEN quantidade ELSE 0 END) AS total
        FROM estoque
        WHERE produto_id = ?
    """
    cursor.execute(query, (produto_id,))
    resultado = cursor.fetchone()
    
    # Retorna o total calculado ou 0 se não houver movimentações
    return resultado['total'] if resultado['total'] else 0

def registrar_movimentacao():
    """
    Fluxo principal para registrar uma entrada ou saída no estoque.
    Valida a data, realiza a transação e verifica se o estoque ficou abaixo do mínimo.
    """
    print("\n=== REGISTRAR MOVIMENTAÇÃO DE ESTOQUE ===")
    
    # Exibe a lista alfabética e guarda os IDs válidos
    ids_validos = exibir_produtos_alfabetico()
    
    if not ids_validos:
        print("Não há produtos cadastrados para movimentar.")
        return
        
    # Seleção do Produto
    try:
        produto_id = int(input("\nDigite o ID do produto para movimentar: ").strip())
        if produto_id not in ids_validos:
            print("[Erro] ID de produto inexistente.")
            return
    except ValueError:
        print("[Erro de Validação] O ID deve ser um número inteiro.")
        return
        
    # Seleção do Tipo de Movimentação (Entrada ou Saída)
    tipo_mov = input("Tipo de movimentação (E - Entrada / S - Saída): ").strip().upper()
    if tipo_mov not in ['E', 'S']:
        print("[Erro] Tipo inválido. Digite 'E' para Entrada ou 'S' para Saída.")
        return
        
    # Definição da Quantidade
    try:
        quantidade = int(input("Quantidade: ").strip())
        if quantidade <= 0:
            print("[Erro] A quantidade deve ser maior que zero.")
            return
    except ValueError:
        print("[Erro de Validação] A quantidade deve ser um número inteiro.")
        return
        
    # Definição e Validação da Data (Requisito de campo tipo DATE)
    while True:
        data_input = input("Data da movimentação (YYYY-MM-DD): ").strip()
        try:
            # O datetime.strptime faz a validação rigorosa do formato de data
            datetime.strptime(data_input, '%Y-%m-%d')
            break
        except ValueError:
            print("[Erro de Validação] Formato de data incorreto. Utilize o formato YYYY-MM-DD (ex: 2026-05-21).")
            
    # Inserção no banco de dados e verificação da regra de negócio
    try:
        conn = database.conectar()
        cursor = conn.cursor()
        
        # 1. Registra a movimentação na tabela 'estoque'
        query_insert = """
            INSERT INTO estoque (produto_id, tipo_movimentacao, quantidade, data_movimentacao)
            VALUES (?, ?, ?, ?)
        """
        cursor.execute(query_insert, (produto_id, tipo_mov, quantidade, data_input))
        
        # 2. Busca os dados do produto para saber qual é o limite do estoque mínimo
        cursor.execute("SELECT nome, estoque_minimo FROM produtos WHERE id = ?", (produto_id,))
        produto_info = cursor.fetchone()
        estoque_minimo = produto_info['estoque_minimo']
        nome_produto = produto_info['nome']
        
        # 3. Calcula como ficou o estoque após a transação
        estoque_atual = calcular_estoque_atual(produto_id, conn)
        
        conn.commit()
        conn.close()
        
        print("\n✅ Movimentação registrada com sucesso!")
        
        # 4. Regra de Negócio: Alerta automático de estoque mínimo
        # O manual exige que a verificação automática ocorra após a transação
        if tipo_mov == 'S' and estoque_atual < estoque_minimo:
            print("\n" + "!"*50)
            print("                ALERTA DE ESTOQUE")
            print("!"*50)
            print(f"ATENÇÃO: A saída deixou o produto '{nome_produto}'")
            print(f"abaixo do estoque mínimo!")
            print(f"Estoque Mínimo: {estoque_minimo} | Estoque Atual: {estoque_atual}")
            print("!"*50 + "\n")
            
    except Exception as e:
        print(f"\n[Erro] Falha ao registrar movimentação no banco de dados: {e}")

def menu():
    """
    Controla o fluxo da interface de gestão de estoque.
    """
    while True:
        print("\n=== MÓDULO DE ESTOQUE ===")
        print("1. Registrar Nova Movimentação (Entrada/Saída)")
        print("2. Consultar Lista Alfabética de Produtos")
        print("3. Voltar ao Menu Principal")
        
        opcao = input("Escolha uma opção: ")
        
        if opcao == '1':
            registrar_movimentacao()
        elif opcao == '2':
            exibir_produtos_alfabetico()
        elif opcao == '3':
            print("\nRetornando ao menu principal...")
            break
        else:
            print("\n[Erro] Opção inválida. Tente novamente.")