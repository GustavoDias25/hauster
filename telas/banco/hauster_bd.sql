create database petshop;
use petshop;

create table servicos (
	id int auto_increment primary key,
    nome varchar(100) not null,
    descricao text,
    preco decimal(10,2) not null,
    duracao_media int,
    categoria varchar(50),
    ativo boolean default true
);