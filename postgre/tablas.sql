drop table detalle;
drop sequence arriendo_ai;
drop sequence categoria_ai;
drop sequence sucursal_ai;
drop table sucursal_herramienta;
drop table arriendo;
drop table usuario;
drop table sucursal;
drop table herramienta;
drop table categoria;
drop table carrito;
drop table empresa;

create table usuario(
    rut int,
    nombres varchar(40),
    apellidos varchar(40),
    correo varchar(50),
    rol varchar(20),
    pass varchar(100),
    estado int,
    direccion varchar(50),
    celular int
);

drop sequence arriendo_ai;
create sequence arriendo_ai;   
    
drop sequence categoria_ai;
create sequence categoria_ai;   
 
drop sequence sucursal_ai;
create sequence sucursal_ai;

drop sequence empresa_ai;
create sequence empresa_ai;

create table arriendo(
    cod_arriendo int,
    fecha_inicio date,
    fecha_final date,
    total int,
    rut_u int,
    cod_s int,
    estado varchar(20),
    fecha_arriendo date
);
--alter table arriendo add fecha_arriendo date;

create table detalle(
    cod_h int,
    cantidad int,
    total_detalle int,
    id_a int
);

create table herramienta(
    cod_herramienta int,
    nombre varchar(100),
    descripcion varchar(200),
    url_foto varchar(100),
    cod_categoria int
);

create table sucursal_herramienta(
    cod_herramienta int,
    cod_sucursal int,
    stock int,
    precio int
);
--alter table sucursal_herramienta add precio int;
--alter table herramienta modify nombre varchar(50);

create table categoria(
    cod_categoria int,
    nombre varchar(30)
);

create table sucursal(
    cod_sucursal int,
    nombre varchar(30),
    direccion varchar(100),
    telefono int,
    url_foto varchar(100),
    cod_empresa int,
    comuna int
);

--alter table sucursal add column comuna int;
--alter table sucursal add cod_empresa int;

create table carrito(
    cod_herramienta int,
    cod_sucursal int,
    rut int,
    cantidad int,
    total int
);

create table empresa(
    cod_empresa int,
    nombre varchar(100)
);

create table region(
    region_id int,
    region_nombre varchar(50)
);

create table provincia(
    provincia_id int,
    provincia_nombre varchar(50),
    provincia_region_id int
);

create table comuna(
    comuna_id int,
    comuna_nombre varchar(50),
    comuna_provincia_id int
);

--alter table carrito add cod_sucursal int;

--alter table primary key
/*
    drop constraint pk_usuario;
    drop constraint pk_arriendo;
    drop constraint pk_detalle;
    drop constraint pk_herramienta;
    drop constraint pk_categoria;
    drop constraint pk_sucursal;

*/
alter table usuario add constraint pk_usuario primary key(rut);
alter table arriendo add constraint pk_arriendo primary key(cod_arriendo);
alter table detalle add constraint pk_detalle primary key(cod_h,id_a);
alter table herramienta add constraint pk_herramienta primary key(cod_herramienta);
alter table categoria add constraint pk_categoria primary key(cod_categoria);
alter table sucursal add constraint pk_sucursal primary key(cod_sucursal);
alter table carrito add constraint pk_carrito primary key(cod_herramienta,rut);
alter table sucursal_herramienta add constraint pk_sucursal_herramienta primary key(cod_herramienta,cod_sucursal);
alter table empresa add constraint pk_empresa primary key(cod_empresa);
alter table region add constraint pk_region primary key(region_id);
alter table provincia add constraint pk_provincia primary key(provincia_id);
alter table comuna add constraint pk_comuna primary key(comuna_id);

--alter table foreign key
/*
    drop constraint fk_usuario;
    drop constraint fk_sucursal;
    drop constraint fk_arriendo;
    drop constraint fk_herramienta;
    drop constraint fk_categoria;
*/

alter table arriendo add constraint fk_usuario foreign key(rut_u) references usuario(rut) on delete cascade;
alter table arriendo add constraint fk_sucursal foreign key(cod_s) references sucursal(cod_sucursal) on delete cascade;
alter table detalle add constraint fk_arriendo foreign key(id_a) references arriendo(cod_arriendo) on delete cascade;
alter table detalle add constraint fk_herramienta foreign key(cod_h) references herramienta(cod_herramienta) on delete cascade;
alter table herramienta add constraint fk_categoria foreign key(cod_categoria) references categoria(cod_categoria) on delete cascade;
alter table carrito add constraint fk_carrito foreign key(rut) references usuario(rut) on delete cascade;
alter table carrito add constraint fk_carrito1 foreign key(cod_herramienta) references herramienta(cod_herramienta) on delete cascade;
alter table carrito add constraint fk_carrito2 foreign key(cod_sucursal) references sucursal(cod_sucursal) on delete cascade;
alter table sucursal_herramienta add constraint fk_sucursal_herramienta foreign key(cod_herramienta) references herramienta(cod_herramienta) on delete cascade;
alter table sucursal_herramienta add constraint fj_sucursal_herramienta2 foreign key(cod_sucursal) references sucursal(cod_sucursal) on delete cascade;    
alter table sucursal add constraint fk_sucursal foreign key(cod_empresa) references empresa(cod_empresa) on delete cascade;
alter table sucursal add constraint fk_sucursal2 foreign key(comuna) references comuna(comuna_id) on delete cascade;
alter table comuna add constraint fk_comuna foreign key(comuna_provincia_id) references provincia(provincia_id) on delete cascade;
alter table provincia add constraint fk_provincia foreign key(provincia_region_id) references region(region_id) on delete cascade;