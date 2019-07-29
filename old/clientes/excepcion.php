<?php
	session_start();



	$excepcion = $_SESSION["excepcion"];
	unset($_SESSION["excepcion"]);

	if (isset ($_SESSION["destino"])) {
		$destino = $_SESSION["destino"];
		unset($_SESSION["destino"]);
	} else
		$destino = "";
?>

<!DOCTYPE html>
<html lang="es">
<head>

<?php $include_path = dirname(__FILE__) . '/../'; ?>
  <!-- Aqui se mete la cabecera de la pagina -->
  <?php include_once($include_path . 'parts/common_head.php') ?>

  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="css/biblio.css" />
  <title>Gestión de clientes: ¡Se ha producido un problema!</title>

  <style>
  main,h1 {
	border-style: dotted;
 	width: 50%;
  	padding: 50px;
	width: 33.33 % auto;
  	margin: 0 auto;
}
  </style>
</head>
<body>

	<!--   barra de navegacion superior -->
<?php include_once($include_path . 'parts/navbar.php') ?>

<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block sidebar black-bg white-color">

       <!-- menu lateral   -->
      <?php include_once($include_path . 'parts/sidebar.php') ?>

    </nav>
    </div>
</div>

	<main>

	<div>
		<h2>Ups!</h2>
		<?php if ($destino<>"") { ?>
		<p>Ocurrió un problema durante el procesado de los datos. Pulse <a href="<?php echo $destino ?>">aquí</a> para volver a la página principal.</p>
		<?php } else { ?>
		<p>Ocurrió un problema para acceder a la base de datos. </p>
		<?php } ?>
	</div>


	<div class='excepcion'>
		<?php echo "Información relativa al problema: $excepcion;" ?>
	</div>
</main>


</body>
</html>
