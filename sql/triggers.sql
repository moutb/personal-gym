-- RN1

CREATE OR REPLACE TRIGGER RN1_calendario_validacion
BEFORE INSERT OR UPDATE ON CALENDARIO
FOR EACH ROW
DECLARE
    PRAGMA AUTONOMOUS_TRANSACTION; -- necesario para evitar el error ORA-04091: table ISSI.CALENDARIO is mutating, trigger/function may not see it (ver https://decipherinfosys.wordpress.com/2009/06/22/mutating-tabletrigger-error-and-how-to-resolve-it/)
    rowcount NUMBER;
BEGIN
    SELECT count(*) INTO rowcount 
    FROM CALENDARIO
    WHERE (:NEW.MES_INICIO < MES_INICIO AND :NEW.MES_FIN < MES_FIN)
            OR (:NEW.MES_INICIO > MES_INICIO AND :NEW.MES_FIN > MES_FIN); 

        --DBMS_OUTPUT.PUT_LINE('nueva fila [mes_inicio, mes_fin] = [':NEW.MES_INICIO',':NEW.MES_FIN']'); 
        --DBMS_OUTPUT.PUT_LINE('fila actual [mes_inicio, mes_fin] = ['calendar.MES_INICIO','calendar.MES_FIN']');

    IF rowcount <> 0 THEN
        raise_application_error(-20600,'ya existe un calendario activo para el periodo seleccionado');
    END IF;
END;

/

-- RN2

-- WARN: este trigger hace uso de una función DIAS_DEL_MES declarada en el fichero functions.sql

CREATE OR REPLACE TRIGGER RN2_cuota_cliente
BEFORE INSERT ON CUOTA
FOR EACH ROW
DECLARE
    CURSOR cuotas_cliente IS 
        SELECT C.ID_CUOTA, C.FECHA_EMISION, C.ID_CLIENTE, (SELECT MESES_VALIDEZ FROM TIPO_CUOTA WHERE CODIGO=C.CODIGO_TIPO_CUOTA) AS MESES_VALIDEZ
        FROM CUOTA C
        WHERE C.ID_CLIENTE = :NEW.ID_CLIENTE
        ORDER BY C.FECHA_EMISION DESC;
        
    ultima_cuota cuotas_cliente%ROWTYPE;
    last_date DATE;
BEGIN
    OPEN cuotas_cliente;
    FETCH cuotas_cliente INTO ultima_cuota;
    DBMS_OUTPUT.PUT_LINE('verificando cuota '||:NEW.ID_CUOTA||' (cuotas previas del cliente '||:NEW.ID_CLIENTE||': '||cuotas_cliente%ROWCOUNT||')');
    IF cuotas_cliente%FOUND THEN
        DBMS_OUTPUT.PUT_LINE('se ha encontrado como cuota más reciente '||ultima_cuota.ID_CUOTA||' con fecha emisión '||ultima_cuota.FECHA_EMISION||' para el cliente '||:NEW.ID_CLIENTE);
        
        IF ultima_cuota.MESES_VALIDEZ < 1 THEN
            last_date := ultima_cuota.FECHA_EMISION + FLOOR(DIAS_DEL_MES(ultima_cuota.FECHA_EMISION) * ultima_cuota.MESES_VALIDEZ);
        ELSE
            last_date := ADD_MONTHS(ultima_cuota.FECHA_EMISION, ultima_cuota.MESES_VALIDEZ);
        END IF;
        
        DBMS_OUTPUT.PUT_LINE('fecha validez ultima cuota: '||last_date);
        DBMS_OUTPUT.PUT_LINE('fecha emisión nueva cuota: '||:NEW.FECHA_EMISION);
        
        IF (:NEW.FECHA_EMISION < last_date) THEN
            raise_application_error(-20600,'ya se encuentra una cuota vigente en la fecha de emisión introducida');
        END IF;
        
    END IF;
    CLOSE cuotas_cliente;
END;

/


-- RN4 -------------------------------------------------------------------------

CREATE OR REPLACE TRIGGER RN4_compra_stock
AFTER INSERT OR UPDATE ON producto_x_compra
FOR EACH ROW
BEGIN

    UPDATE Producto 
    SET stock= stock + :NEW.cantidad 
    WHERE (:NEW.id_producto = id_producto);

END;

/

-- RN6 -------------------------------------------------------------------------
CREATE OR REPLACE TRIGGER RN6_venta_stock
AFTER INSERT OR UPDATE ON producto_x_venta
FOR EACH ROW
DECLARE
    producto_row producto%ROWTYPE;
    new_stock number(5, 2);
BEGIN
    SELECT * INTO producto_row FROM producto WHERE id_producto = :NEW.id_producto;

    new_stock := producto_row.stock - :NEW.cantidad;

    IF new_stock < 0 THEN
        raise_application_error(-20600,'no hay stock suficiente del producto vendido');
    END IF;

    UPDATE producto 
    SET stock= new_stock
    WHERE :NEW.id_producto = id_producto;
END;

