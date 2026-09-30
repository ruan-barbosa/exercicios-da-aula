import database 

def validar_credenciais(login, senha):
    """
    Comunica-se com o Banco de dados para verificar se o usuário
    existe e se asenha está correta.
    Retorna o registro do usuário se encontrar, ou None se falhar.
    """
    try:
        conn = database.conectar()
        cursor =conn.cursor()

        # Utiliza parâmetros (?) na query para previnir ataques de SQL Injection
        query = "SELECT * FROM usuarios WHERE login = ? AND senha = ?"
        cursor.excecute(query, (login, senha))

        # Fetchone() retorna aprimeira linha que bater com a consulta, ou None
        usuario = cursor.fetchone()

        # Fecha a conexão após a consulta para liberar recursos
        conn.close()

        # Converte a linha do banco (sqllite.Row) para um dicionário padrão
        # Isso facilita o manuseio dos dados no main.py
        if usuario:
            return dict(usuario)
        return None

    except Exception as e:
        print(f"\n[Erro interno] Falha ao consultar o banco de dados: {e}")
        return None

def login():
    '''
    Fluxo principal de interface (CLI) para autenticação.
    Mantém o usuário em um laço até que ele acerte as credenciais ou escolha sair.
    '''
    while True:
        print("\n" + "=" * 45)
        print("              Login de Acesso")
        print("=" * 45)
        print("Dica: Digite 'sair' no campo de login para encerrar.")

        # captura entrada do usuário
        login_input = input("Login: ").strip()

        # Permite que o usuário desista e feche o sistema
        if login_input.lower() == 'sair':
            return None

        senha_input = input("Senha: ").strip()

        # Verifica se os campos não foram deixados em branco
        if not login_input or not senha_input:
            print("\n[Falha] Os campos de login e senha não podem ficar vazios.")
            continue
        # Chama a função de validação no banco de dados
        usuario_logado = validar_credenciais(login_input, senha_input)

        if usuario_logado:
            print("\nAutenticação realizada com sucesso!")
            # Retorna o dicionario com os dados do usuário para o main.py
            return usuario_logado
        else:
            # Requisito da prova: em caso de erro, exibir mensagem com o motivo da falha
            print("\n[Falha no login] Motivo: Usuário não encontrado ou senha incorreta. Tente novamente.")