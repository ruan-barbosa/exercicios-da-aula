import database

def exibir_lista_produtos(termo_pesquisa=None):
    """
    Consulta e exibe a lista de produtos cadastrados.
    Se um 'termo_pesquisa' for fornecido, filtra os resultados pelo nome.
    Atende ao requisito de exibir a lista e permitir pesquisa da Entrega 3.6.
    """
    try:
        conn = database.conectar()
        cursor = conn.cursor()
        
        if termo_pesquisa:
            # Uso do LIKE para buscar qualquer parte do nome do produto
            query = "SELECT * FROM produtos WHERE nome LIKE ?"
            cursor.execute(query, (f'%{termo_pesquisa}%',))
        else:
            # Busca todos os registros caso não haja filtro
            query = "SELECT * FROM produtos"
            cursor.execute(query)
            
        produtos = cursor.fetchall()
        conn.close()
        
        print("\n" + "-"*65)
        print(f"{'ID':<5} | {'NOME DO PRODUTO':<25} | {'PREÇO (R$)':<10} | {'ESTOQUE MÍN.'}")
        print("-" * 65)
        
        if not produtos:
            print("Nenhum produto encontrado.")
        else:
            for p in produtos:
                # Formatando a saída em colunas alinhadas para melhor visualização no terminal
                print(f"{p['id']:<5} | {p['nome']:<25} | {p['preco']:<10.2f} | {p['estoque_minimo']}")
        print("-" * 65)
        
    except Exception as e:
        print(f"\n[Erro] Falha ao listar produtos: {e}")

def cadastrar_produto():
    """
    Realiza o cadastro de um novo produto com validação rigorosa de tipos de dados.
    Atende ao requisito de não aceitar textos em campos numéricos.
    """
    print("\n--- NOVO PRODUTO ---")
    
    # O ID é gerado automaticamente pelo banco de dados (AUTOINCREMENT)
    # Portanto, não solicitamos o preenchimento da chave primária
    nome = input("Nome do produto: ").strip()
    descricao = input("Descrição detalhada: ").strip()
    
    # Validação do campo numérico (Preço) utilizando um laço de repetição
    while True:
        try:
            preco_str = input("Preço (ex: 35.50): ").strip()
            preco = float(preco_str.replace(',', '.')) # Aceita vírgula ou ponto
            if preco < 0:
                print("[Erro] O preço não pode ser negativo.")
                continue
            break # Sai do laço se a conversão der certo
        except ValueError:
            # Bloqueia a inserção de texto/letras no campo numérico
            print("[Erro de Validação] Por favor, insira apenas números válidos para o preço.")

    # Validação do campo numérico inteiro (Estoque Mínimo)
    while True:
        try:
            estoque_minimo = int(input("Estoque mínimo: ").strip())
            if estoque_minimo < 0:
                print("[Erro] O estoque não pode ser negativo.")
                continue
            break
        except ValueError:
            print("[Erro de Validação] Por favor, insira um número inteiro para o estoque mínimo.")
            
    try:
        conn = database.conectar()
        cursor = conn.cursor()
        
        query = """
            INSERT INTO produtos (nome, descricao, preco, estoque_minimo) 
            VALUES (?, ?, ?, ?)
        """
        cursor.execute(query, (nome, descricao, preco, estoque_minimo))
        conn.commit()
        conn.close()
        
        print(f"\n✅ Produto '{nome}' cadastrado com sucesso!")
        
    except Exception as e:
        print(f"\n[Erro] Falha ao cadastrar o produto: {e}")

def menu():
    """
    Controla o fluxo da interface de produtos.
    """
    # A prova exige que, ao acessar a interface, a lista seja exibida imediatamente
    exibir_lista_produtos()
    
    while True:
        print("\n=== GESTÃO DE PRODUTOS ===")
        print("1. Cadastrar Novo Produto")
        print("2. Pesquisar Produto")
        print("3. Exibir Todos os Produtos")
        print("4. Voltar ao Menu Principal")
        
        opcao = input("Escolha uma opção: ")
        
        if opcao == '1':
            cadastrar_produto()
            # A prova exige que o novo item apareça na lista imediatamente após o cadastro
            print("\nAtualizando lista de produtos...")
            exibir_lista_produtos()
            
        elif opcao == '2':
            termo = input("\nDigite o nome (ou parte dele) para pesquisa: ").strip()
            exibir_lista_produtos(termo)
            
        elif opcao == '3':
            exibir_lista_produtos()
            
        elif opcao == '4':
            # Atende à exigência de possuir um botão/opção de retorno à interface principal
            print("\nRetornando ao menu principal...")
            break
            
        else:
            print("\n[Erro] Opção inválida. Tente novamente.")