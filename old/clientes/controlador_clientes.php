<?php	
	session_start();
	

	if(isset($_REQUEST["ID_CLIENTE"])){
		$cliente["ID_CLIENTE"] = $_REQUEST["ID_CLIENTE"];
		$cliente["NOMBRE"] = $_REQUEST["NOMBRE"];
		$cliente["APELLIDOS"] = $_REQUEST["APELLIDOS"];
		$cliente["FECHA_NACIMIENTO"] = $_REQUEST["FECHA_NACIMIENTO"];
		$cliente["DIRECCION"] = $_REQUEST["DIRECCION"];
		$cliente["CP"] = $_REQUEST["CP"];
		$cliente["TELEFONO"] = $_REQUEST["TELEFONO"];
		$cliente["EMAIL"] = $_REQUEST["EMAIL"];
		$cliente["FECHA_ALTA"] = $_REQUEST["FECHA_ALTA"];
		$cliente["FECHA_BAJA"] = $_REQUEST["FECHA_BAJA"];
		$cliente["ID_RUTINA"] = $_REQUEST["ID_RUTINA"];
		
		$_SESSION["cliente"] = $cliente;

		if (isset($_REQUEST["editar"])) Header("Location: detail.php"); 
		else if (isset($_REQUEST["guardar_cliente_nuevo"])) Header("Location: validacion_alta_usuario.php");
		else if (isset($_REQUEST["guardar_modificaciones"])) Header("Location: accion_modificar_cliente.php");
		else if (isset($_REQUEST["nuevo"])) Header ("Location: detail.php");
		else /* if (isset($_REQUEST["borrar"]))*/ Header("Location: accion_borrar_cliente.php"); /* lo comentado se puede descomentar o no  esto puede estar o no, ya que es la que salta si no salen las de arriba*/ 

	// esto es para cuando se le da al boton de cliente nuevo, como no tiene id  ni nada no entraria arriba
	} else if (isset($_REQUEST["nuevo"])){ 
		 Header("Location: detail.php");
	} else {	
		Header ("Location: listV2.php");
	}	
	

?>
