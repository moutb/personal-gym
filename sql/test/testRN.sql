--Prueba de RN1----------------------------------------------------------------
INSERT INTO calendario VALUES (null, 'calendario eventual', 6, 6);


--Prueba de RN2-----------------------------------------------------------------

-- ERROR PROVOCADO: INSERTAR UNA CUOTA EN UN PERIODO EN EL QUE YA SE ENCUENTRA EL ULTIMO PAGO VIGENTE
INSERT INTO CUOTA VALUES (null, TO_DATE('2019/02/28', 'yyyy/mm/dd'), 30.00, 'MITAD_MENSUAL', 1);


--Prueba de RN4----------------------------------------------------------------
INSERT INTO PRODUCTO VALUES(null, 'Botella plástico roja', 0);

INSERT INTO PRODUCTO_X_COMPRA VALUES (1, 4, 5.25, 7);

--Pueba de RN6------------------------------------------------------------------

UPDATE PRODUCTO_X_VENTA SET cantidad = 20 WHERE ID_VENTA = 1 AND ID_PRODUCTO = 3;

