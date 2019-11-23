-- Geração de Modelo físico
-- Sql ANSI 2003 - brModelo.



CREATE TABLE favorito (
usuario INTEGER,
receita VARCHAR(10)
);

CREATE TABLE receitas (
id_receita VARCHAR(10) PRIMARY KEY,
usuario INTEGER,
titulo_receita VARCHAR(80),
ingredientes VARCHAR(10),
desc_receita VARCHAR(12000),
tipo_receita VARCHAR(25),
foto_receita VARCHAR(10)
);

CREATE TABLE usuario (
id_ususario INTEGER PRIMARY KEY,
nome VARCHAR(150),
tipo_usuario VARCHAR(25),
sobrenome VARCHAR(150),
email VARCHAR(200),
senha VARCHAR(100),
cpf VARCHAR(20),
foto_usuario VARCHAR(10)
);

ALTER TABLE favorito ADD FOREIGN KEY(usuario) REFERENCES usuario (id_ususario);
ALTER TABLE favorito ADD FOREIGN KEY(receita) REFERENCES receitas (id_receita);
ALTER TABLE receitas ADD FOREIGN KEY(usuario) REFERENCES usuario (id_ususario);
