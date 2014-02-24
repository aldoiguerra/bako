<?php

function retornarTabelas(){
    
    $pos = 0;

    $tabelas[$pos++] = "create table usuario(
                        usuario varchar(20) not null,
                        senha varchar(50) not null,
                        nome varchar(50) not null,
                        tipo int not null,
                        status tinyint not null,
                        primary key (usuario)
                )engine = InnoDB;";

    $tabelas[$pos++] = "create table adicional(
                        id integer not null auto_increment,
                        descricao varchar(50) not null,
                        status tinyint not null,
                        primary key (id)
                )engine = InnoDB;";

    $tabelas[$pos++] = "create table perfilImpressao(
                    id integer not null auto_increment,
                    descricao varchar(50),
                    layout mediumtext,
                    tipo tinyint not null, /*1-texto,2-html*/
                    primary key (id)
            )engine = InnoDB;";
    
    $tabelas[$pos++] = "create table categoria(
                        id integer not null auto_increment,
                        descricao varchar(50) not null,
                        status tinyint not null,
                        categoriaPaiId integer,
                        perfilImpressaoId integer,
                        primary key (id),
                        foreign key (perfilImpressaoId) references perfilImpressao(id)
                )engine = InnoDB;";

    $tabelas[$pos++] = "alter table categoria add foreign key (categoriaPaiId) references categoria(id);";

    $tabelas[$pos++] = "create table categoriaAdicional(
                        id integer not null auto_increment,
                        categoriaId integer not null,
                        adicionalId integer not null,
                        primary key (id),
                        foreign key (categoriaId) references categoria(id),
                        foreign key (adicionalId) references adicional(id)
                )engine = InnoDB;";

    $tabelas[$pos++] = "create table produto(
                        id integer not null auto_increment,
                        codigo varchar(50) not null,
                        nome varchar(50) not null,
                        descricao varchar(250) not null,
                        preco float(15,2) not null,
                        foto varchar(200),
                        status tinyint not null,
                        categoriaId integer,
                        perfilImpressaoId integer,
                        primary key (id),
                        foreign key (categoriaId) references categoria(id),
                        foreign key (perfilImpressaoId) references perfilImpressao(id)
                )engine = InnoDB;";

    $tabelas[$pos++] = "create table conta(
                        id integer not null auto_increment,
                        dataHoraAbertura dateTime,
                        dataHoraFechamento dateTime,
                        numMesa integer,
                        qtdPessoas integer,
                        descricao varchar(50),
                        desconto float(15,2),
                        taxaServico tinyint,
                        status tinyint not null, /*1-aberta,2-fechada,3-livre*/
                        primary key (id)
                )engine = InnoDB;";

    $tabelas[$pos++] = "create table pedido(
                        id integer not null auto_increment,
                        dataHora dateTime,
                        quantidade integer not null,
                        valorUnitario float(15,2) not null,
                        produtoId integer not null,
                        contaId integer not null,
                        usuarioId varchar(20) not null,
                        observacao varchar(250),
                        primary key (id),
                        foreign key (produtoId) references produto(id),
                        foreign key (contaId) references conta(id),
                        foreign key (usuarioId) references usuario(usuario)
                )engine = InnoDB;";

    $tabelas[$pos++] = "create table pedidoAdicional(
                        id integer not null auto_increment,
                        pedidoId integer not null,
                        adicionalId integer not null,
                        quantidade integer not null,
                        primary key (id),
                        foreign key (pedidoId) references pedido(id),
                        foreign key (adicionalId) references adicional(id)
                )engine = InnoDB;";

    $tabelas[$pos++] = "create table formaPagamento(
                        id integer not null auto_increment,
                        descricao varchar(50) not null,
                        pedeObservacao tinyint,
                        primary key (id)
                )engine = InnoDB;";

    $tabelas[$pos++] = "create table parametroSistema(
                        id integer not null auto_increment,
                        qtdMesas varchar(50),
                        valorTxServico float(5,2),
                        primary key (id)
                )engine = InnoDB;";

    $tabelas[$pos++] = "create table pagamento(
                        id integer not null auto_increment,
                        contaId integer not null,
                        formaPagamentoId integer not null,
                        observacao varchar(250),
                        valor float(15,2),
                        usuarioId varchar(20) not null,
                        dataHora dateTime,
                        foreign key (contaId) references conta(id),
                        foreign key (formaPagamentoId) references formaPagamento(id),
                        foreign key (usuarioId) references usuario(usuario),
                        primary key (id)
                )engine = InnoDB;";

    $tabelas[$pos++] = "create table mesa(
                        numMesa integer not null,
                        primary key (numMesa)
                )engine = InnoDB;";

    $tabelas[$pos++] = "DELIMITER //
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
                DELIMITER ; ";

    $tabelas[$pos++] = "insert into usuario (nome,usuario,senha,tipo,status) values ('Administrador','sysadmin','742379261b4ba6149a2c3bc7ca8d1cb31f176642',1,1);";
    $tabelas[$pos++] = "INSERT INTO parametroSistema (qtdMesas,valorTxServico) VALUES ('','0');";

    return $tabelas;
}

?>