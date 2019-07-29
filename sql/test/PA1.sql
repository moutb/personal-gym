SET SERVEROUTPUT ON;

CREATE OR REPLACE PACKAGE PA1_calendario_clase AS
    
    PROCEDURE inicializar;
    
    PROCEDURE editar_clase (
        nombre_prueba VARCHAR2,
        salida_esperada BOOLEAN,
        id_clase IN clase.id_clase%TYPE,
        new_nombre IN clase.nombre%TYPE,
        new_codigo IN clase.codigo%TYPE
    );
    
    PROCEDURE editar_calendario (
        nombre_prueba VARCHAR2,
        salida_esperada BOOLEAN,
        id_calendario IN calendario.id_calendario%TYPE,
        new_nombre IN calendario.nombre%TYPE, 
        new_mes_inicio IN calendario.mes_inicio%TYPE,
        new_mes_fin IN calendario.mes_fin%TYPE
    );
   
    PROCEDURE vincular_clase_calendario (
        nombre_prueba VARCHAR2,
        salida_esperada BOOLEAN,
        id_calendario_x_clase IN calendario_x_clase.id_calendario_x_clase%TYPE,
        new_id_calendario IN calendario_x_clase.id_calendario%TYPE,
        new_id_clase IN calendario_x_clase.id_clase%TYPE,
        new_fecha_inicio IN calendario_x_clase.fecha_inicio%TYPE,
        new_duracion IN calendario_x_clase.duracion%TYPE,
        new_monitor IN calendario_x_clase.monitor%TYPE
    );
    
    PROCEDURE eliminar_clase (
        nombre_prueba VARCHAR2,
        salida_esperada BOOLEAN,
        id_clase IN clase.id_clase%TYPE
    );
    
    PROCEDURE eliminar_calendario (
        nombre_prueba VARCHAR2,
        salida_esperada BOOLEAN,
        id_calendario IN calendario.id_calendario%TYPE
    );
    
    PROCEDURE eliminar_calendario_x_clase (
        nombre_prueba VARCHAR2,
        salida_esperada BOOLEAN,
        id_calendario_x_clase IN calendario_x_clase.id_calendario_x_clase%TYPE
    );

END PA1_calendario_clase;
/

-- PACKAGE PRUEBAS_CALENDARIO
CREATE OR REPLACE PACKAGE BODY PA1_calendario_clase AS
    
    PROCEDURE inicializar IS
    BEGIN
        DELETE FROM calendario_x_clase;
        DELETE FROM clase;
        DELETE FROM calendario;
    END inicializar;
    
    PROCEDURE editar_clase (
        nombre_prueba VARCHAR2,
        salida_esperada BOOLEAN,
        id_clase IN clase.id_clase%TYPE,
        new_nombre IN clase.nombre%TYPE,
        new_codigo IN clase.codigo%TYPE
    ) IS
        salida BOOLEAN := TRUE;
        clase_row clase%ROWTYPE;
        curr_id_clase clase.id_clase%TYPE;
    BEGIN
        IF id_clase IS NULL THEN
            INSERT INTO clase VALUES (null, new_nombre, new_codigo);
            curr_id_clase := seq_clase_id_clase.CURRVAL;
        ELSE
            UPDATE clase SET nombre = new_nombre, codigo = new_codigo WHERE id_clase = id_clase;
            curr_id_clase := id_clase;
        END IF;
        
        SELECT * INTO clase_row FROM clase WHERE id_clase = curr_id_clase;
        
        IF clase_row.nombre <> new_nombre OR clase_row.codigo <> new_codigo THEN
            salida := FALSE;
        END IF;
        COMMIT WORK;
        
        DBMS_OUTPUT.PUT_LINE(nombre_prueba||': '||ASSERT_EQUALS(salida, salida_esperada));
        
        EXCEPTION
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE(nombre_prueba||': '||ASSERT_EQUALS(FALSE, salida_esperada));
            ROLLBACK;
    END editar_clase;
    
    PROCEDURE editar_calendario (
        nombre_prueba VARCHAR2,
        salida_esperada BOOLEAN,
        id_calendario IN calendario.id_calendario%TYPE,
        new_nombre IN calendario.nombre%TYPE,
        new_mes_inicio IN calendario.mes_inicio%TYPE,
        new_mes_fin IN calendario.mes_fin%TYPE
    ) IS
        salida BOOLEAN := TRUE;
        calendario_row calendario%ROWTYPE;
        curr_id calendario.id_calendario%TYPE;
    BEGIN
    
        IF id_calendario IS NULL THEN
            INSERT INTO calendario VALUES (null, new_nombre, new_mes_inicio, new_mes_fin);
            curr_id := seq_calendario_id_calendario.CURRVAL;
        ELSE
            UPDATE calendario SET nombre = new_nombre, mes_inicio = new_mes_inicio, mes_fin = new_mes_fin WHERE id_calendario = id_calendario;
            curr_id := id_calendario;
        END IF;
        
        SELECT * INTO calendario_row FROM calendario WHERE id_calendario = curr_id;
        
        IF calendario_row.nombre <> new_nombre OR calendario_row.mes_inicio <> new_mes_inicio OR calendario_row.mes_fin <> new_mes_fin THEN
            salida := FALSE;
        END IF;
        COMMIT WORK;
        
        DBMS_OUTPUT.PUT_LINE(nombre_prueba||': '||ASSERT_EQUALS(salida, salida_esperada));
        
        EXCEPTION
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE(nombre_prueba||': '||ASSERT_EQUALS(FALSE, salida_esperada));
            ROLLBACK;
    END editar_calendario;
    
    
    PROCEDURE vincular_clase_calendario (
        nombre_prueba VARCHAR2,
        salida_esperada BOOLEAN,
        id_calendario_x_clase IN calendario_x_clase.id_calendario_x_clase%TYPE,
        new_id_calendario IN calendario_x_clase.id_calendario%TYPE,
        new_id_clase IN calendario_x_clase.id_clase%TYPE,
        new_fecha_inicio IN calendario_x_clase.fecha_inicio%TYPE,
        new_duracion IN calendario_x_clase.duracion%TYPE,
        new_monitor IN calendario_x_clase.monitor%TYPE
    ) IS
        salida BOOLEAN := TRUE;
        c_row calendario_x_clase%ROWTYPE;
        curr_id calendario_x_clase.id_calendario_x_clase%TYPE;
    BEGIN
    
        IF id_calendario_x_clase IS NULL THEN
            INSERT INTO calendario_x_clase VALUES (null, new_id_calendario, new_id_clase, new_fecha_inicio, new_duracion, new_monitor);
            curr_id := seq_cal_x_clase_id_cal_x_clase.CURRVAL;
        ELSE
            UPDATE calendario_x_clase SET 
                id_calendario = new_id_calendario,
                id_clase = new_id_clase,
                fecha_inicio = new_fecha_inicio,
                duracion = new_duracion,
                monitor = new_monitor
            WHERE id_calendario_x_clase = id_calendario_x_clase;
            curr_id := id_calendario_x_clase;
        END IF;
        
        SELECT * INTO c_row FROM calendario_x_clase WHERE id_calendario_x_clase = curr_id;
        
        IF new_id_calendario <> c_row.id_calendario OR new_id_clase <> c_row.id_clase OR new_fecha_inicio <> c_row.fecha_inicio
            OR new_duracion <> c_row.duracion OR new_monitor <> c_row.monitor THEN
            salida := FALSE;
        END IF;
        COMMIT WORK;
        
        DBMS_OUTPUT.PUT_LINE(nombre_prueba||': '||ASSERT_EQUALS(salida, salida_esperada));
        
        EXCEPTION
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE(nombre_prueba||': '||ASSERT_EQUALS(FALSE, salida_esperada));
            ROLLBACK;
    END vincular_clase_calendario;
    
    PROCEDURE eliminar_clase (
        nombre_prueba VARCHAR2,
        salida_esperada BOOLEAN,
        id_clase IN clase.id_clase%TYPE
    ) IS
        salida BOOLEAN := TRUE;
        rowcount NUMBER;
    BEGIN
        DELETE FROM clase WHERE id_clase = id_clase;
        
        SELECT count(*) INTO rowcount FROM clase WHERE id_clase = id_clase;
        
        IF rowcount <> 0 THEN
            salida := FALSE;
        END IF;
        COMMIT WORK;
        
        DBMS_OUTPUT.PUT_LINE(nombre_prueba||': '||ASSERT_EQUALS(salida, salida_esperada));
        
        EXCEPTION
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE(nombre_prueba||': '||ASSERT_EQUALS(FALSE, salida_esperada));
            ROLLBACK;
        
    END eliminar_clase;
    
    PROCEDURE eliminar_calendario (
        nombre_prueba VARCHAR2,
        salida_esperada BOOLEAN,
        id_calendario IN calendario.id_calendario%TYPE
    ) IS
        salida BOOLEAN := TRUE;
        rowcount NUMBER;
    BEGIN
        DELETE FROM calendario WHERE id_calendario = id_calendario;
        
        SELECT count(*) INTO rowcount FROM calendario WHERE id_calendario = id_calendario;
        
        IF rowcount <> 0 THEN
            salida := FALSE;
        END IF;
        COMMIT WORK;
        
        DBMS_OUTPUT.PUT_LINE(nombre_prueba||': '||ASSERT_EQUALS(salida, salida_esperada));
        
        EXCEPTION
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE(nombre_prueba||': '||ASSERT_EQUALS(FALSE, salida_esperada));
            ROLLBACK;
    
    END eliminar_calendario;    
    
    PROCEDURE eliminar_calendario_x_clase (
        nombre_prueba VARCHAR2,
        salida_esperada BOOLEAN,
        id_calendario_x_clase IN calendario_x_clase.id_calendario_x_clase%TYPE
    ) IS
        salida BOOLEAN := TRUE;
        rowcount NUMBER;
    BEGIN
        DELETE FROM calendario_x_clase WHERE id_calendario_x_clase = id_calendario_x_clase;
        
        SELECT count(*) INTO rowcount FROM calendario_x_clase WHERE id_calendario_x_clase = id_calendario_x_clase;
        
        IF rowcount <> 0 THEN
            salida := FALSE;
        END IF;
        COMMIT WORK;
        
        DBMS_OUTPUT.PUT_LINE(nombre_prueba||': '||ASSERT_EQUALS(salida, salida_esperada));
        
        EXCEPTION
        WHEN OTHERS THEN
            DBMS_OUTPUT.PUT_LINE(nombre_prueba||': '||ASSERT_EQUALS(FALSE, salida_esperada));
            ROLLBACK;
    
    END eliminar_calendario_x_clase;  

END PA1_calendario_clase;

/
DECLARE
    id_calendario calendario.id_calendario%TYPE;
    id_clase clase.id_clase%TYPE;
    id_calendario_x_clase calendario_x_clase.id_calendario_x_clase%TYPE;
BEGIN    
    -- Ejecucion de pruebas para verificar RN1  
    PA1_calendario_clase.inicializar;
    PA1_calendario_clase.editar_calendario('crear calendario primavera (enero - mayo)', TRUE, null, 'calendario primavera', 1, 5);
    PA1_calendario_clase.editar_calendario('crear calendario verano (junio - agosto)', TRUE, null, 'calendario verano', 6, 8);
    
    DBMS_OUTPUT.PUT_LINE('');
    
    PA1_calendario_clase.editar_clase('crear clase de yoga', TRUE, null, 'Yoga', 'CLYOGA');
    PA1_calendario_clase.editar_clase('crear clase de spinning', TRUE, null, 'Spinning', 'CLSPINNING');
    PA1_calendario_clase.editar_clase('crear clase de step', TRUE, null, 'Step', 'CLSTEP');
    PA1_calendario_clase.editar_clase('crear clase de taekwondo', TRUE, null, 'Taekwondo', 'CLTAEKWONDO');
    
    DBMS_OUTPUT.PUT_LINE('');
    
    SELECT id_calendario INTO id_calendario FROM calendario WHERE nombre = 'calendario primavera';
    
    SELECT id_clase INTO id_clase FROM clase WHERE codigo = 'CLTAEKWONDO';
    
    DBMS_OUTPUT.PUT_LINE('id registrada: calendario con nombre "calendario primavera": '||id_calendario);
    
    DBMS_OUTPUT.PUT_LINE('id registrada: clase con codigo "CLTAEKWONDO": '||id_clase);
    
    DBMS_OUTPUT.PUT_LINE('');
    
    PA1_calendario_clase.vincular_clase_calendario('vinculamos la clase "Taekwondo" al calendario de primavera', TRUE, null, id_calendario, id_clase, TO_DATE('2019/01/01 09:00:00', 'YYYY/MM/DD HH:MI:SS'), 60, 'Julián Guerrero');
    
    SELECT id_calendario_x_clase INTO id_calendario_x_clase FROM calendario_x_clase WHERE id_clase = id_clase AND id_calendario = id_calendario;
    
    DBMS_OUTPUT.PUT_LINE('');
    
    PA1_calendario_clase.editar_clase('editar la clase "Taekwondo"', TRUE, id_clase, 'Taekwondo Class', 'CLTAEKWONDO');
    
    PA1_calendario_clase.vincular_clase_calendario('editar la vinculación de la clase "Taekwondo" con calendario de primavera', TRUE, null, id_calendario, id_clase, TO_DATE('2019/01/01 09:00:00', 'YYYY/MM/DD HH:MI:SS'), 90, 'Julián Guerrero');
    
    PA1_calendario_clase.editar_calendario('editar el calendario de primavera', TRUE, id_calendario, 'calendario primavera', 2, 5);
    
    DBMS_OUTPUT.PUT_LINE('');
    
    PA1_calendario_clase.eliminar_calendario_x_clase('desvinculamos la clase "Taekwondo Class" del calendario de primavera', TRUE, id_calendario_x_clase);
    
    PA1_calendario_clase.eliminar_calendario('eliminar calendario de primavera', TRUE, id_calendario);
    
    PA1_calendario_clase.eliminar_clase('eliminar clase "Taekwondo Class"', TRUE, id_clase);

END;