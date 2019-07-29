<?php
  /*
     * #===========================================================#
     * #	Este fichero contiene las funciones de gestión     			 
     * #	de clientes de la capa de acceso a datos 		
     * #==========================================================#
     */
     
 /* Para esta funcion tendremos que editar el sql y meterle una funcion para, con el id de un cliente, pasarle valores nuevos y modificarlo*/ 

/*
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
*/

function modificar_cliente($conexion, $id_Cliente, $nombre, $apellidos, $fechaNacimiento,$email, $cp, $telefono, $direccion){
	try{
		$stmt=$conexion->prepare('CALL MODIFICAR_CLIENTE(:id_Cliente, :nombre, :apellidos, :fechaNac, :direccion, :email, :cp, :telefono)');
    $stmt->bindParam(':id_Cliente',$id_Cliente);
    $stmt->bindParam(':nombre',$nombre);
    $stmt->bindParam(':apellidos',$apellidos);
    $stmt->bindParam(':fechaNac',$fechaNacimiento);
    $stmt->bindParam(':direccion',$direccion);
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':cp',$cp);
    $stmt->bindParam(':telefono',$telefono);

		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
		}
}
/* Para esta funcion tendremos que editar el sql para pasarle el id del cliente al sql y borrarlo de la base de datos */
/*
	CREATE OR REPLACE PROCEDURE QUITAR_CLIENTE (ID_CLIENTE_A_QUITAR IN CLIENTE.ID_CLIENTE%TYPE) IS
BEGIN
    DELETE FROM CLIENTE WHERE ID_CLIENTE = ID_CLIENTE_A_QUITAR;
END;
*/

function borrar_cliente($conexion, $id_Cliente){
	try {
		$stmt=$conexion->prepare('CALL QUITAR_CLIENTE(:id_Cliente)');
		$stmt->bindParam(':id_Cliente',$id_Cliente);
		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }

}
/* Para esta funcion tendremos que editar el sql la cual añadira un cliente a la base de datos, pasandole todos los correspondientes parametros en el sql */
 /* 

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
 
//FUNCIONA INSERT INTO CLIENTE(NOMBRE, APELLIDOS, FECHA_NACIMIENTO, DIRECCION, CP, TELEFONO, EMAIL, FECHA_INICIO_RUTINA, FECHA_FIN_RUTINA)VALUES ('arbo', 'retrum', TO_DATE('2019-05-31 16:18:51', 'YYYY-MM-DD HH24:MI:SS'), 'sevilla', 41001, 123654789, 'qwasf@gmail.com', TO_DATE('2019-05-31 16:18:51', 'YYYY-MM-DD HH24:MI:SS'), TO_DATE('2019-06-01 16:19:01', 'YYYY-MM-DD HH24:MI:SS'));
END;


 */
function nuevo_cliente($conexion,$nombre,$apellidos,$fecha_nacimiento,$direccion,$email,$cp,$telefono,$ini_rutina,$fin_rutina) {
	$fechaNacimiento = date('d/m/Y', strtotime($usuario["FECHA_NACIMIENTO"]));

	try {
		$consulta = "CALL INSERTAR_CLIENTE(:nombre, :ape, :fec, :dir, :email, :cp, :telf, :fecha_ini, :fecha_fin)";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':nombre',$nombre);
    $stmt->bindParam(':ape',$apellidos);
    $stmt->bindParam(':fec',$fecha_nacimiento);
    $stmt->bindParam(':dir',$direccion);
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':cp',$cp);
    $stmt->bindParam(':telf',$telefono);
    $stmt->bindParam(':fecha_ini',$ini_rutina);
    $stmt->bindParam(':fecha_fin',$fin_rutina);
		
		$stmt->execute();
		
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
		// Si queremos visualizar la excepción durante la depuración: $e->getMessage();
    }
}

?>