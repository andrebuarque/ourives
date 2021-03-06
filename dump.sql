PGDMP                 	        r            ourives    9.3.2    9.3.2 #    �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                       false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                       false            �           1262    16418    ourives    DATABASE     e   CREATE DATABASE ourives WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'C' LC_CTYPE = 'C';
    DROP DATABASE ourives;
             postgres    false                        2615    2200    public    SCHEMA        CREATE SCHEMA public;
    DROP SCHEMA public;
             postgres    false            �           0    0    SCHEMA public    COMMENT     6   COMMENT ON SCHEMA public IS 'standard public schema';
                  postgres    false    5            �           0    0    public    ACL     �   REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;
                  postgres    false    5            �            3079    12018    plpgsql 	   EXTENSION     ?   CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;
    DROP EXTENSION plpgsql;
                  false            �           0    0    EXTENSION plpgsql    COMMENT     @   COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';
                       false    177            �            1259    16444 	   MENU_TYPE    TABLE     �   CREATE TABLE "MENU_TYPE" (
    id integer NOT NULL,
    titulo character varying(50),
    link character varying(100),
    ativo boolean,
    idmenu integer
);
    DROP TABLE public."MENU_TYPE";
       public         postgres    false    5            �            1259    16442    MENU_TYPE_id_seq    SEQUENCE     t   CREATE SEQUENCE "MENU_TYPE_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public."MENU_TYPE_id_seq";
       public       postgres    false    5    175            �           0    0    MENU_TYPE_id_seq    SEQUENCE OWNED BY     ;   ALTER SEQUENCE "MENU_TYPE_id_seq" OWNED BY "MENU_TYPE".id;
            public       postgres    false    174            �            1259    16427    PERFIL    TABLE     {   CREATE TABLE "PERFIL" (
    id integer NOT NULL,
    titulo character varying(100) NOT NULL,
    ativo boolean NOT NULL
);
    DROP TABLE public."PERFIL";
       public         postgres    false    5            �            1259    16425    PERFIL_id_seq    SEQUENCE     q   CREATE SEQUENCE "PERFIL_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public."PERFIL_id_seq";
       public       postgres    false    5    173            �           0    0    PERFIL_id_seq    SEQUENCE OWNED BY     5   ALTER SEQUENCE "PERFIL_id_seq" OWNED BY "PERFIL".id;
            public       postgres    false    172            �            1259    16455 	   PERMISSAO    TABLE     �   CREATE TABLE "PERMISSAO" (
    idperfil integer,
    idmenu integer,
    visualizar boolean,
    gravar boolean,
    remover boolean
);
    DROP TABLE public."PERMISSAO";
       public         postgres    false    5            �            1259    16421    USUARIO    TABLE     �   CREATE TABLE "USUARIO" (
    id integer NOT NULL,
    idperfil integer NOT NULL,
    nome character varying(100) NOT NULL,
    email character varying(100) NOT NULL,
    senha character varying(100) NOT NULL,
    ativo boolean NOT NULL
);
    DROP TABLE public."USUARIO";
       public         postgres    false    5            �            1259    16419    USUARIO_id_seq    SEQUENCE     r   CREATE SEQUENCE "USUARIO_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public."USUARIO_id_seq";
       public       postgres    false    171    5            �           0    0    USUARIO_id_seq    SEQUENCE OWNED BY     7   ALTER SEQUENCE "USUARIO_id_seq" OWNED BY "USUARIO".id;
            public       postgres    false    170            ;           2604    16447    id    DEFAULT     b   ALTER TABLE ONLY "MENU_TYPE" ALTER COLUMN id SET DEFAULT nextval('"MENU_TYPE_id_seq"'::regclass);
 =   ALTER TABLE public."MENU_TYPE" ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    174    175    175            :           2604    16430    id    DEFAULT     \   ALTER TABLE ONLY "PERFIL" ALTER COLUMN id SET DEFAULT nextval('"PERFIL_id_seq"'::regclass);
 :   ALTER TABLE public."PERFIL" ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    173    172    173            9           2604    16424    id    DEFAULT     ^   ALTER TABLE ONLY "USUARIO" ALTER COLUMN id SET DEFAULT nextval('"USUARIO_id_seq"'::regclass);
 ;   ALTER TABLE public."USUARIO" ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    170    171    171            �          0    16444 	   MENU_TYPE 
   TABLE DATA               ?   COPY "MENU_TYPE" (id, titulo, link, ativo, idmenu) FROM stdin;
    public       postgres    false    175   q#       �           0    0    MENU_TYPE_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('"MENU_TYPE_id_seq"', 1, false);
            public       postgres    false    174            �          0    16427    PERFIL 
   TABLE DATA               .   COPY "PERFIL" (id, titulo, ativo) FROM stdin;
    public       postgres    false    173   �#       �           0    0    PERFIL_id_seq    SEQUENCE SET     7   SELECT pg_catalog.setval('"PERFIL_id_seq"', 1, false);
            public       postgres    false    172            �          0    16455 	   PERMISSAO 
   TABLE DATA               M   COPY "PERMISSAO" (idperfil, idmenu, visualizar, gravar, remover) FROM stdin;
    public       postgres    false    176   �#       �          0    16421    USUARIO 
   TABLE DATA               E   COPY "USUARIO" (id, idperfil, nome, email, senha, ativo) FROM stdin;
    public       postgres    false    171   �#       �           0    0    USUARIO_id_seq    SEQUENCE SET     8   SELECT pg_catalog.setval('"USUARIO_id_seq"', 1, false);
            public       postgres    false    170            A           2606    16449    MENU_TYPE_pkey 
   CONSTRAINT     S   ALTER TABLE ONLY "MENU_TYPE"
    ADD CONSTRAINT "MENU_TYPE_pkey" PRIMARY KEY (id);
 F   ALTER TABLE ONLY public."MENU_TYPE" DROP CONSTRAINT "MENU_TYPE_pkey";
       public         postgres    false    175    175            ?           2606    16436    PERFIL_pkey 
   CONSTRAINT     M   ALTER TABLE ONLY "PERFIL"
    ADD CONSTRAINT "PERFIL_pkey" PRIMARY KEY (id);
 @   ALTER TABLE ONLY public."PERFIL" DROP CONSTRAINT "PERFIL_pkey";
       public         postgres    false    173    173            =           2606    16434    USUARIO_pkey 
   CONSTRAINT     O   ALTER TABLE ONLY "USUARIO"
    ADD CONSTRAINT "USUARIO_pkey" PRIMARY KEY (id);
 B   ALTER TABLE ONLY public."USUARIO" DROP CONSTRAINT "USUARIO_pkey";
       public         postgres    false    171    171            C           2606    16450    MENU_TYPE_idmenu_fkey    FK CONSTRAINT     y   ALTER TABLE ONLY "MENU_TYPE"
    ADD CONSTRAINT "MENU_TYPE_idmenu_fkey" FOREIGN KEY (idmenu) REFERENCES "MENU_TYPE"(id);
 M   ALTER TABLE ONLY public."MENU_TYPE" DROP CONSTRAINT "MENU_TYPE_idmenu_fkey";
       public       postgres    false    2113    175    175            E           2606    16463    PERMISSAO_idmenu_fkey    FK CONSTRAINT     y   ALTER TABLE ONLY "PERMISSAO"
    ADD CONSTRAINT "PERMISSAO_idmenu_fkey" FOREIGN KEY (idmenu) REFERENCES "MENU_TYPE"(id);
 M   ALTER TABLE ONLY public."PERMISSAO" DROP CONSTRAINT "PERMISSAO_idmenu_fkey";
       public       postgres    false    2113    176    175            D           2606    16458    PERMISSAO_idperfil_fkey    FK CONSTRAINT     z   ALTER TABLE ONLY "PERMISSAO"
    ADD CONSTRAINT "PERMISSAO_idperfil_fkey" FOREIGN KEY (idperfil) REFERENCES "PERFIL"(id);
 O   ALTER TABLE ONLY public."PERMISSAO" DROP CONSTRAINT "PERMISSAO_idperfil_fkey";
       public       postgres    false    2111    176    173            B           2606    16437    USUARIO_idperfil_fkey    FK CONSTRAINT     v   ALTER TABLE ONLY "USUARIO"
    ADD CONSTRAINT "USUARIO_idperfil_fkey" FOREIGN KEY (idperfil) REFERENCES "PERFIL"(id);
 K   ALTER TABLE ONLY public."USUARIO" DROP CONSTRAINT "USUARIO_idperfil_fkey";
       public       postgres    false    171    173    2111            �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �     