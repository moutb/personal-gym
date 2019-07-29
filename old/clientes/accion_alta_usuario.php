<?php
	session_start();

	$include_path = dirname(__FILE__) . '/../';

		require_once($include_path . "persistence/db-connection.php");
		require_once("gestionarClientes.php");
		
	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["cliente"])) {
		$nuevoUsuario = $_SESSION["cliente"];
		unset($_SESSION["cliente"]);
		

		$conexion = createConnection();
		$excepcion = nuevo_cliente($conexion,$nuevoUsuario["NOMBRE"],$nuevoUsuario["APELLIDOS"],$nuevoUsuario["FECHA_NACIMIENTO"],$nuevoUsuario["DIRECCION"],$nuevoUsuario["EMAIL"],$nuevoUsuario["CP"],$nuevoUsuario["TELEFONO"],$nuevoUsuario["FECHA_NACIMIENTO"],$nuevoUsuario["FECHA_NACIMIENTO"]);
		closeConnection($conexion); 

		if ($excepcion<>"") {
			$_SESSION["excepcion"] = $excepcion;
			$_SESSION["destino"] = "listV2.php";
			Header("Location: excepcion.php");
		} else {
			Header("Location: listV2.php");
		}
	}
	else 
		Header("Location: listV2.php");	

?>

