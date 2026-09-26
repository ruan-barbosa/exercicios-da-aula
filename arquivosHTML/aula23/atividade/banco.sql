create database if not exists escola_formulario
character set utf8mb4
collate utf8mb4_unicode_ci;

use escola_formulario;

create table if not exists alunos (
    id int auto_increment primary key,
    cpf varchar(11) not null,
    nome varchar(100) not null,
    idade int not null,
    email varchar(120) not null,
    telefone varchar(12) not null,
    endereco varchar(100) not null,
    curso varchar(100) not null,
    turma varchar (50) not null,
    criado_em timestamp default current_timestamp
);

create table if not exists universidades (
    id_uni int auto_increment primary key,
	nome varchar(100) not null,
    endereco varchar(100) not null,
    cnpj varchar(14) not null,
	telefone varchar(12) not null
);

select * from alunos;
select * from universidades;