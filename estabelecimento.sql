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

create table adicional(
	id integer not null auto_increment,
	descricao varchar(50) not null,
	primary key (id)
)engine = InnoDB;

create table categoria(
	id integer not null auto_increment,
	descricao varchar(50) not null,
        categoriaPaiId integer,
	primary key (id)
)engine = InnoDB;

alter table categoria add foreign key (categoriaPaiId) references categoria(id);

create table categoriaAdicional(
	id integer not null auto_increment,
        categoriaId integer not null,
        adicionalId integer not null,
	primary key (id),
        foreign key (categoriaId) references categoria(id),
        foreign key (adicionalId) references adicional(id)
)engine = InnoDB;

create table produto(
	id integer not null auto_increment,
	codigo varchar(50) not null,
	nome varchar(50) not null,
	descricao varchar(250) not null,
	preco float(15,2) not null,
        foto varchar(100) not null,
        categoriaId integer,
	primary key (id),
        foreign key (categoriaId) references categoria(id)
)engine = InnoDB;

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

create table pedido(
	id integer not null auto_increment,
	quantidade float(15,2) not null,
	produtoId integer not null,
	contaId integer not null,
        observacao varchar(250),
	primary key (id),
	foreign key (produtoId) references produto(id),
	foreign key (contaId) references conta(id)
)engine = InnoDB;

create table pedidoAdicional(
	id integer not null auto_increment,
        pedidoId integer not null,
        adicionalId integer not null,
	primary key (id),
        foreign key (pedidoId) references pedido(id),
        foreign key (adicionalId) references adicional(id)
)engine = InnoDB;

DELIMITER //
DROP FUNCTION IF EXISTS buscarDescricao//
CREATE FUNCTION buscarDescricao(idPai INT)
    RETURNS VARCHAR(500)
    DETERMINISTIC
    BEGIN
        DECLARE pai INT;
        DECLARE desc1 VARCHAR(50);
        DECLARE descFinal VARCHAR(50);
        SET descFinal = '';
        busca1: LOOP
            IF idPai IS NOT NULL THEN
                SELECT descricao,categoriaPaiId INTO desc1,pai FROM categoria WHERE id = idPai;
                If descFinal = '' THEN
                    SET descFinal = desc1;
                ELSE
                    SET descFinal = CONCAT(desc1,'->',descFinal);
                END IF;
                IF pai IS NOT NULL && pai != idPai THEN
                    SET idPai = pai;
                    ITERATE busca1;
                END IF;
            END IF;
        LEAVE busca1;
        END LOOP busca1;
        RETURN  descFinal;
    END//
DELIMITER ;

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