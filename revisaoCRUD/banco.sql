-- Criação da atbela de usuarios
create table if not exists usuarios (
    id int primary key auto_increment,
    nome varchar(50) not null,
    login varchar(50) not null,
    senha varchar(50) not null
);

create table if not exists produtos (
    id int primary key auto_increment,
    nome varchar(50) not null,
    descricao varchar(100),
    preco real not null,
    estoque_minimo int not null
)

create table if not exists estoque (
    id int primary key auto_increment,
    produto_id int not null,
    tipo_movimentacao varchar(20) check(tipo_movimentacao in ('E', 'S')) not null,
    quantidade int not null,
    foreign key (produto_id) references produtos(id)
)