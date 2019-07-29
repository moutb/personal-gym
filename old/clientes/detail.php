<?php
    session_start();

    $include_path = dirname(__FILE__) . '/../';

    require_once($include_path . "persistence/db-connection.php");
    require_once("gestionarClientes.php");
    require_once("paginacion_consulta.php");


    if (isset($_SESSION["cliente"])){
	      	$cliente = $_SESSION["cliente"];
     //   unset($_SESSION["cliente"]);
    }

    // Si hay errores de validación, hay que mostrarlos y marcar los campos (El estilo viene dado y ya se explicará)
	if (isset($_SESSION["errores"])){
		$errores = $_SESSION["errores"];
		unset($_SESSION["errores"]);
	}

?>


<!DOCTYPE html>
<html>
  <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Datos de cliente</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link href='https://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/main.css">
        <!-- Hay que indicar el fichero externo de estilos -->
        <?php $include_path = dirname(__FILE__) . '/../'; ?>
        <!-- Aqui se mete la cabecera de la pagina -->
        <?php include_once($include_path . 'parts/common_head.php') ?>
<style>
  main,P {
	border-style: dotted;
  width: 50%;
  padding: 50px;
	width: 33.33 % auto;
  margin: 0 auto;
	text:center;

}
h1{
	text-align:center;
	text-shadow: 3px 2px red;
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

<?php
		// Mostrar los erroes de validación (Si los hay)
		if (isset($errores) && count($errores)>0) {
	    	echo "<div id=\"div_errores\" class=\"error\">";
			echo "<h4> Errores en el formulario:</h4>";
    		foreach($errores as $error){
    			echo $error;
			}
    		echo "</div>";
  		}
	?>


<main>

       <?php //print_r ($_SESSION["cliente"])  // esta funcion nos permite ver todo el contedido del array $_SESSION["cliente"]?>

      <form method="post" action="controlador_clientes.php" novalidate>

        <input type="hidden" id="ID_CLIENTE" name="ID_CLIENTE" value="<?php echo $cliente["ID_CLIENTE"]?>">

        <h1>Informacion de cliente</h1>

        <fieldset class="infoBasica">
          <legend><span class="number"></span> Informacion basica </legend>

          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="NOMBRE" placeholder="Introduzca el nombre" value="<?php if(isset($cliente)) {
              echo $cliente["NOMBRE"];
            } ?>" required>
            <div class="invalid-feedback">
              Por favor, elija un nombre.
            </div>
          </div>

          <div class="row">
            <div class="col col-8">
              <div class="form-group">
                <label for="apellidos">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="APELLIDOS" placeholder="Introduzca los apellidos" value="<?php if(isset($cliente)) {
                  echo $cliente["APELLIDOS"];
                } ?>">
              </div>
            </div>

            <div class="col col-4">
              <div class="form-group">
                <label for="apellidos">Fecha de nacimiento</label>
                <input type="text" class="form-control" id="apellidos" name="FECHA_NACIMIENTO" placeholder="dd/MM/yyyy" value="<?php if(isset($cliente)) {
                  echo $cliente["FECHA_NACIMIENTO"];
                } ?>" required>
                <div class="invalid-feedback">
                  Por favor, introduzca la fecha de nacimiento.
                </div>
              </div>
            </div>
          </div>

        </fieldset>

        <fieldset class="infoBasica">
        <legend><span class="number"></span> Datos extras</legend>

          <div class="form-group">
            <label for="direccion">Direccion</label>
            <input type="text" class="form-control" id="direccion" name="DIRECCION" value="<?php if(isset($cliente)) {
              echo $cliente["DIRECCION"];
            } ?>">
          </div>

          <div class="row">
            <div class="col col-6">
              <div class="form-group">
                <label for="codigo-postal">Código Postal</label>
                <input type="text" class="form-control" id="codigo-postal" name="CP" value="<?php if(isset($cliente)) {
                  echo $cliente["CP"];
                } ?>">
              </div>
            </div>

            <div class="col col-6">
              <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="TELEFONO" value="<?php if(isset($cliente)) {
                  echo $cliente["TELEFONO"];
                } ?>">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="EMAIL" value="<?php if(isset($cliente)) {
              echo $cliente["EMAIL"];
            } ?>">
          </div>

        </fieldset>

       <?php if (isset($_SESSION["cliente"])){ ?>
          <button id="guardar_modificaciones" class="btn red-bg white-color" name="guardar_modificaciones"type="submit">Guardar</button>
       <?php } else { ?>
          <button id="guardar_cliente_nuevo" class="btn red-bg white-color" name="guardar_cliente_nuevo"type="submit">Insertar cliente</button>
          <?php }  ?>

      </form>
</main>

      <script type="text/javascript">
        (function() {
          "use strict";

          let $form = $('form');

          $form.on('submit', function(evt) {
            let form = $form[0];
            $form.addClass('was-validated');
            let invalid = form.checkValidity() === false;
            if (invalid) {
              evt.preventDefault();
              evt.stopPropagation();
            }
            return !invalid;
          });
        })();
      </script>
    </body>
</html>
