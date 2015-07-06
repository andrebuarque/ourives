<?

var_dump(apc_fetch('aaa'));
exit();

$conexao = pg_connect("host=localhost dbname=sistemao_producao port=5432 user=sistemao password=IHrg^X.oPU[A");

$sqlMenu = '
CREATE TABLE menu_type
(
  id serial NOT NULL,
  titulo character varying(50),
  link character varying(100),
  ativo boolean,
  idmenu integer,
  CONSTRAINT "MENU_TYPE_pkey" PRIMARY KEY (id),
  CONSTRAINT "MENU_TYPE_idmenu_fkey" FOREIGN KEY (idmenu)
      REFERENCES menu_type (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE menu_type
  OWNER TO sistemao;';

pg_query($sqlMenu) or die (pg_errormessage());


$sqlPerfil = '
CREATE TABLE perfil
(
  id serial NOT NULL,
  titulo character varying(100) NOT NULL,
  ativo boolean NOT NULL,
  CONSTRAINT "PERFIL_pkey" PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE perfil
  OWNER TO sistemao;';

pg_query($sqlPerfil) or die (pg_errormessage());

$sqlUsuario = '
CREATE TABLE usuario
(
  id serial NOT NULL,
  idperfil integer NOT NULL,
  nome character varying(100) NOT NULL,
  email character varying(100) NOT NULL,
  senha character varying(100) NOT NULL,
  ativo boolean NOT NULL,
  CONSTRAINT "USUARIO_pkey" PRIMARY KEY (id),
  CONSTRAINT "USUARIO_idperfil_fkey" FOREIGN KEY (idperfil)
      REFERENCES perfil (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE usuario
  OWNER TO sistemao;';

pg_query($sqlUsuario) or die (pg_errormessage());

$sqlPermissao = '
CREATE TABLE permissao
(
  idperfil integer NOT NULL,
  idmenu integer NOT NULL,
  visualizar boolean,
  gravar boolean,
  remover boolean,
  CONSTRAINT permissao_pk PRIMARY KEY (idmenu, idperfil),
  CONSTRAINT "PERMISSAO_idmenu_fkey" FOREIGN KEY (idmenu)
      REFERENCES menu_type (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "PERMISSAO_idperfil_fkey" FOREIGN KEY (idperfil)
      REFERENCES perfil (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE permissao
  OWNER TO sistemao;';

pg_query($sqlPermissao) or die (pg_errormessage());


$sqlInserirMenu = "
INSERT INTO menu_type (id, titulo, link, ativo, idmenu)
VALUES (1, 'Administração', 'javascript:void(0);', 'TRUE', NULL),
	   (2, 'Usuários', 'cadastroUsuario', 'TRUE', 1),
	   (3, 'Perfil', 'cadastroPerfil', 'TRUE', 1),
	   (4, 'Clientes', 'cadastroCliente', 'TRUE', 1),
	   (5, 'Produtos', 'cadastroProduto', 'TRUE', 1),
	   (6, 'Categorias de O.S', 'cadastroCategoriaOS', 'TRUE', 1),
	   (7, 'Ordens de Serviços', 'cadastroOrdemServiço', 'TRUE', NULL)
";

pg_query($sqlInserirMenu) or die (pg_errormessage());

?>