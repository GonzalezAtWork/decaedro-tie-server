DROP FUNCTION IF EXISTS unaccent(); CREATE FUNCTION unaccent() RETURNS VARCHAR AS $$ DECLARE v_str varchar; BEGIN select translate($1, 'ÀÁÂÃÄÅ', 'AAAAAA') into v_str; select translate(v_str, 'ÉÈËÊ', 'EEEE') into v_str; select translate(v_str, 'ÌÍÎÏ', 'IIII') into v_str; select translate(v_str, 'ÌÍÎÏ', 'IIII') into v_str; select translate(v_str, 'ÒÓÔÕÖ', 'OOOOO') into v_str; select translate(v_str, 'ÙÚÛÜ', 'UUUU') into v_str; select translate(v_str, 'àáâãäå', 'aaaaaa') into v_str; select translate(v_str, 'èéêë', 'eeee') into v_str; select translate(v_str, 'ìíîï', 'iiii') into v_str; select translate(v_str, 'òóôõö', 'ooooo') into v_str; select translate(v_str, 'ùúûü', 'uuuu') into v_str; select translate(v_str, 'Çç', 'Cc') into v_str; return v_str; END; $$ LANGUAGE plpgsql;
DROP FUNCTION IF EXISTS simple_unaccent(); CREATE FUNCTION simple_unaccent() RETURNS TEXT AS $$ DECLARE resultado TEXT; BEGIN select translate($1,'áàâãäăéèêëęíìïóòôõöúùûüÁÀÂÃÄÉÈÊËÍÌÏÓÒÔÕÖÚÙÛÜçÇ', 'aaaaaaeeeeeiiiooooouuuuAAAAAEEEEIIIOOOOOUUUUcC') into resultado; END; $$ LANGUAGE plpgsql;

DROP SEQUENCE IF EXISTS _mob_seq_usuarios_mobile CASCADE;  CREATE SEQUENCE _mob_seq_usuarios_mobile INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 76  CACHE 1 ; 
DROP SEQUENCE IF EXISTS _mob_seq_usuarios CASCADE;  CREATE SEQUENCE _mob_seq_usuarios INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 6  CACHE 1 ; 
DROP SEQUENCE IF EXISTS _mob_seq_tipos CASCADE;  CREATE SEQUENCE _mob_seq_tipos INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 8  CACHE 1 ; 
DROP SEQUENCE IF EXISTS _mob_seq_status CASCADE;  CREATE SEQUENCE _mob_seq_status INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 10  CACHE 1 ; 
DROP SEQUENCE IF EXISTS _mob_seq_roteiros CASCADE;  CREATE SEQUENCE _mob_seq_roteiros INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 9  CACHE 1 ; 
DROP SEQUENCE IF EXISTS _mob_seq_publicidade_imagens CASCADE;  CREATE SEQUENCE _mob_seq_publicidade_imagens INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 2  CACHE 1 ; 
DROP SEQUENCE IF EXISTS _mob_seq_permissoes CASCADE;  CREATE SEQUENCE _mob_seq_permissoes INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 6  CACHE 1 ; 
DROP SEQUENCE IF EXISTS _mob_seq_perfis CASCADE;  CREATE SEQUENCE _mob_seq_perfis INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 4  CACHE 1 ; 
DROP SEQUENCE IF EXISTS _mob_seq_modelos CASCADE;  CREATE SEQUENCE _mob_seq_modelos INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 40  CACHE 1 ; 
DROP SEQUENCE IF EXISTS _mob_seq_encarregados CASCADE;  CREATE SEQUENCE _mob_seq_encarregados INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 2  CACHE 1 ; 
DROP SEQUENCE IF EXISTS _mob_seq_categorias_itens_vistoria CASCADE;  CREATE SEQUENCE _mob_seq_categorias_itens_vistoria INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 2  CACHE 1 ; 
DROP SEQUENCE IF EXISTS _mob_seq_bairros CASCADE;  CREATE SEQUENCE _mob_seq_bairros INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 157  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_zonas CASCADE;  CREATE SEQUENCE seq_zonas INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 13  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_vistoria_itens CASCADE;  CREATE SEQUENCE seq_vistoria_itens INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 172  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_vistoria CASCADE;  CREATE SEQUENCE seq_vistoria INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 349  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_usuario CASCADE;  CREATE SEQUENCE seq_usuario INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 87  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_servidor CASCADE;  CREATE SEQUENCE seq_servidor INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 6  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_roteiros CASCADE;  CREATE SEQUENCE seq_roteiros INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 98  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_regionais CASCADE;  CREATE SEQUENCE seq_regionais INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 13  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_publicidade_veiculacao CASCADE;  CREATE SEQUENCE seq_publicidade_veiculacao INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 18686  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_publicidade_imagens CASCADE;  CREATE SEQUENCE seq_publicidade_imagens INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 2032  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_pontos_tipo CASCADE;  CREATE SEQUENCE seq_pontos_tipo INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 23  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_pontos_status CASCADE;  CREATE SEQUENCE seq_pontos_status INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 6  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_pontos_padroes CASCADE;  CREATE SEQUENCE seq_pontos_padroes INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 42  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_pontos CASCADE;  CREATE SEQUENCE seq_pontos INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 18474  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_piso_calcada CASCADE;  CREATE SEQUENCE seq_piso_calcada INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 10  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_permissoes CASCADE;  CREATE SEQUENCE seq_permissoes INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 11  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_perfil CASCADE;  CREATE SEQUENCE seq_perfil INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 15  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_oss CASCADE;  CREATE SEQUENCE seq_oss INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 28  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_ocorrencia CASCADE;  CREATE SEQUENCE seq_ocorrencia INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 59742  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_limite_terreno CASCADE;  CREATE SEQUENCE seq_limite_terreno INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 15  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_item_tipo CASCADE;  CREATE SEQUENCE seq_item_tipo INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 24  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_interferencias CASCADE;  CREATE SEQUENCE seq_interferencias INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 14  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_inclinacao CASCADE;  CREATE SEQUENCE seq_inclinacao INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 6  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_gravidades CASCADE;  CREATE SEQUENCE seq_gravidades INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 4  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_gps_logger CASCADE;  CREATE SEQUENCE seq_gps_logger INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 2604156  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_fotos CASCADE;  CREATE SEQUENCE seq_fotos INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 27555  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_equipes CASCADE;  CREATE SEQUENCE seq_equipes INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 3  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_carros CASCADE;  CREATE SEQUENCE seq_carros INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 39  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_bairros CASCADE;  CREATE SEQUENCE seq_bairros INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 568  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_auditoriaacao CASCADE;  CREATE SEQUENCE seq_auditoriaacao INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 14  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_adm_usuario CASCADE;  CREATE SEQUENCE seq_adm_usuario INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 2  CACHE 1 ; 
DROP SEQUENCE IF EXISTS seq_adm_perfil CASCADE;  CREATE SEQUENCE seq_adm_perfil INCREMENT 1  MINVALUE 1  MAXVALUE 9223372036854775807  START 5  CACHE 1 ; 

DROP TABLE IF EXISTS adm_perfis CASCADE ; CREATE TABLE IF NOT EXISTS adm_perfis(id_perfil smallint    DEFAULT nextval('seq_adm_perfil'::regclass), nome character varying (32)   , ativo boolean    DEFAULT true);
DROP TABLE IF EXISTS adm_usuarios CASCADE ; CREATE TABLE IF NOT EXISTS adm_usuarios(id_usuario smallint    DEFAULT nextval('seq_adm_usuario'::regclass), id_perfil smallint   , cpf character varying (11)   , senha character varying (32)   , nome character varying (64)   , nome_completo character varying (64)   , data_nascimento date     , id_cargo integer     , data_admissao date     , rg character varying (16)     , habilitacao_numero character varying (16)     , habilitacao_categoria character varying (2)     , habilitacao_validade date     , email character varying (64)     , ddd_celular character (2)     , telefone_celular character varying (10)     , ddd_fixo character (2)     , telefone_fixo character varying (10)     , ddd_contato character (2)     , telefone_contato character varying (10)     , nome_contato character varying (64)     , endereco_rua character varying (128)     , endereco_numero character varying (6)     , endereco_complemento character varying (32)     , endereco_bairro character varying (64)     , endereco_id_cidade integer     , endereco_cep character varying (8)     , id_tipo_sanguineo smallint     , alergias text     , tamanho_camisa character varying (3)     , tamanho_calca character varying (3)     , tamanho_sapato character varying (3)     , capacitacao_eletrica boolean      DEFAULT false, capacitacao_plasma boolean      DEFAULT false, periodo_experiencia boolean      DEFAULT false, contratado_agencia boolean      DEFAULT false, logado_mobile boolean    DEFAULT false, ativo boolean    DEFAULT true, id_servidor smallint    DEFAULT 1, equipe_supervisor smallint     , id_departamento smallint     , foto text     );
DROP TABLE IF EXISTS auditoria CASCADE ; CREATE TABLE IF NOT EXISTS auditoria(data timestamp without time zone   NOT NULL   DEFAULT now(), ip_address text   , id_usuario integer   NOT NULL  , acao integer   NOT NULL  , obs text   NOT NULL  );
DROP TABLE IF EXISTS auditoriaacoes CASCADE ; CREATE TABLE IF NOT EXISTS auditoriaacoes(acao integer    DEFAULT nextval('seq_auditoriaacao'::regclass), nome text   NOT NULL  );
DROP TABLE IF EXISTS backup_pontos CASCADE ; CREATE TABLE IF NOT EXISTS backup_pontos(data_backup character varying (16)  , id_ponto integer   NOT NULL  , endereco character varying (250)   , cep character varying (10)    DEFAULT '0'::character varying, ativo boolean    DEFAULT true, gmaps_latitude character varying (50)  , gmaps_longitude character varying (50)  , gmaps_endereco text   NOT NULL   DEFAULT ''::text, codigo_abrigo character varying (50)  , codigo_novo character varying (50) , id_regional smallint  , id_bairro smallint , posicao_global smallint  , id_roteiro smallint  , id_padrao smallint , conjugados character varying (250)   , dt_implantacao date   NOT NULL   DEFAULT now(), dt_painel_calcada date   NOT NULL   DEFAULT now(), painel_calcada boolean    DEFAULT false, observacoes text  , id_inclinacao smallint   , id_limite_terreno smallint   , limite_terreno_obs text  , id_piso_calcada smallint , piso_calcada_obs text  , croquis text  , noturno boolean    DEFAULT false, poste boolean    DEFAULT false, poste_quantos character varying (50) , eletrica boolean    DEFAULT false, secundario boolean    DEFAULT false, iluminacao_publica boolean    DEFAULT false, largura_calcada character varying (50)  , distancia_calcada character varying (50)   );
DROP TABLE IF EXISTS bairros CASCADE ; CREATE TABLE IF NOT EXISTS bairros(id_bairro integer    DEFAULT nextval('seq_bairros'::regclass), id_zona integer    , nome character varying (250)   , distancia integer   NOT NULL   DEFAULT 0, vistoria character varying (1)    DEFAULT 'D'::character varying, ativo boolean    DEFAULT true);
DROP TABLE IF EXISTS carros CASCADE ; CREATE TABLE IF NOT EXISTS carros(id_carro integer   NOT NULL   DEFAULT nextval('seq_carros'::regclass), data timestamp without time zone   NOT NULL   DEFAULT now(), placa character varying (150)  , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS equipes CASCADE ; CREATE TABLE IF NOT EXISTS equipes(id_equipe integer   NOT NULL   DEFAULT nextval('seq_equipes'::regclass), data_equipe date   NOT NULL  , id_supervisor integer   NOT NULL  );
DROP TABLE IF EXISTS equipe_membros CASCADE ; CREATE TABLE IF NOT EXISTS equipe_membros(id_equipe integer   NOT NULL  , id_superior integer   NOT NULL  , id_membro integer   NOT NULL  , id_perfil integer   NOT NULL  );
DROP TABLE IF EXISTS fotografias CASCADE ; CREATE TABLE IF NOT EXISTS fotografias(stamp text   , id_foto integer    DEFAULT nextval('seq_fotos'::regclass), nome character varying (150)  , id_ponto integer   NOT NULL  , id_ocorrencia integer   NOT NULL  , id_os integer   NOT NULL  , id_vistoria integer   NOT NULL  , id_item integer   NOT NULL  , data timestamp without time zone   NOT NULL   DEFAULT now(), base64 text   , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS gps_logger CASCADE ; CREATE TABLE IF NOT EXISTS gps_logger(data timestamp without time zone   NOT NULL   DEFAULT now(), device character varying (150)  , id_usuario integer   NOT NULL  , latitude character varying (150)  , longitude character varying (150)  , altitude character varying (150)  , accuracy character varying (150)  , velocidade character varying (150)  , bearing character varying (150)  , id_logger integer   NOT NULL   DEFAULT nextval('seq_gps_logger'::regclass));
DROP TABLE IF EXISTS gravidades CASCADE ; CREATE TABLE IF NOT EXISTS gravidades(id_gravidade integer   NOT NULL   DEFAULT nextval('seq_gravidades'::regclass), nome character varying (32)  , gmaps_latitude character varying (50)  , gmaps_longitude character varying (50)  , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS history_store CASCADE ; CREATE TABLE IF NOT EXISTS history_store(timemark timestamp without time zone   , table_name character varying (50)   , pk_date_src character varying (400)   , pk_date_dest character varying (400)   , record_state smallint   );
DROP TABLE IF EXISTS inclinacoes CASCADE ; CREATE TABLE IF NOT EXISTS inclinacoes(id_inclinacao integer   NOT NULL   DEFAULT nextval('seq_inclinacao'::regclass), nome character varying (32)  , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS interferencias CASCADE ; CREATE TABLE IF NOT EXISTS interferencias(id_interferencia integer   NOT NULL   DEFAULT nextval('seq_interferencias'::regclass), nome character varying (32)  , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS itenstipo CASCADE ; CREATE TABLE IF NOT EXISTS itenstipo(id_tipoitem integer   NOT NULL   DEFAULT nextval('seq_item_tipo'::regclass), nome character varying (150)  , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS limiteterreno CASCADE ; CREATE TABLE IF NOT EXISTS limiteterreno(id_limite_terreno integer   NOT NULL   DEFAULT nextval('seq_limite_terreno'::regclass), nome character varying (32)  , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS mobile_login CASCADE ; CREATE TABLE IF NOT EXISTS mobile_login(data timestamp without time zone   NOT NULL   DEFAULT now(), ip_address character varying (150)  , device character varying (150)  , id_usuario integer   NOT NULL  , token character varying (150)  );
DROP TABLE IF EXISTS ocorrencias CASCADE ; CREATE TABLE IF NOT EXISTS ocorrencias(id_ocorrencia integer   NOT NULL   DEFAULT nextval('seq_ocorrencia'::regclass), data timestamp without time zone   NOT NULL   DEFAULT now(), posicao integer , id_ponto integer  , id_os integer  , id_vistoria integer , id_equipe integer  , observacao text   , itensvistoria text   , observacaovistoria text   , fotovistoria text   , itensmanutencao text   , observacaomanutencao text   , fotomanutencao text   , vistoriada boolean   NOT NULL   DEFAULT false, executada boolean   NOT NULL   DEFAULT false, gerar_os boolean   NOT NULL   DEFAULT false, nomeimagenspublicidade text   , dt_lastupdate timestamp without time zone   , semanapublicidade character varying (4)  , id_usuario integer   NOT NULL   DEFAULT 0, processada boolean   NOT NULL   DEFAULT false);
DROP TABLE IF EXISTS oss CASCADE ; CREATE TABLE IF NOT EXISTS oss(id_os integer   NOT NULL   DEFAULT nextval('seq_oss'::regclass), dt_emissao timestamp without time zone   NOT NULL   DEFAULT now(), data timestamp without time zone   NOT NULL   DEFAULT now(), id_gravidade integer   NOT NULL  , agendada boolean   NOT NULL   DEFAULT false, executada boolean   NOT NULL   DEFAULT false, id_prioridade integer   NOT NULL  , chuva boolean   NOT NULL   DEFAULT false, andamento boolean   NOT NULL   DEFAULT false, id_carro integer , km_saida character varying (100) , km_chegada character varying (100)  , km_rodados character varying (100), hs_saida character varying (20)   , hs_chegada character varying (20)  , hs_rodados character varying (20)   , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS ossequipes CASCADE ; CREATE TABLE IF NOT EXISTS ossequipes(id_os integer   NOT NULL  , id_equipe integer   NOT NULL  );
DROP TABLE IF EXISTS ossroteiros CASCADE ; CREATE TABLE IF NOT EXISTS ossroteiros(id_os integer   NOT NULL  , id_roteiro integer   NOT NULL  , qtd_pontos integer   NOT NULL  );
DROP TABLE IF EXISTS perfil_permissoes CASCADE ; CREATE TABLE IF NOT EXISTS perfil_permissoes(id_perfil integer   NOT NULL  , id_permissao integer   NOT NULL  );
DROP TABLE IF EXISTS perfis CASCADE ; CREATE TABLE IF NOT EXISTS perfis(id_perfil integer   NOT NULL   DEFAULT nextval('seq_perfil'::regclass), nome character varying (32)  , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS permissoes CASCADE ; CREATE TABLE IF NOT EXISTS permissoes(id_permissao integer   NOT NULL   DEFAULT nextval('seq_permissoes'::regclass), descricao character varying (32)  , token character varying (32)  );
DROP TABLE IF EXISTS pisocalcada CASCADE ; CREATE TABLE IF NOT EXISTS pisocalcada(id_piso_calcada integer   NOT NULL   DEFAULT nextval('seq_piso_calcada'::regclass), nome character varying (32)  , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS pontos CASCADE ; CREATE TABLE IF NOT EXISTS pontos(id_ponto integer   NOT NULL   DEFAULT nextval('seq_pontos'::regclass), endereco character varying (250)  , cep character varying (10) , ativo boolean   NOT NULL   DEFAULT true, gmaps_latitude character varying (50)  , gmaps_longitude character varying (50)  , gmaps_endereco text , codigo_abrigo character varying (50)  , codigo_novo character varying (50)  , id_regional integer  , id_bairro integer  , posicao_global integer , id_roteiro integer , id_padrao integer  , conjugados character varying (250)  , dt_implantacao date   DEFAULT now(), dt_painel_calcada date  DEFAULT now(), painel_calcada boolean   DEFAULT false, observacoes text , id_inclinacao integer   , id_limite_terreno integer  , limite_terreno_obs text , id_piso_calcada integer   , piso_calcada_obs text  , croquis text  , noturno boolean   DEFAULT false, poste boolean  DEFAULT false, poste_quantos character varying (50)  , eletrica boolean   DEFAULT false, secundario boolean   DEFAULT false, iluminacao_publica boolean   DEFAULT false, largura_calcada character varying (50)   , distancia_calcada character varying (50)   , bairro_nome character varying (150)   , nome_imagem_a character varying (50)  , nome_imagem_b character varying (50)   , id_tipo smallint   , sequencia numeric   , status character varying (50) );
DROP TABLE IF EXISTS pontosgps CASCADE ; CREATE TABLE IF NOT EXISTS pontosgps(data timestamp without time zone   NOT NULL   DEFAULT now(), id_ponto integer   NOT NULL  , simak character varying (150)  , gmaps_latitude character varying (50)  , gmaps_longitude character varying (50)  , old_latitude character varying (50)  , old_longitude character varying (50)  , altitude character varying (100)  , accuracy character varying (100)  , velocidade character varying (100)  , bearing character varying (100)  , device character varying (150)  , token character varying (150)  , hora character varying (150)  );
DROP TABLE IF EXISTS pontosinterferencias CASCADE ; CREATE TABLE IF NOT EXISTS pontosinterferencias(id_ponto integer   NOT NULL  , id_interferencia integer   NOT NULL  , tipo character varying (1)  , metragem character varying (32)  );
DROP TABLE IF EXISTS pontospadrao CASCADE ; CREATE TABLE IF NOT EXISTS pontospadrao(id_padrao integer   NOT NULL   DEFAULT nextval('seq_pontos_padroes'::regclass), id_tipo integer  , nome character varying (50)  , telhado character varying (150) , qtd_modulos character varying (50)  DEFAULT '1'::character varying, croquis text   , foto text   , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS pontosstatus CASCADE ; CREATE TABLE IF NOT EXISTS pontosstatus(id_status integer   NOT NULL   DEFAULT nextval('seq_pontos_status'::regclass), nome character varying (32)  , observacoes text   , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS pontosstatushistorico CASCADE ; CREATE TABLE IF NOT EXISTS pontosstatushistorico(id_ponto integer   NOT NULL  , id_status integer   , id_os integer  , id_usuario integer   , data timestamp without time zone   NOT NULL   DEFAULT now());
DROP TABLE IF EXISTS pontostipo CASCADE ; CREATE TABLE IF NOT EXISTS pontostipo(id_tipo integer   NOT NULL   DEFAULT nextval('seq_pontos_tipo'::regclass), nome character varying (32)  , cor character varying (6)   NOT NULL   DEFAULT '000000'::character varying, totem boolean   NOT NULL   DEFAULT false, ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS publicidadeimagens CASCADE ; CREATE TABLE IF NOT EXISTS publicidadeimagens(id_imagem integer   NOT NULL   DEFAULT nextval('seq_publicidade_imagens'::regclass), periodo_inicio timestamp without time zone   NOT NULL   DEFAULT now(), periodo_fim timestamp without time zone   NOT NULL   DEFAULT now(), nome character varying (50)  , imagem text   , observacao text   , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS publicidadeveiculacao CASCADE ; CREATE TABLE IF NOT EXISTS publicidadeveiculacao(id_veiculacao integer   NOT NULL   DEFAULT nextval('seq_publicidade_veiculacao'::regclass), simak character varying (50)  , otima character varying (50)  , caixa character (1)   NOT NULL  , face character (1)   NOT NULL  , semana integer   NOT NULL  , ano integer   NOT NULL   DEFAULT date_part('year'::text, ('now'::text)::date), nome_imagem character varying (50)  , id_ocorrencia integer  , troca_realizada boolean   NOT NULL   DEFAULT false, ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS regionais CASCADE ; CREATE TABLE IF NOT EXISTS regionais(id_regional integer   NOT NULL   DEFAULT nextval('seq_regionais'::regclass), nome character varying (32)  , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS roteiros CASCADE ; CREATE TABLE IF NOT EXISTS roteiros(id_roteiro integer   NOT NULL   DEFAULT nextval('seq_roteiros'::regclass), nome character varying (32)  , id_gravidade integer   NOT NULL  , noturno boolean   NOT NULL   DEFAULT false, vistoria boolean   NOT NULL   DEFAULT false, manutencao boolean   NOT NULL   DEFAULT false, publicidade boolean   NOT NULL   DEFAULT false, lavagem boolean   NOT NULL   DEFAULT false, frequencia character varying (1)   NOT NULL   DEFAULT 'D'::character varying, cor character varying (32)   NOT NULL   DEFAULT '000000'::character varying, ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS roteirospontos CASCADE ; CREATE TABLE IF NOT EXISTS roteirospontos(posicao integer   NOT NULL  , id_roteiro integer   NOT NULL  , id_ponto integer   NOT NULL  );
DROP TABLE IF EXISTS servidores CASCADE ; CREATE TABLE IF NOT EXISTS servidores(id_servidor integer   NOT NULL   DEFAULT nextval('seq_servidor'::regclass), url character varying (250)  , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS usuarios CASCADE ; CREATE TABLE IF NOT EXISTS usuarios(id_usuario integer   NOT NULL   DEFAULT nextval('seq_usuario'::regclass), id_perfil integer   NOT NULL  , cpf character varying (11)  , nome character varying (64)  , senha character varying (32)  , email character varying (64)  , ddd character (2)   NOT NULL  , celular character varying (10)  , logado_mobile boolean   NOT NULL   DEFAULT false, ativo boolean   NOT NULL   DEFAULT true, id_servidor integer   NOT NULL   DEFAULT 1, nome_completo character varying (64)  );
DROP TABLE IF EXISTS usuariosequipes CASCADE ; CREATE TABLE IF NOT EXISTS usuariosequipes(id_equipe integer   NOT NULL  , id_usuario integer   NOT NULL  );
DROP TABLE IF EXISTS vistorias CASCADE ; CREATE TABLE IF NOT EXISTS vistorias(id_vistoria integer   NOT NULL   DEFAULT nextval('seq_vistoria'::regclass), dt_emissao timestamp without time zone   NOT NULL   DEFAULT now(), id_roteiro integer   , id_gravidade integer  , agendada boolean   NOT NULL   DEFAULT false, executada boolean   NOT NULL   DEFAULT false, data timestamp without time zone   NOT NULL   DEFAULT now(), dia_semana character varying (1)  , periodo character varying (1)  , andamento boolean   NOT NULL   DEFAULT false, id_carro integer , km_saida character varying (100)  , km_chegada character varying (100)  , km_rodados character varying (100)  , hs_saida character varying (20)  , hs_chegada character varying (20)  , hs_rodados character varying (20)  , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS vistoriasequipes CASCADE ; CREATE TABLE IF NOT EXISTS vistoriasequipes(id_vistoria integer   NOT NULL  , id_equipe integer   NOT NULL  );
DROP TABLE IF EXISTS vistoriasitens CASCADE ; CREATE TABLE IF NOT EXISTS vistoriasitens(id_item integer   NOT NULL   DEFAULT nextval('seq_vistoria_itens'::regclass), id_tipoitem integer   NOT NULL   DEFAULT 0, sigla character varying (50)  , nome character varying (150)  , codigo character varying (32)  , critico boolean   NOT NULL   DEFAULT false, chuva boolean   NOT NULL   DEFAULT false, urgente boolean   NOT NULL   DEFAULT false, foto boolean   NOT NULL   DEFAULT false, cotia boolean   NOT NULL   DEFAULT false, eletrica boolean   NOT NULL   DEFAULT false, cobertura_maior boolean   NOT NULL   DEFAULT false, ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS vistoriasroteiros CASCADE ; CREATE TABLE IF NOT EXISTS vistoriasroteiros(id_vistoria integer   NOT NULL  , id_roteiro integer   NOT NULL  , qtd_pontos integer   NOT NULL  );
DROP TABLE IF EXISTS vistoriastipos CASCADE ; CREATE TABLE IF NOT EXISTS vistoriastipos(id_vistoria integer   NOT NULL  , id_tipo integer   NOT NULL  );
DROP TABLE IF EXISTS zonas CASCADE ; CREATE TABLE IF NOT EXISTS zonas(id_zona integer   NOT NULL   DEFAULT nextval('seq_zonas'::regclass), nome character varying (32)  , ativo boolean   NOT NULL   DEFAULT true);
DROP TABLE IF EXISTS _mob_abrigos CASCADE ; CREATE TABLE IF NOT EXISTS _mob_abrigos(simak integer   , endereco character varying (128)   , imagem_face_interna character varying (64)  , imagem_face_externa character varying (64)  , numero_otima text   , id_tipo smallint   , id_roteiro smallint  , id_bairro smallint , id_status smallint  , id_modelo smallint   , latitude character varying (32)  , longitude character varying (32)  , sequencia numeric   );
DROP TABLE IF EXISTS _mob_app_info CASCADE ; CREATE TABLE IF NOT EXISTS _mob_app_info(last_update character (19)    DEFAULT "substring"((now())::text, 1, 19), total_records integer   );
DROP TABLE IF EXISTS _mob_bairros CASCADE ; CREATE TABLE IF NOT EXISTS _mob_bairros(id_bairro smallint    DEFAULT nextval('_mob_seq_bairros'::regclass), id_zona smallint    DEFAULT 1, nome character varying (64)   );
DROP TABLE IF EXISTS _mob_cargos CASCADE ; CREATE TABLE IF NOT EXISTS _mob_cargos(id_cargo smallint   , nome character varying (32)  );
DROP TABLE IF EXISTS _mob_categorias_itens CASCADE ; CREATE TABLE IF NOT EXISTS _mob_categorias_itens(id_categoria integer   , nome character varying (128)   );
DROP TABLE IF EXISTS _mob_categorias_itens_vistoria CASCADE ; CREATE TABLE IF NOT EXISTS _mob_categorias_itens_vistoria(id_categoria integer    DEFAULT nextval('_mob_seq_categorias_itens_vistoria'::regclass), nome character varying (128)   );
DROP TABLE IF EXISTS _mob_encarregados CASCADE ; CREATE TABLE IF NOT EXISTS _mob_encarregados(id_encarregado integer    DEFAULT nextval('_mob_seq_encarregados'::regclass), nome character varying (64)   );
DROP TABLE IF EXISTS _mob_imagens CASCADE ; CREATE TABLE IF NOT EXISTS _mob_imagens(nome character varying (64)   , imagem text   , thumb text   );
DROP TABLE IF EXISTS _mob_itens_vistoria CASCADE ; CREATE TABLE IF NOT EXISTS _mob_itens_vistoria(id_item integer   , nome character varying (128)   , id_categoria integer   );
DROP TABLE IF EXISTS _mob_modelos CASCADE ; CREATE TABLE IF NOT EXISTS _mob_modelos(id_modelo smallint    DEFAULT nextval('_mob_seq_modelos'::regclass), nome character varying (32)   );
DROP TABLE IF EXISTS _mob_perfil_permissoes CASCADE ; CREATE TABLE IF NOT EXISTS _mob_perfil_permissoes(id_perfil smallint   , id_permissao smallint   );
DROP TABLE IF EXISTS _mob_perfis CASCADE ; CREATE TABLE IF NOT EXISTS _mob_perfis(id_perfil smallint    DEFAULT nextval('_mob_seq_perfis'::regclass), nome character varying (32)   );
DROP TABLE IF EXISTS _mob_permissoes CASCADE ; CREATE TABLE IF NOT EXISTS _mob_permissoes(id_permissao smallint    DEFAULT nextval('_mob_seq_permissoes'::regclass), nome character varying (32)   , token character varying (32)   );
DROP TABLE IF EXISTS _mob_roteiros CASCADE ; CREATE TABLE IF NOT EXISTS _mob_roteiros(id_roteiro smallint    DEFAULT nextval('_mob_seq_roteiros'::regclass), nome character varying (32)   );
DROP TABLE IF EXISTS _mob_status CASCADE ; CREATE TABLE IF NOT EXISTS _mob_status(id_status smallint    DEFAULT nextval('_mob_seq_status'::regclass), nome character varying (32)   );
DROP TABLE IF EXISTS _mob_tipos CASCADE ; CREATE TABLE IF NOT EXISTS _mob_tipos(id_tipo smallint    DEFAULT nextval('_mob_seq_tipos'::regclass), nome character varying (32)   );
DROP TABLE IF EXISTS _mob_usuarios CASCADE ; CREATE TABLE IF NOT EXISTS _mob_usuarios(id_usuario smallint    DEFAULT nextval('_mob_seq_usuarios'::regclass), id_perfil smallint   , cpf character varying (11)   , nome character varying (64)   , senha character varying (32)   , email character varying (64)  );
DROP TABLE IF EXISTS _mob_usuarios_mobile CASCADE ; CREATE TABLE IF NOT EXISTS _mob_usuarios_mobile(id_usuario_mobile integer    DEFAULT nextval('_mob_seq_usuarios_mobile'::regclass), id_cargo smallint   , nome character varying (32)  , nome_completo character varying (128)   );
DROP TABLE IF EXISTS _mob_zonas CASCADE ; CREATE TABLE IF NOT EXISTS _mob_zonas(id_zona smallint   , nome character varying (16)   );
