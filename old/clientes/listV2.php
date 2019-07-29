<?php
    session_start();

    $include_path = dirname(__FILE__) . '/../';

    require_once($include_path . "persistence/db-connection.php");
    require_once("gestionarClientes.php");
    require_once("paginacion_consulta.php");

    if (isset($_SESSION["cliente"])){
					$libro = $_SESSION["cliente"];
				unset($_SESSION["cliente"]);
	}

	// ¿Venimos simplemente de cambiar página o de haber seleccionado un registro ?
	// ¿Hay una sesión activa?
	if (isset($_SESSION["paginacion"])) $paginacion = $_SESSION["paginacion"]; 
	$pagina_seleccionada = isset($_GET["PAG_NUM"])? (int)$_GET["PAG_NUM"]:
												(isset($paginacion)? (int)$paginacion["PAG_NUM"]: 1);
	$pag_tam = isset($_GET["PAG_TAM"])? (int)$_GET["PAG_TAM"]:
										(isset($paginacion)? (int)$paginacion["PAG_TAM"]: 5);
	if ($pagina_seleccionada < 1) $pagina_seleccionada = 1;
	if ($pag_tam < 1) $pag_tam = 5;
		
	// Antes de seguir, borramos las variables de sección para no confundirnos más adelante
	unset($_SESSION["paginacion"]);

	$conexion = createConnection();
	
	// La consulta que ha de paginarse
	$query = 'SELECT * FROM CLIENTE ' ;
		
	
	// Se comprueba que el tamaño de página, página seleccionada y total de registros son conformes.
	// En caso de que no, se asume el tamaño de página propuesto, pero desde la página 1
	$total_registros = total_consulta($conexion,$query);
	$total_paginas = (int) ($total_registros / $pag_tam);
	if ($total_registros % $pag_tam > 0) $total_paginas++; 
	if ($pagina_seleccionada > $total_paginas) $pagina_seleccionada = $total_paginas;
	
	// Generamos los valores de sesión para página e intervalo para volver a ella después de una operación
	$paginacion["PAG_NUM"] = $pagina_seleccionada;
	$paginacion["PAG_TAM"] = $pag_tam;
	$_SESSION["paginacion"] = $paginacion;
	
	$filas = consulta_paginada($conexion,$query,$pagina_seleccionada,$pag_tam);
	
    closeConnection($conexion);
    
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <!-- Hay que indicar el fichero externo de estilos -->
  <title>Lista de clientes</title>
  <?php $include_path = dirname(__FILE__) . '/../'; ?>
  <!-- Aqui se mete la cabecera de la pagina -->
  <?php include_once($include_path . 'parts/common_head.php') ?> 

<?php include_once($include_path . 'persistence/db-connection.php') ?>
<?php include_once($include_path . 'persistence/rutinas/controller.php') ?>

<link rel="stylesheet" href="/personal-gym/css/clientes.css"> 

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

   <!-- Aqui iran los clientes mostrados-->
	 <h1>CLIENTES </h1>
   <main value=>
	 <nav>
		<div id="enlaces">
			<?php
				for( $pagina = 1; $pagina <= $total_paginas; $pagina++ ) 
					if ( $pagina == $pagina_seleccionada) { 	?>
						<span class="current"><?php echo $pagina; ?></span>
			<?php }	else { ?>			
						<a href="listV2.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>
			<?php } ?>			
		</div>
		
		<form method="get" action="listV2.php">
			<input id="PAG_NUM" name="PAG_NUM" type="hidden" value="<?php echo $pagina_seleccionada?>"/>
			Mostrando 
			<input id="PAG_TAM" name="PAG_TAM" type="number" 
				min="1" max="<?php echo $total_registros;?>" 
				value="<?php echo $pag_tam?>" autofocus="autofocus" /> 
			entradas de <?php echo $total_registros?>
			<input type="submit" value="Cambiar">
		</form>
	</nav>	
								<!--Boton de cliente nuevo -->
		<form method="post" action="controlador_clientes.php">
						<div id= "boton_nuevo_cliente" class= "boton_nuevo_cliente">
							<button id="nuevo" name="nuevo" type="submit">
							Cliente nuevo
							</button>
						</div>
			</form>	
			<br><br>			


	<?php
		foreach($filas as $fila) {
	?>
		<!-- Clientes -->
	<article class="cliente">
		<form method="post" action="controlador_clientes.php">
			<div class="fila_cliente">
				<div class="datos_cliente">		
					<input id="ID_CLIENTE" name="ID_CLIENTE"
						type="hidden" value="<?php echo $fila["ID_CLIENTE"]; ?>"/>
					<input id="FECHA_NACIMIENTO" name="FECHA_NACIMIENTO"
						type="hidden" value="<?php echo $fila["FECHA_NACIMIENTO"]; ?>"/>
					<input id="DIRECCION" name="DIRECCION"
						type="hidden" value="<?php echo $fila["DIRECCION"]; ?>"/>
					<input id="CP" name="CP"
						type="hidden" value="<?php echo $fila["CP"]; ?>"/>
					<input id="FECHA_ALTA" name="FECHA_ALTA"
						type="hidden" value="<?php echo $fila["FECHA_ALTA"]; ?>"/>
					<input id="FECHA_BAJA" name="FECHA_BAJA"
						type="hidden" value="<?php echo $fila["FECHA_BAJA"]; ?>"/>
					<input id="ID_RUTINA" name="ID_RUTINA"
						type="hidden" value="<?php echo $fila["ID_RUTINA"]; ?>"/>
					<input id="NOMBRE" name="NOMBRE"
						type="hidden" value="<?php echo $fila["NOMBRE"]; ?>"/>
					<input id="APELLIDOS" name="APELLIDOS"
						type="hidden" value="<?php echo $fila["APELLIDOS"]; ?>"/>
					<input id="CP" name="CP"
						type="hidden" value="<?php echo $fila["CP"]; ?>"/>
					<input id="TELEFONO" name="TELEFONO"
						type="hidden" value="<?php echo $fila["TELEFONO"]; ?>"/>
					<input id="EMAIL" name="EMAIL"
						type="hidden" value="<?php echo $fila["EMAIL"]; ?>"/>	

						
				
						<!-- mostrando NOMBRE, APELLIDOS, TELEFONO EMAIL -->
						<div class="info" id="qwerty"><b>Direccion:</b><?php echo $fila["DIRECCION"]; ?></div>
						<div class="info" id="qwerty"><b>Nombre: </b><em><?php echo $fila["NOMBRE"]; ?></em></div>
						<div class="info" id="qwerty"><b>Telefono: </b><em><?php echo $fila["TELEFONO"]; ?></em></div>
						<div class="info" id="qwerty"><b>Email: </b><em><?php echo $fila["EMAIL"]; ?></em></div>
				</div>


				<!--Botones de edicion y borrado -->
				<div id="botones_fila" class="botones_right">
						<button id="editar" name="editar" type="submit" class="editar_fila">
						<i class="far fa-edit" alt="Editar Producto"></i>
						</button>
					<button id="borrar" name="borrar" type="submit" class="editar_fila">
					<i class="far fa-trash-alt" alt="Borrar Producto"></i>
					</button>
					
				</div>
			</div>
		</form>
	</article>
	<br>

	<?php } ?>
</main>

        <!-- aqui va el cuerpo comun-->
        <?php include_once($include_path . 'parts/common_body.php') ?>  
  </body>
</html>
