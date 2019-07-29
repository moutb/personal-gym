<?php

    require_once(dirname(__FILE__) . '/../persistence/db-connection.php');
	require_once("compraControllerBD.php");
    session_start();	
    
	
	if (isset($_SESSION["COMPRA"])) {
		//Traer la variable de sesion y unset
		$compra = $_SESSION["COMPRA"];
		unset($_SESSION["COMPRA"]);

		//Creamos conexion de base de datos
		$conexion = createConnection();	

		//INSERTAR COMPRA
		$fecha = date('d/m/Y', strtotime($compra["FECHA"]));
		$excepcion = insertar_compra($conexion,null,$compra["DESCRIPCION"],$compra["TOTAL"],$fecha);
		closeConnection($conexion);
		$conexion = createConnection();	
		$idCompra = intval(ultimo_id_compra($conexion));
		
		//INSERTAR PRODUCTO_X_COMPRA
		foreach ($compra["PRODUCTOS"] as $producto){
			$trozos = explode(';',$producto);
			
			
			$excepcionCompra = insertar_producto_por_compra($conexion,$idCompra,$trozos[0],$trozos[2],$trozos[1]);
		}
		closeConnection($conexion);
		if ($excepcion<>"" || $excepcionCompra<>"") {
			$_SESSION["excepcion"] = $excepcion;
			$_SESSION["excepcionCompra"] = $excepcionCompra;
			$_SESSION["destino"] = "consultaProductos.php";
			Header("Location: excepcion.php");
		}
		else
			
			Header("Location: consultaCompra.php");
	} 
	else Header("Location: altaCompra.php"); // Se ha tratado de acceder directamente a este PHP
?>
