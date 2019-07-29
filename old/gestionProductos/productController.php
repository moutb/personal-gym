<?php	
	session_start();
	
	if (isset($_REQUEST["ID_PRODUCTO"]) || isset($_REQUEST["insertar_producto"])){
		$producto["ID_PRODUCTO"] = $_REQUEST["ID_PRODUCTO"];
		$producto["NOMBRE"] = $_REQUEST["NOMBRE"];
		$producto["STOCK"] = $_REQUEST["STOCK"];
	
		$_SESSION["producto"] = $producto;
		
		$errores = validarDatosProducto($producto);
		if (count($errores)>0) {
			// Guardo en la sesión los mensajes de error y volvemos al formulario
			$_SESSION["errores"] = $errores;
			Header('Location: consultaProductos.php');
		}else{
			if (isset($_REQUEST["editar"])) Header("Location: consultaProductos.php"); 
			else if (isset($_REQUEST["grabar"])) Header("Location: accion_modificar_producto.php");
			else if (isset($_REQUEST["insertar_producto"])) Header("Location: accion_insertar_producto.php");
			else /* if (isset($_REQUEST["borrar"])) */ Header("Location: accion_borrar_producto.php"); 
		}
	}	
	else 
		Header("Location: consultaProductos.php");

	///////////////////////////////////////////////////////////
	// Validación en servidor del formulario Nuevo Producto y editar
	///////////////////////////////////////////////////////////
	function validarDatosProducto($producto){

		// Validación del Nombre			
		if($producto["NOMBRE"]=="") 
			$errores[] = "<p>El nombre del producto no puede estar vacío</p>";

		//Validacion Del Stock
		if($producto["STOCK"]=="") 
			$errores[] = "<p>El Stock no puede estar vacío</p>";
		else if (($producto["STOCK"] <= 0))
			$errores[] = "<p>El Stock no puede ser negativo o nulo</p>";
	
		return $errores;
	}
?>
