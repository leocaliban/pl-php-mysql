CREATE DATABASE php_guitar_wars;

CREATE TABLE guitarwars (
	id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
	data DATETIME,
	nome VARCHAR(20),
	pontuacao VARCHAR(20),
	imagem VARCHAR(999),
    aprovado TINYINT
);