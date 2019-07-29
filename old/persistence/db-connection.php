<?php

function createConnection() {
	$host="oci:dbname=localhost/XE;charset=UTF8";
	$usuario="IISSI";
	$password="iissi";

	try {
		$conexion = new PDO($host,$usuario,$password,array(PDO::ATTR_PERSISTENT => true));
	  /* Indicar que se disparen excepciones cuando ocurra un error*/
    $conexion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conexion;
	} catch (PDOException $e) {
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: error.php");
	}
}

function closeConnection($conexion){
	$conexion=null;
}

?>
