drop table calendario_x_clase PURGE;
drop table calendario PURGE;
drop table clase PURGE;

drop table actividad_x_serie PURGE;
drop table actividad PURGE;
drop table serie PURGE;
drop table ejercicio_x_rutina PURGE;
drop table ejercicio PURGE;
drop table tipo_ejercicio PURGE;

drop table mobiliario_deportivo_x_compra PURGE;
drop table producto_x_compra PURGE;
drop table producto_x_venta PURGE;
drop table compra PURGE;
drop table venta PURGE;
drop table mobiliario_deportivo PURGE;
drop table producto PURGE;

drop table habito_x_cliente PURGE;
drop table cuota PURGE;
drop table tipo_cuota PURGE;
drop table habito PURGE;
drop table cliente PURGE;
drop table rutina PURGE;

CREATE TABLE calendario (
	id_calendario NUMBER(10) PRIMARY KEY,
	nombre VARCHAR2(50) NOT NULL,
	mes_inicio integer NOT NULL,
	mes_fin integer NOT NULL
);


CREATE TABLE clase (
	id_clase NUMBER(10) PRIMARY KEY,
	nombre VARCHAR2(50),
	codigo VARCHAR2(25)
);


CREATE TABLE calendario_x_clase (
	id_calendario_x_clase NUMBER(10) PRIMARY KEY,
	id_calendario NUMBER(10),
	id_clase NUMBER(10),
	fecha_inicio DATE,
	duracion integer,
	monitor VARCHAR2(25),
	FOREIGN KEY (id_calendario) REFERENCES calendario,
	FOREIGN KEY (id_clase) REFERENCES clase
);


CREATE TABLE compra (
	id_compra NUMBER(10) PRIMARY KEY,
	descripcion VARCHAR2(200),
	total number(11, 2) DEFAULT 0,
	fecha date DEFAULT SYSDATE
);


CREATE TABLE venta (
	id_venta NUMBER(10) PRIMARY KEY,
	descripcion VARCHAR2(200),
	total number(11, 2) DEFAULT 0,
	fecha date DEFAULT SYSDATE
);


CREATE TABLE mobiliario_deportivo (
	id_mobiliario_deportivo NUMBER(10) PRIMARY KEY,
	nombre VARCHAR2(25) NOT NULL,
	stock number(3, 2) DEFAULT 0,
	proveedor VARCHAR2(25)
);


CREATE TABLE producto (
	id_producto NUMBER(2) PRIMARY KEY,
	nombre VARCHAR2(25) NOT NULL,
	stock number(5, 2) DEFAULT 0
);


CREATE TABLE mobiliario_deportivo_x_compra (
	id_compra NUMBER(10),
	id_mobiliario_deportivo NUMBER(10),
	precio number(7, 2) NOT NULL,
	cantidad number(5, 2) DEFAULT 1,
	PRIMARY KEY (id_compra, id_mobiliario_deportivo),
	FOREIGN KEY (id_compra) REFERENCES compra,
	FOREIGN KEY (id_mobiliario_deportivo) REFERENCES mobiliario_deportivo
);


CREATE TABLE producto_x_compra (
	id_compra NUMBER(10),
	id_producto NUMBER(10),
	precio number(7, 2) NOT NULL,
	cantidad number(5, 2) DEFAULT 1,
	PRIMARY KEY (id_compra, id_producto),
	FOREIGN KEY (id_compra) REFERENCES compra,
	FOREIGN KEY (id_producto) REFERENCES producto
);


CREATE TABLE producto_x_venta (
	id_venta NUMBER(10),
	id_producto NUMBER(10),
	precio number(7, 2) NOT NULL,
	cantidad number(5, 2) DEFAULT 1,
	PRIMARY KEY (id_venta, id_producto),
	FOREIGN KEY (id_venta) REFERENCES venta,
	FOREIGN KEY (id_producto) REFERENCES producto
);

CREATE TABLE rutina (
	id_rutina NUMBER(10) PRIMARY KEY,
	nombre VARCHAR2(80) NOT NULL
);

CREATE TABLE cliente (
	id_cliente NUMBER(10) PRIMARY KEY,
	nombre VARCHAR2(25) NOT NULL,
	apellidos VARCHAR2(35),
	fecha_nacimiento date NOT NULL,
	direccion VARCHAR2(40),
	cp number(8, 2),
	telefono number(11, 2),
	email VARCHAR2(25),
	fecha_alta date DEFAULT SYSDATE,
	fecha_baja date,
	id_rutina NUMBER(10),
	fecha_inicio_rutina date NOT NULL,
	fecha_fin_rutina date NOT NULL,
	FOREIGN KEY (id_rutina) REFERENCES rutina ON DELETE CASCADE
);


CREATE TABLE habito (
	id_habito NUMBER(10) PRIMARY KEY,
	pregunta VARCHAR2(100) NOT NULL
);


CREATE TABLE habito_x_cliente (
	id_cliente NUMBER(10) NOT NULL,
	id_habito NUMBER(10) NOT NULL,
	respuesta VARCHAR2(100) NOT NULL,
	PRIMARY KEY (id_cliente, id_habito),
	FOREIGN KEY (id_cliente) REFERENCES cliente,
	FOREIGN KEY (id_habito) REFERENCES habito
);


CREATE TABLE tipo_cuota (
	codigo VARCHAR2(15) PRIMARY KEY,
	meses_validez NUMBER(5, 2) NOT NULL
);


CREATE TABLE cuota (
	id_cuota NUMBER(10) PRIMARY KEY,
	fecha_emision date NOT NULL,
	precio number(7, 2) NOT NULL,
	codigo_tipo_cuota VARCHAR2(15) NOT NULL,
	id_cliente NUMBER(10) NOT NULL,
	FOREIGN KEY (codigo_tipo_cuota) REFERENCES tipo_cuota,
	FOREIGN KEY (id_cliente) REFERENCES cliente
);


CREATE TABLE tipo_ejercicio (
	codigo VARCHAR2(20) PRIMARY KEY
);


CREATE TABLE ejercicio (
	id_ejercicio NUMBER(10) PRIMARY KEY,
	nombre VARCHAR2(80) NOT NULL,
	codigo_tipo_ejercicio VARCHAR2(20) NOT NULL
);


CREATE TABLE ejercicio_x_rutina (
	id_ejercicio_x_rutina NUMBER(10) PRIMARY KEY,
	id_rutina NUMBER(10) NOT NULL,
	id_ejercicio NUMBER(10) NOT NULL,
	dia_semana integer NOT NULL, 
	orden integer NOT NULL,
	FOREIGN KEY (id_rutina) REFERENCES rutina,
	FOREIGN KEY (id_ejercicio) REFERENCES ejercicio
);


CREATE TABLE serie (
	id_serie NUMBER(10) PRIMARY KEY,
	id_ejercicio NUMBER(10) NOT NULL,
	orden integer NOT NULL,
	FOREIGN KEY (id_ejercicio) REFERENCES ejercicio
);


CREATE TABLE actividad (
	id_actividad NUMBER(10) PRIMARY KEY,
	nombre VARCHAR2(80) NOT NULL,
	codigo VARCHAR(20) NOT NULL,
	codigo_tipo_ejercicio VARCHAR(20) NOT NULL
);


CREATE TABLE actividad_x_serie (
	id_actividad NUMBER(10),
	id_serie NUMBER(10),
	tiempo_espera integer NOT NULL,
	numero_repeticiones integer NOT NULL,
	orden integer NOT NULL,
	PRIMARY KEY (id_actividad, id_serie),
	FOREIGN KEY (id_actividad) REFERENCES actividad,
	FOREIGN KEY (id_serie) REFERENCES serie
);