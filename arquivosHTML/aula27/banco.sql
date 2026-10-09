create database if not exists ruview
CHARACTER SET utf8mb4
collate utf8mb4_unicode_ci;

use ruview;

create table if not exists usuario (
	id int primary key auto_increment not null,
    nome varchar(50) not null,
    email varchar(100) not null unique,
    senha varchar(255) not null
);

create table if not exists filmes (
    id int primary key auto_increment not null,
    titulo varchar(150) not null,
    duracao int not null,
    sinopse text not null,
    tmdb_id int unique null,
    poster varchar(255) null
);

create table if not exists avaliacoes (
	id int primary key auto_increment not null,
    usuario_id int not null,
    filme_id int not null,
    nota decimal(2,1) not null,
    comentario varchar(500),
    criado_em datetime default current_timestamp,
    foreign key (usuario_id) references usuario(id) on delete cascade,
    foreign key (filme_id) references filmes(id) on delete cascade,
    unique (usuario_id, filme_id), 
    check (nota between 0 and 5)
    );