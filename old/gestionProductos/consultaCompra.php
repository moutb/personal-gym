<?php
	session_start();

	require_once(dirname(__FILE__) . '/../persistence/db-connection.php');
	require_once("compraControllerBD.php");
	
	require_once(dirname(__FILE__) . '/../util/paginacion_consulta.php');

	if (isset($_SESSION["compra"])){
		$compra = $_SESSION["compra"];
		unset($_SESSION["compra"]);
	}
	if(isset($_SESSION["errores"])){
		$errores = $_SESSION["errores"];
		
		unset($_SESSION["errores"]);
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
	$query = "SELECT * FROM COMPRA";

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<!--HEADER-->
  <?php include_once(dirname(__FILE__) . '/../parts/common_head.php'); ?>
  <title>Gestión de compras:  Lista de compras</title>
	<link rel="stylesheet" type="text/css" href="../css/estiloTabla.css" />

</head>

<body>
	<!--NavBar es la barra superior-->
	<?php include_once(dirname(__FILE__) . '/../parts/navbar.php');?>

  <div class="container-fluid">
		<!--DIV PARA LA BARRA LATERAL-->
    <div class="row">
      <nav class="col-md-2 d-none d-md-block sidebar black-bg white-color">

        <? /* menu lateral */ ?>
        <?php include_once(dirname(__FILE__) . '/../parts/sidebar.php'); ?>

      </nav>
    </div>
    
		<main role="main" id="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
	
		<p class="display-4 text-center" background="light"> Lista de  compras </p>				
		<div id="NUEVOcompra" class="container-fluid">
      <div class="row mb-4 ">
				<div class="col col-2 mr-8">
         
					<a href="altaCompra.php" class="btn btn-link red-bg white-color white-color-hover">Nueva Compra</a>
     		</div>

				<div class="col col-md-auto mr-8">
					<form id="form_nuevo_compra" method="post" action="productController.php">

					</form>
				</div>
			</div>
    </div>
		
	<?php 
		// Mostrar los errores de validación (Si los hay)
		if (isset($errores) && count($errores)>0) { 
	    	echo "<div id=\"div_errores\" class=\"error\">";
			echo "<h4> Errores:</h4>";
    		foreach($errores as $error) echo $error; 
    		echo "</div>";
  		}
	?>
	<table id="tablaProductos" class="table table-hover table-striped">
    <thead>
      <tr>
        <th scope="col">Ver Compra</th>
        <th scope="col">Descripcion</th> 
        <th scope="col">Fecha</th>
        <th scope="col">Total</th>
       </tr>
    </thead>
    <tbody>
	<?php //Creacion de cada compra a partir de la consulta 
		foreach($filas as $fila) {
	?>
	<div class="compra container-fluid " > 
		<form method="post" action="">

			<tr>
				<td class="ver_compra_tabla">	
					<button id="ver_compra" name="ver" type="submit" class="ver_compra ">
						<i class="fas fa-bars"alt="Ver Compra"></i>
					</button>							
				</td>
					<!--DATOS DE compra -->	
					<input id="ID_COMPRA" name="ID_COMPRA" type="hidden" value="<?php echo $fila["ID_COMPRA"]; ?>"/>
					<!-- mostrando compra -->
				<td>
					<input id="DESCRIPCION" name="DESCRIPCION" type="hidden" value="<?php echo $fila["DESCRIPCION"]; ?>"/>
					<span class="DESCRIPCION"><?php echo $fila["DESCRIPCION"]; ?></span>
				</td>
				<td>
					<input id="FECHA" name="FECHA" type="hidden" value="<?php echo $fila["FECHA"]; ?>"/>
					<span class="FECHA"><?php echo $fila["FECHA"]?></span>
				</td>
				<td>
		 			<input id="TOTAL" name="TOTAL" type="hidden" value="<?php echo $fila["TOTAL"]; ?>"/>
						<span class="TOTAL"><?php echo $fila["TOTAL"]?></span>
				</td>	
								
			</tr>
		</form>
	</div>
	<?php } //CIERRA EL FOREACH?>

	       <!--NAVEGACION ENLACES PAGINACION-->
		</tbody>
	</table>		
		<div class="row justify-content-center">
				<nav aria-label="Page navigation example">
					<ul class="pagination">
						<?php
							for( $pagina = 1; $pagina <= $total_paginas; $pagina++ )
								if ( $pagina == $pagina_seleccionada) { 	?>
									<li class="page-item"><a class="page-link"><?php echo $pagina; ?></a></li>
						<?php }	else { ?>
									<li class="page-item"><a class="page-link" href="consultacompra.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a></li>
						<?php } ?>
					</ul>
				</nav>

			<form  method="get" action="consultacompra.php">
				<input id="PAG_NUM" name="PAG_NUM" type="hidden" value="<?php echo $pagina_seleccionada?>"/>
					Mostrando
				<input id="PAG_TAM" name="PAG_TAM" type="number"
					min="1" max="<?php echo $total_registros;?>"
					value="<?php echo $pag_tam?>" autofocus="autofocus" />
				entradas de <?php echo $total_registros?>
				<input type="submit" value="Cambiar">
			</form>
		</div>

	</main>
	</div>
<?php
	
    require_once(dirname(__FILE__) . '/../parts/common_body.php');
?>
</body>
</html>