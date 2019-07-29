<?php	
	session_start();	
	

	if(isset($_SESSION["cliente"])) {
		$cliente = $_SESSION["cliente"];
		unset($_SESSION["cliente"]);

		$include_path = dirname(__FILE__) . '/../';

		require_once($include_path . "persistence/db-connection.php");
		require_once("gestionarClientes.php");

		$conexion = createConnection();		
		$excepcion = borrar_cliente($conexion,$cliente["ID_CLIENTE"]);
		closeConnection($conexion);

		if ($excepcion<>"") {
			$_SESSION["excepcion"] = $excepcion;
			$_SESSION["destino"] = "listV2.php";
			Header("Location: excepcion.php");
		} else {
			Header("Location: listV2.php");
		}


	} else {
		Header("Location: listV2.php"); // Se ha tratado de acceder directamente a este PHP
	}
?>
