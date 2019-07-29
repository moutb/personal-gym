<?php 
	session_start();
	
	$excepcion = $_SESSION["excepcion"];
	$excepcionCompra = $_SESSION["excepcionCompra"];
	unset($_SESSION["excepcion"]);
	unset($_SESSION["excepcionCompra"]);

	if (isset ($_SESSION["destino"])) {
		$destino = $_SESSION["destino"];
		unset($_SESSION["destino"]);	
	} else 
		$destino = "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="css/biblio.css" />
  <title>Gestión de biblioteca: ¡Se ha producido un problema!</title>
</head>
<body>	
	
<?php include_once(dirname(__FILE__) . '/../parts/common_head.php'); ?>

	<div>
		<h2>Ups!</h2>
		<?php if ($destino<>"") { ?>
		<p>Ocurrió un problema durante el procesado de los datos. Pulse <a href="<?php echo $destino ?>">aquí</a> para volver a la página principal.</p>
		<?php } else { ?>
		<p>Ocurrió un problema para acceder a la base de datos. </p>
		<?php } ?>
	</div>
		
	<div class='excepcion'>	
		<?php echo "Información relativa Compra : $excepcion;" ?>
	</div>

	<div class='excepcionCompra'>	
		<?php echo "Información relativa a ProductoxCompra : $excepcionCompra;" ?>
	</div>

<?php	
	
?>	

</body>
</html>