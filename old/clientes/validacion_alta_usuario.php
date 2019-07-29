<?php
	session_start();

	$include_path = dirname(__FILE__) . '/../';

    require_once($include_path . "persistence/db-connection.php");
    require_once("gestionarClientes.php");


	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["cliente"])) {
		// Recogemos los datos del formulario
		$nuevoUsuario = $_SESSION["cliente"];
		
	}
	else // En caso contrario, vamos al formulario
		Header("Location: detail.php");

	// Validamos el formulario en servidor
	$errores = validarDatosUsuario($nuevoUsuario);
	
	// Si se han detectado errores
	if (count($errores)>0) {
		// Guardo en la sesión los mensajes de error y volvemos al formulario
		$_SESSION["excepcion"] = $errores;
		Header('Location: exception.php');
	} else{
		// Si todo va bien, vamos a la página de acción (inserción del usuario en la base de datos)
		Header('Location: accion_alta_usuario.php');
		
	}
		
///////////////////////////////////////////////////////////
// Validación en servidor del formulario de alta de usuario
///////////////////////////////////////////////////////////
function validarDatosUsuario($nuevoUsuario){
	$errores=array();

	// Validación del Nombre			
	if($nuevoUsuario["NOMBRE"]=="") {
		$errores[] = "<p>El nombre no puede estar vacío</p>";
	}

	//Validacion de los apellidos
	if($nuevoUsuario["APELLIDOS"]==""){
		$errores[] = "<p>Los apellidos no pueden estar vacíos</p>";
	}

	//validacion fecha nacimiento
	if($nuevoUsuario["FECHA_NACIMIENTO"]==""){
		$errores[] = "<p>La fecha de nacimiento no puede estra vacia</p>";
	}

	//Validacion de CP
	if($nuevoUsuario["CP"]==""){
		$errores[] = "<p>El codigo postal no puede estar vacio</p>";
	}

	//Validacion del telefono
	if($nuevoUsuario["TELEFONO"]==""){
		$errores[] = "<p>el telefono no puede estar vacio</p>";
	}
	
	// Validación del email
	if($nuevoUsuario["EMAIL"]==""){ 
		$errores[] = "<p>El email no puede estar vacío</p>";
	}
		
	// Validación de la dirección
	if($nuevoUsuario["DIRECCION"]==""){
		$errores[] = "<p>La dirección no puede estar vacía</p>";	
	}
	
}

?>

