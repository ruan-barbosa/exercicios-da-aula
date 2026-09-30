import sys
import database
import modulo_auth
import modulo_produto
import modulo_estoque


def exibir_menu_principal(usuario_logado):
    """
    Exibe o menu principal do sistema e gerencia a navegação.
    Representa a entrega 3.5 da avaliação.
    O laço 'while True' mantém o menu ativo até o usuário
    escolher sair ou fazer logout.
    """
    while True:
        print("\n" + "=" * 45)
        print("         SISTEMA DE GESTÃO - SAEP")
        print("=" * 45)

        print(f"Usuário logado: {usuario_logado['nome']}\n")

        print("1. Cadastro e Gestão de Produtos")
        print("2. Gestão de Estoque")
        print("3. Fazer logout")
        print("4. Encerrar Sistema")
        print("-" * 45)

        opcao = input("Escolha uma opção: ").strip()

        if opcao == "1":
            modulo_produto.menu()

        elif opcao == "2":
            modulo_estoque.menu()

        elif opcao == "3":
            print("\nLogout realizado com sucesso.")
            return  # volta para a tela de acesso

        elif opcao == "4":
            print("\nEncerrando o sistema. Até logo!")
            sys.exit(0)

        else:
            print("\n[Erro] Opção inválida. Digite um número de 1 a 4.")


def iniciar_sistema():
    print("Inicializando componentes do sistema")
    database.inicializar_banco()

    while True:
        print("\n--- TELA DE ACESSO ---")

        usuario_logado = modulo_auth.login()

        if usuario_logado:
            exibir_menu_principal(usuario_logado)
        else:
            print("\nSistema encerrado.")
            break

if __name__ == "__main__":
    iniciar_sistema()