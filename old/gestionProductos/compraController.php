<?php	
	session_start();
	
	if (isset($_REQUEST["DESCRIPCION"]) ){
		$compra["DESCRIPCION"] = $_REQUEST["DESCRIPCION"];
		$compra["FECHA"] = $_REQUEST["FECHA"];
        $compra["TOTAL"] = $_REQUEST["TOTAL"];
        $compra["PRODUCTOS"] = $_REQUEST["PRODUCTO"];
		
		$_SESSION["COMPRA"] = $compra;

		$errores = validarDatosCompra($compra);
		if (count($errores)>0) {
			// Guardo en la sesión los mensajes de error y volvemos al formulario
			$_SESSION["errores"] = $errores;
			Header('Location: altaCompra.php');
		}
		else{
			Header("Location: accion_insertar_compra.php");
		}
	}
	else 
		Header("Location: altaCompra.php");

	///////////////////////////////////////////////////////////
	// Validación en servidor del formulario de compra
	///////////////////////////////////////////////////////////
	function validarDatosCompra($compra){

		// Validación del Nombre			
		if($compra["DESCRIPCION"]=="") {
			$errores[] = "<p>La descripcion de la compra no puede estar vacía</p>";
		}
		//Validacion Del Stock
		if($compra["TOTAL"]=="") {
			$errores[] = "<p>El Total no puede estar vacío</p>";
		}else if (($compra["TOTAL"] <= 0)){
			$errores[] = "<p>El total no puede ser negativo o nulo</p>";
		}
		if($compra["DESCRIPCION"]=="") {
			$errores[] = "<p>La descripcion de la compra no puede estar vacía</p>";
		}

		return $errores;
	}		
?>