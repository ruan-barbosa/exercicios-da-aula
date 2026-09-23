create database aula21;
use aula21;

create table usuarios (
	id int auto_increment primary key,
    usuario varchar(50) not null unique,
    senha varchar(255) not null
);

select * from usuarios;