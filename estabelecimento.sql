start transaction;

drop database if exists dbestabelecimento;
create database dbestabelecimento;

use dbestabelecimento;

create table usuario(
	usuario varchar(20) not null,
        senha varchar(50) not null,
        nome varchar(50) not null,
        tipo int not null,
	primary key (usuario)
)engine = InnoDB;

create table categoriaProduto(
	id integer not null auto_increment,
	descricao varchar(50) not null,
        categoriaProdutoPaiId integer,
	primary key (id)
)engine = InnoDB;

alter table categoriaProduto add foreign key (categoriaProdutoPaiId) references categoriaProduto(id);

create table conta(
	id integer not null auto_increment,
	dataAbertura date,
	horaAbertura time,
	dataFechamento date,
	horaFechamento time,
	numMesa integer,
	qtdPessoas integer,
	primary key (id)
)engine = InnoDB;

create table produto(
	id integer not null auto_increment,
	codigo varchar(50) not null,
	nome varchar(50) not null,
	descricao varchar(250) not null,
	preco float(0) not null,
        foto varchar(100) not null,
        categoriaId integer,
	primary key (id),
        foreign key (categoriaId) references categoriaProduto(id)
)engine = InnoDB;

create table pedido(
	id integer not null auto_increment,
	quantidade float(0) not null,
	produtoId integer not null,
	contaId integer not null,
	primary key (id),
	foreign key (produtoId) references produto(id),
	foreign key (contaId) references conta(id)
)engine = InnoDB;

--S%s@dm1n
insert into usuario (nome,usuario,senha,tipo) values ('Administrador','sysadmin','742379261b4ba6149a2c3bc7ca8d1cb31f176642',1);

--insert into usuario (nome,usuario,senha,tipo) values ('Usuário 2','user2','12345',2);
--insert into usuario (nome,usuario,senha,tipo) values ('Usuário 3','user3','12345',3);
--insert into usuario (nome,usuario,senha,tipo) values ('Usuário 4','user4','12345',1);
--insert into usuario (nome,usuario,senha,tipo) values ('Usuário 5','user5','12345',2);
--insert into usuario (nome,usuario,senha,tipo) values ('Usuário 6','user6','12345',3);
--insert into usuario (nome,usuario,senha,tipo) values ('Usuário 7','user7','12345',1);
--insert into usuario (nome,usuario,senha,tipo) values ('Usuário 8','user8','12345',2);

commit;