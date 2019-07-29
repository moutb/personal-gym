DROP SEQUENCE seq_calendario_id_calendario;
CREATE SEQUENCE seq_calendario_id_calendario MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_calendario 
BEFORE INSERT ON calendario
FOR EACH ROW
BEGIN
    IF :NEW.ID_CALENDARIO IS NULL THEN
        SELECT seq_calendario_id_calendario.NEXTVAL INTO :NEW.ID_CALENDARIO FROM DUAL;
    END IF;
END;

/

DROP SEQUENCE seq_clase_id_clase;
CREATE SEQUENCE seq_clase_id_clase MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_clase
BEFORE INSERT ON clase
FOR EACH ROW
BEGIN
    IF :NEW.ID_CLASE IS NULL THEN
        SELECT seq_clase_id_clase.NEXTVAL INTO :NEW.ID_CLASE FROM DUAL;
    END IF;
END;

/

DROP SEQUENCE seq_actividad_id_actividad;
CREATE SEQUENCE seq_actividad_id_actividad MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_actividad
BEFORE INSERT ON actividad
FOR EACH ROW
BEGIN
    IF :NEW.ID_ACTIVIDAD IS NULL THEN
        SELECT seq_actividad_id_actividad.NEXTVAL INTO :NEW.ID_ACTIVIDAD FROM DUAL;
    END IF;
END;

/

DROP SEQUENCE seq_serie_id_serie;
CREATE SEQUENCE seq_serie_id_serie MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_serie
BEFORE INSERT ON serie
FOR EACH ROW
BEGIN
    IF :NEW.ID_SERIE IS NULL THEN
        SELECT seq_serie_id_serie.NEXTVAL INTO :NEW.ID_SERIE FROM DUAL;
    END IF;
END;

/

DROP SEQUENCE seq_cal_x_clase_id_cal_x_clase;
CREATE SEQUENCE seq_cal_x_clase_id_cal_x_clase MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_calendario_x_clase
BEFORE INSERT ON calendario_x_clase
FOR EACH ROW
BEGIN
    IF :NEW.ID_CALENDARIO_X_CLASE IS NULL THEN
        SELECT seq_cal_x_clase_id_cal_x_clase.NEXTVAL INTO :NEW.ID_CALENDARIO_X_CLASE FROM DUAL;
    END IF;
END;

/

DROP SEQUENCE seq_cliente_id_cliente;
CREATE SEQUENCE seq_cliente_id_cliente MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_cliente
BEFORE INSERT ON cliente
FOR EACH ROW
BEGIN
    IF :NEW.ID_CLIENTE IS NULL THEN
        SELECT seq_cliente_id_cliente.NEXTVAL INTO :NEW.ID_CLIENTE FROM DUAL;
    END IF;
END;

/

DROP SEQUENCE seq_compra_id_compra;
CREATE SEQUENCE seq_compra_id_compra MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_compra_id_compra
BEFORE INSERT ON compra
FOR EACH ROW
BEGIN
    IF :NEW.ID_COMPRA IS NULL THEN
        SELECT seq_compra_id_compra.NEXTVAL INTO :NEW.ID_COMPRA FROM DUAL;
    END IF;
END;

/

DROP SEQUENCE seq_cuota_id_cuota;
CREATE SEQUENCE seq_cuota_id_cuota MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_cuota_id_cuota
BEFORE INSERT ON cuota
FOR EACH ROW
BEGIN
    IF :NEW.ID_CUOTA IS NULL THEN
        SELECT seq_cuota_id_cuota.NEXTVAL INTO :NEW.ID_CUOTA FROM DUAL;
    END IF;
END;

/

DROP SEQUENCE seq_ejercicio_id_ejercicio;
CREATE SEQUENCE seq_ejercicio_id_ejercicio MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_ejercicio_id_ejercicio
BEFORE INSERT ON ejercicio
FOR EACH ROW
BEGIN
    IF :NEW.ID_EJERCICIO IS NULL THEN
        SELECT seq_ejercicio_id_ejercicio.NEXTVAL INTO :NEW.ID_EJERCICIO FROM DUAL;
    END IF;
END;

/

DROP SEQUENCE seq_eje_x_rut_id_eje_x_rutina;
CREATE SEQUENCE seq_eje_x_rut_id_eje_x_rutina MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_ejercicio_x_rutina
BEFORE INSERT ON ejercicio_x_rutina
FOR EACH ROW
BEGIN
    IF :NEW.ID_EJERCICIO_X_RUTINA IS NULL THEN
        SELECT seq_eje_x_rut_id_eje_x_rutina.NEXTVAL INTO :NEW.ID_EJERCICIO_X_RUTINA FROM DUAL;
    END IF;
END;

/

DROP SEQUENCE seq_habito_id_habito;
CREATE SEQUENCE seq_habito_id_habito MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_habito
BEFORE INSERT ON habito
FOR EACH ROW
BEGIN
    IF :NEW.ID_HABITO IS NULL THEN
        SELECT seq_habito_id_habito.NEXTVAL INTO :NEW.ID_HABITO FROM DUAL;
    END IF;
END;

/

DROP SEQUENCE seq_mob_depor_id_mob_deportivo;
CREATE SEQUENCE seq_mob_depor_id_mob_deportivo MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_mobiliario_deportivo
BEFORE INSERT ON mobiliario_deportivo
FOR EACH ROW
BEGIN
    IF :NEW.ID_MOBILIARIO_DEPORTIVO IS NULL THEN
        SELECT seq_mob_depor_id_mob_deportivo.NEXTVAL INTO :NEW.ID_MOBILIARIO_DEPORTIVO FROM DUAL;
    END IF;
END;

/

DROP SEQUENCE seq_producto_id_producto;
CREATE SEQUENCE seq_producto_id_producto MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_producto
BEFORE INSERT ON producto
FOR EACH ROW
BEGIN
    IF :NEW.ID_PRODUCTO IS NULL THEN
        SELECT seq_producto_id_producto.NEXTVAL INTO :NEW.ID_PRODUCTO FROM DUAL;
    END IF;
END;

/

DROP SEQUENCE seq_rutina_id_rutina;
CREATE SEQUENCE seq_rutina_id_rutina MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_rutina
BEFORE INSERT ON rutina
FOR EACH ROW
BEGIN
    IF :NEW.ID_RUTINA IS NULL THEN
        SELECT seq_rutina_id_rutina.NEXTVAL INTO :NEW.ID_RUTINA FROM DUAL;
    END IF;
END;

/

DROP SEQUENCE seq_venta_id_venta;
CREATE SEQUENCE seq_venta_id_venta MINVALUE 0 INCREMENT BY 1 START WITH 1;

/

CREATE OR REPLACE TRIGGER seq_venta
BEFORE INSERT ON venta
FOR EACH ROW
BEGIN
    IF :NEW.ID_VENTA IS NULL THEN
        SELECT seq_venta_id_venta.NEXTVAL INTO :NEW.ID_VENTA FROM DUAL;
    END IF;
END;


/

CREATE OR REPLACE PROCEDURE QUITAR_CLIENTE (ID_CLIENTE_A_QUITAR IN CLIENTE.ID_CLIENTE%TYPE) IS
BEGIN
    DELETE FROM CLIENTE WHERE ID_CLIENTE = ID_CLIENTE_A_QUITAR;
END;

/

CREATE OR REPLACE PROCEDURE INSERTAR_CLIENTE 
  (P_NOM IN CLIENTE.NOMBRE%TYPE,
   P_APE IN CLIENTE.APELLIDOS%TYPE,
   P_FECHA_NAC IN CLIENTE.FECHA_NACIMIENTO%TYPE,
   P_DIR IN CLIENTE.DIRECCION%TYPE,
   P_EMAIL IN CLIENTE.EMAIL%TYPE,
   P_CP IN CLIENTE.CP%TYPE,
   P_TELF IN CLIENTE.TELEFONO%TYPE,
   P_FECH_INI_RUTINA IN CLIENTE.FECHA_INICIO_RUTINA%TYPE,
   P_FECH_FIN_RUTINA IN CLIENTE.FECHA_FIN_RUTINA%TYPE
   ) IS
BEGIN 
  INSERT INTO CLIENTE(NOMBRE, APELLIDOS, FECHA_NACIMIENTO, DIRECCION, CP, TELEFONO, EMAIL, FECHA_INICIO_RUTINA, FECHA_FIN_RUTINA) 
  VALUES (P_NOM, P_APE, P_FECHA_NAC, P_DIR, P_CP, P_TELF, P_EMAIL, P_FECH_INI_RUTINA, P_FECH_FIN_RUTINA);
END;

/

CREATE OR REPLACE PROCEDURE MODIFICAR_CLIENTE 
    (ID_CLIENTE_A_MOD IN CLIENTE.ID_CLIENTE%TYPE,
    NOMBRE_A_MOD IN CLIENTE.NOMBRE%TYPE,
    APELLIDOS_A_MOD IN CLIENTE.APELLIDOS%TYPE,
    FECHA_NAC_A_MOD IN CLIENTE.FECHA_NACIMIENTO%TYPE,
    DIRECCION_A_MOD IN CLIENTE.DIRECCION%TYPE,
    EMAIL_A_MOD IN CLIENTE.EMAIL%TYPE,
    CP_A_MOD IN CLIENTE.CP%TYPE,
    TELEFONO_A_MOD IN CLIENTE.TELEFONO%TYPE) IS
BEGIN
  UPDATE CLIENTE 
    SET NOMBRE=NOMBRE_A_MOD, APELLIDOS= APELLIDOS_A_MOD, FECHA_NACIMIENTO= FECHA_NAC_A_MOD,
    EMAIL= EMAIL_A_MOD, CP= CP_A_MOD, TELEFONO= TELEFONO_A_MOD, DIRECCION= DIRECCION_A_MOD
  WHERE ID_CLIENTE = ID_CLIENTE_A_MOD;
END;

/

CREATE OR REPLACE PROCEDURE MODIFICAR_PRODUCTO
(ID_PRODUCTO_A_MOD IN PRODUCTO.ID_PRODUCTO%TYPE,
 NOM_PRODUCTO_A_MOD IN PRODUCTO.NOMBRE%TYPE,
 STOCK_A_MOD IN PRODUCTO.STOCK%TYPE) IS
BEGIN
  UPDATE PRODUCTO SET NOMBRE=NOM_PRODUCTO_A_MOD,STOCK=STOCK_A_MOD
  WHERE ID_PRODUCTO = ID_PRODUCTO_A_MOD;
END;

/

CREATE OR REPLACE PROCEDURE QUITAR_PRODUCTO 
(ID_PRODUCTO_A_QUITAR IN PRODUCTO.ID_PRODUCTO%TYPE) IS
BEGIN
    DELETE FROM PRODUCTO WHERE ID_PRODUCTO = ID_PRODUCTO_A_QUITAR;
   
END;

/

CREATE OR REPLACE PROCEDURE INSERTAR_COMPRA (ID_COMPRA IN COMPRA.ID_COMPRA%TYPE,
DESCRIPCION IN COMPRA.DESCRIPCION%TYPE,TOTAL IN COMPRA.TOTAL%TYPE,FECHA IN COMPRA.FECHA%TYPE) IS
BEGIN
    INSERT INTO COMPRA ("ID_COMPRA","DESCRIPCION","TOTAL","FECHA") VALUES (ID_COMPRA,DESCRIPCION,TOTAL,FECHA);
   
END;

/

CREATE OR REPLACE PROCEDURE INSERTAR_PRODUCTO_POR_COMPRA (ID_COMPRA IN PRODUCTO_X_COMPRA.ID_COMPRA%TYPE,
ID_PRODUCTO IN PRODUCTO_X_COMPRA.ID_PRODUCTO%TYPE,PRECIO IN PRODUCTO_X_COMPRA.PRECIO%TYPE,CANTIDAD IN PRODUCTO_X_COMPRA.CANTIDAD%TYPE) IS
BEGIN
    INSERT INTO PRODUCTO_X_COMPRA ("ID_COMPRA","ID_PRODUCTO","PRECIO","CANTIDAD") VALUES (ID_COMPRA,ID_PRODUCTO,PRECIO,CANTIDAD);

END;    

/

CREATE OR REPLACE PROCEDURE INSERTAR_PRODUCTO 
(ID_PRODUCTO in PRODUCTO.ID_PRODUCTO%TYPE,NOMBRE IN PRODUCTO.NOMBRE%TYPE,
 STOCK IN PRODUCTO.STOCK%TYPE) IS
BEGIN
    INSERT INTO PRODUCTO VALUES(ID_PRODUCTO,NOMBRE,STOCK);
   
END;