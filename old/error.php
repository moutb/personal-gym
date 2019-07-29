<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Error</title>

    <?php $include_path = dirname(__FILE__) . '/../'; ?>

    <?php include_once($include_path . 'parts/common_head.php') ?>
  </head>
  <body>
    <h1>Vaya, ha ocurrido un error inesperado</h1>
  <?php
    $excepcion = getValue($_SESSION, 'exception', null);
    unset($_SESSION["excepcion"]);
  ?>

  <p>Pulse <a href="/personal-gym/index.php">aquí</a> para volver a la página principal.</p>

  <?php if (!is_null($exception)) { ?>
  	<div class='excepcion'>
  		<?php echo "Información relativa al problema: $exception;" ?>
  	</div>
  <?php } ?>
  </body>
</html>
