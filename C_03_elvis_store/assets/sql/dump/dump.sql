/* Use a Cabeça - Elvis Store */

CREATE DATABASE php_elvis_store;

/* Selecionar Banco para realizar operações */
USE php_elvis_store;

CREATE TABLE email_list (
	primeiro_nome varchar(40),
    sobrenome varchar(40),
    email varchar(40)
);

/* Descreve a estrutura desejada */
DESCRIBE email_list;

/* Deletar uma tabela */
DROP TABLE email_list;