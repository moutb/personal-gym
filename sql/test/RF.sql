-- RF1 visualizar el balance mensual de compras.

SELECT *
FROM COMPRA
WHERE 
	EXTRACT(MONTH FROM FECHA) = w_mes
    AND EXTRACT(YEAR FROM FECHA) = w_anno;


-- RF2 visualizar el balance mensual de ventas.


SELECT *
FROM VENTA
WHERE 
	EXTRACT(MONTH FROM FECHA) = w_mes
    AND EXTRACT(YEAR FROM FECHA) = w_anno;


-- RF4 visualizar el número de cuotas por cliente.

SELECT count_cuotas_cliente(w_id_cliente)
FROM DUAL;