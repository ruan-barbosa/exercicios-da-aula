saldo = 1000

while True:
    opcao = input("O que deseja fazer?\n(1) Ver saldo\n(2) Depositar\n(3) Sacar\n(4) Sair: ")
    if opcao == "1":
        print(f"Saldo atual:\nR${saldo:.2f}")
    
    elif opcao	== "2":
        valor_deposito = float(input("Quanto deseja depositar: "))
        if valor_deposito > 0:
            saldo += valor_deposito
            print("Depósito realizado com sucesso!") 
        else:
            print("Valor inválido!")

    elif opcao == "3":
        valor_saque = float(input("Quanto deseja sacar: "))
        if valor_saque <= 0:
            print("Valor inválido!")
        elif valor_saque > saldo:
            print("Saldo insuficiente!")
        else:
            saldo -= valor_saque
            print(f"Saque no valor de {valor_saque} realizado com sucesso!")

    elif opcao == "4":
        break

    else:
        print("Opção inválida")