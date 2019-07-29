<?php	
	session_start();	
	
	if (isset($_SESSION["producto"])) {
		$producto = $_SESSION["producto"];
		unset($_SESSION["producto"]);
		
		require_once(dirname(__FILE__) . '/../persistence/db-connection.php');
		require_once("productControllerBD.php");
		
		$conexion = createConnection();		
		$excepcion = modificar_producto($conexion,$producto["ID_PRODUCTO"],$producto["NOMBRE"],$producto["STOCK"]);
		closeConnection($conexion);
			
		if ($excepcion<>"") {
			$_SESSION["excepcion"] = $excepcion;
			$_SESSION["destino"] = "consultaProductos.php";
			Header("Location: excepcion.php");
		}
		else
			Header("Location: consultaProductos.php");
	} 
	else Header("Location: consultaProductos.php"); // Se ha tratado de acceder directamente a este PHP
?>
