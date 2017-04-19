/**
@author evilnapsis
**/
drop database if exists ASIAN_FOOD;
create database ASIAN_FOOD;
use ASIAN_FOOD;

create table USUARIO(
	Id int not null auto_increment primary key,
	Nombre varchar(50) not null,
	Apellidos varchar(50) not null,
	NombreUsuario varchar(50),
	Correo varchar(255) not null,
	Contraseña varchar(60) not null,
	Fotografia varchar(255),
	is_active boolean not null default 1,
	is_admin boolean not null default 0,
	is_mesero boolean not null default 0,
	is_cajero boolean not null default 0,
	created_at datetime not null
);

insert into USUARIO(Nombre,Apellidos,Correo,Contraseña,is_admin,created_at) value ("Administrador", "","admin","90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad",1,NOW());

create table CATEGORIA(
	Id int not null auto_increment primary key,
	Nombre varchar(50) not null,
	is_active boolean not null default 1
);

insert into CATEGORIA (Nombre) value ("Tacos");
insert into CATEGORIA (Nombre) value ("Caldos");

/* tabla para almacenar las mesas del restaurant*/
create table MESA(
	Id int not null auto_increment primary key,
	Nombre varchar(50) not null,
	Capacidad int
);

insert into MESA (Nombre,Capacidad) values ("1",6),("2",6),("3",6),("4",6),("5",6),("6",6),("7",6),("8",6),("9",6),("10",6);

create table PRODUCTO(
	Id int not null auto_increment primary key,
	Codigo varchar(50) not null,
	Nombre varchar(50) not null,
	Descripcion varchar(50) not null,
	Preparacion varchar(50) not null,
	Precio_entrada float not null,
	Precio_salida float,
	Unidad varchar(255) not null,
	Presentancion varchar(255) not null,
	Duracion int, /* tiempo de preparacion en minutos */
	use_ingredient boolean not null default 1,
	is_active boolean not null default 1,
	Usuario_id int not null,
	Categoria_id int,
	foreign key (Usuario_id) references USUARIO(Id),
	foreign key (Categoria_id) references CATEGORIA(Id)
);

create table INGREDIENTE(
	Id int not null auto_increment primary key,
	Codigo varchar(50) not null,
	Nombre varchar(50) not null,
	Precio_entrada float not null,
	Precio_salida float,
	Unidad varchar(255) not null,
	is_active boolean not null default 1,
	Usuario_id int not null,
	foreign key (Usuario_id) references USUARIO(id)
);

create table PRODUCTO_INGREDIENTE(
	Id int not null auto_increment primary key,
	Product_id int not null,
	Ingredient_id int not null,
	q float,
	is_required boolean not null,
	foreign key (Product_id) references PRODUCTO(Id),
	foreign key (Ingredient_id) references INGREDIENTE(Id)
);


create table OPERACION_TIPO(
	id int not null auto_increment primary key,
	name varchar(50) not null
);

insert into OPERACION_TIPO (name) value ("entrada");
insert into OPERACION_TIPO (name) value ("salida");

create table VENTA(
	Id int not null auto_increment primary key,
	Mesa_id int, /* mesa */
	q int, /* cantidad de personas en la mesa */
	is_applied boolean not null default 0,
	Mesero_id int,
	Cajero_id int,
	foreign key (Mesero_id) references USUARIO(Id),	
	foreign key (Cajero_id) references USUARIO(Id),	
	created_at datetime not null
);

create table operation(
	Id int not null auto_increment primary key,
	Producto_id int not null,
	q float not null,
	Operacion_Tipo_id int not null,
	Venta_id int,
	is_oficial boolean not null default 0,
	created_at datetime not null,
	foreign key (Producto_id) references PRODUCTO(Id),
	foreign key (Operacion_Tipo_id) references OPERACION_TIPO(Id),
	foreign key (Venta_id) references VENTA(Id)
);
/* para gestionar el inventario de ingredientes */
create table VENTA2(
	Id int not null auto_increment primary key,
	Usuario_id int ,
	Operacion_Tipo_id int default 2,
	foreign key (Operacion_Tipo_id) references OPERACION_TIPO(Id),
	foreign key (Usuario_id) references USUARIO(Id),
	created_at datetime not null
);

create table OPERACION2(
	Id int not null auto_increment primary key,
	Ingrediente_id int not null,
	q float not null,
	Operacion_Tipo_id int not null,
	Venta_id int,
	is_oficial boolean not null default 0,
	created_at datetime not null,
	foreign key (Ingrediente_id) references INGREDIENTE(Id),
	foreign key (Operacion_Tipo_id) references OPERACION_TIPO(Id),
	foreign key (Venta_id) references VENTA2(Id)
);


/* no se usa actualmente */
create table GASTO(
	Id int not null auto_increment primary key,
	q int not null,
	Concepto varchar(255) not null,
	Unidad varchar(255) not null,
	Precio float not null,
	Categoria_id int not null,
	created_at datetime not null,
	foreign key (Categoria_id) references CATEGORIA(Id)
);
