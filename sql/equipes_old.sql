----------------------------------------------------------------------------
DROP TABLE IF EXISTS usuariosEquipes;
DROP TABLE IF EXISTS equipes;
DROP SEQUENCE IF EXISTS seq_equipes CASCADE;

CREATE SEQUENCE seq_equipes
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
-- ALTER TABLE seq_equipes
  -- OWNER TO postgres;
COMMENT ON SEQUENCE seq_equipes
  IS 'Sequência de incremento automático da tabela de equipes.';

----------------------------------------------------------------------------

CREATE TABLE equipes (
    id_equipe smallint DEFAULT nextval('seq_equipes'::regclass) NOT NULL, -- Código
    nome character varying(32) NOT NULL, -- Nome
    lavagem boolean NOT NULL DEFAULT FALSE, -- Check se esta Ativo
    ativo boolean NOT NULL DEFAULT TRUE -- Check se esta Ativo
);

-- ALTER TABLE equipes -- OWNER TO postgres;

-- REVOKE ALL ON TABLE equipes FROM PUBLIC;
-- REVOKE ALL ON TABLE equipes FROM postgres;
-- GRANT ALL ON TABLE equipes TO postgres;

----------------------------------------------------------------------------

DELETE FROM equipes;


INSERT INTO equipes(nome) values ('Desenvolvimento');
INSERT INTO equipes(nome) values ('Time 1');
INSERT INTO equipes(nome) values ('Time 2');
INSERT INTO equipes(nome) values ('Elétrica');
INSERT INTO equipes(nome) values ('Manutenção');

----------------------------------------------------------------------------

CREATE TABLE usuariosEquipes (
    id_equipe smallint NOT NULL, -- Código da equipe
    id_usuario smallint NOT NULL -- Código do usuario
);

-- ALTER TABLE usuariosEquipes -- OWNER TO postgres;

-- REVOKE ALL ON TABLE usuariosEquipes FROM PUBLIC;
-- REVOKE ALL ON TABLE usuariosEquipes FROM postgres;
-- GRANT ALL ON TABLE usuariosEquipes TO postgres;
----------------------------------------------------------------------------

DELETE FROM usuariosEquipes;


INSERT INTO usuariosEquipes(id_equipe, id_usuario) values (1,1);
INSERT INTO usuariosEquipes(id_equipe, id_usuario) values (1,2);

INSERT INTO usuariosEquipes(id_equipe, id_usuario) values (2,6);
INSERT INTO usuariosEquipes(id_equipe, id_usuario) values (2,10);

INSERT INTO usuariosEquipes(id_equipe, id_usuario) values (3,7);
INSERT INTO usuariosEquipes(id_equipe, id_usuario) values (3,11);

INSERT INTO usuariosEquipes(id_equipe, id_usuario) values (4,8);
INSERT INTO usuariosEquipes(id_equipe, id_usuario) values (4,12);

INSERT INTO usuariosEquipes(id_equipe, id_usuario) values (5,9);
INSERT INTO usuariosEquipes(id_equipe, id_usuario) values (5,13);

----------------------------------------------------------------------------