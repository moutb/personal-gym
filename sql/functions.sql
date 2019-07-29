-- Función que calcula los dias del mes solicitado

CREATE OR REPLACE FUNCTION DIAS_DEL_MES (FECHA DATE) RETURN NUMBER AS 
BEGIN
    RETURN 1 + TRUNC(LAST_DAY(FECHA)) - TRUNC(FECHA, 'MM');
END DIAS_DEL_MES;

/ 

-- Función de utilidad para ejecutar pruebas
CREATE OR REPLACE FUNCTION ASSERT_EQUALS (
    salida BOOLEAN,
    salida_esperada BOOLEAN
) RETURN VARCHAR2 AS
BEGIN
    IF salida = salida_esperada THEN
        RETURN 'EXITO';
    ELSE
        RETURN 'FALLO';
    END IF;
END ASSERT_EQUALS;

/

-- Función que cuenta las cuotas de un cliente
CREATE OR REPLACE FUNCTION count_cuotas_cliente (
    w_id_cliente IN cliente.id_cliente%TYPE
) RETURN NUMBER AS 
    rowcount NUMBER;
BEGIN
    SELECT COUNT(*) INTO rowcount FROM cuota
        WHERE id_cliente = w_id_cliente;
        
    RETURN rowcount;
END count_cuotas_cliente;