<?php
	session_start();

	require_once(dirname(__FILE__) . '/../persistence/db-connection.php');
	require_once("productControllerBD.php");
	
	require_once(dirname(__FILE__) . '/../util/paginacion_consulta.php');

	if (isset($_SESSION["producto"])){
		$producto = $_SESSION["producto"];
		unset($_SESSION["producto"]);
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
	$query = "SELECT * FROM PRODUCTO";

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
	<title>Gestión de Productos:  Lista de Productos</title>
	<link rel="stylesheet" type="text/css" href="../css/estiloTabla.css" />
</head>

<body>
	<!--NavBar es la barra superior-->
	<?php include_once(dirname(__FILE__) . '/../parts/navbar.php');?>
	<script>
		// Inicialización de elementos y eventos cuando el documento se carga completamente
		$(document).ready(function() {
      
			$("#BotonNuevoProducto").one("click",function(){
				$('#main').attr({class: 'col-md-9 ml-sm-auto col-lg-10 px-4 bg-light'});
				$('#NUEVOPRODUCTO').attr({class: 'container-fluid justify-content-md-left border border-warning rounded mb-3 bg-white'});
        
				$('<input>').attr({
   				 value: '',
   				 id: 'NOMBRE',
    			 name: 'NOMBRE',
					 placeholder: 'NOMBRE',
					 required: ''
				}).appendTo('#form_nuevo_producto');

				$('<input>').attr({
   				 value: '',
   				 id: 'STOCK',
    			 name: 'STOCK',
					 placeholder: 'STOCK',
					 type: 'number',
					 required: ''
				}).appendTo('#form_nuevo_producto');

				$('#BotonNuevoProducto').attr({
					 form: 'form_nuevo_producto',
   				 id: 'AÑADIRPRODUCTO',
					 name: 'insertar_producto',
					 class: 'btn bg-warning white-color white-color-hover ml-2 mb-3'
				});

				$("#BotonNuevoProductoText").text("Añadir producto");

			});

		});		

		$(document).on('change','input',function(){
			$("#AÑADIRPRODUCTO").on('click', function () {
				$('#AÑADIRPRODUCTO').attr({
					type: 'submit'
				});

			});
		});


	</script>
  <div class="container-fluid">
		<!--DIV PARA LA BARRA LATERAL-->
    <div class="row">
      <nav class="col-md-2 d-none d-md-block sidebar black-bg white-color">

        <? /* menu lateral */ ?>
        <?php include_once(dirname(__FILE__) . '/../parts/sidebar.php'); ?>

      </nav>
    </div>
    
		<main role="main" id="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
	<!--	<div class="container-fluid">
			<form id="productos-searchform" method="get" action="productController.php">
				<? /* barra de busqueda */ ?>
					<div id="productos-searchbar" class="mb-1">
						<div class="form-inline">
							<input id="searchText" type="search" class="form-control mb-3" placeholder="Búsqueda" name="name" value="<?php echo getValue($_REQUEST, 'name', '') ?>">
							<button type="submit" class="btn red-bg white-color white-color-hover ml-2 mb-3">                <i class="fas fa-search"></i>
							</button>
						</div>
					</div>
			</form>
		</div>-->
		<p class="display-4 text-center" background="light"> Lista de Productos </p>	

		<div 	id="NUEVOPRODUCTO" class="container-fluid">
      <div class="row mb-4 ">
				<div class="col col-2 mr-8">
          <button type="button" id="BotonNuevoProducto" class="btn btn-link red-bg white-color white-color-hover">
					<span id="BotonNuevoProductoText">Nuevo Producto</span></button>
     		</div>

				<div class="col col-md-auto mr-8">
					<form id="form_nuevo_producto" method="post" action="productController.php">

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
            <th scope="col"></th>
            <th scope="col">Producto</th> 
            <th scope="col">Stock</th>
          </tr>
				</thead>
				<tbody>
	<?php //Creacion de cada producto a partir de la consulta 
		foreach($filas as $fila) {	
	?>
	<div class="producto container-fluid " > 
		<form method="post" action="productController.php">
			<!--<div class="fila_producto row justify-content-center">-->
		
					<tr>
					 <td class="ver_producto_tabla">
				
				<!--BOTONES DE LA FILA  div id="botones_fila" class=""> -->
								
						<?php if (isset($producto) and ($producto["ID_PRODUCTO"] == $fila["ID_PRODUCTO"])) { ?>
							<button id="grabar" name="grabar" type="submit" class="editar_fila">
								<i class="far fa-save"  alt="Guardar modificación"></i>
								</button>
						<?php } else {?>
								<button id="editar" name="editar" type="submit" class="editar_fila">
									<i class="far fa-edit" alt="Editar Producto"></i>
								</button>
						<?php } ?>
								<button id="borrar" name="borrar" type="submit" class="editar_fila ">
									<i class="far fa-trash-alt" alt="Borrar Producto"></i>
							</button>
					</td>
				<!--</div>-->
		<!--DATOS DE PRODUCTO -->	
							
				<!--<div class="datos_producto col-6 border ml-2" > -->
					<input id="ID_PRODUCTO" name="ID_PRODUCTO"
						type="hidden" value="<?php echo $fila["ID_PRODUCTO"]; ?>"/>
						
				<?php
					if (isset($producto) and ($producto["ID_PRODUCTO"] == $fila["ID_PRODUCTO"])) { ?>
						<!-- Editando nombre -->
					<td>
						<h6><input id="NOMBRE" name="NOMBRE" type="text" required value="<?php echo $fila["NOMBRE"]; ?>"/>	</h6>
					</td>
					<td>
						<h6><input id="STOCK" name="STOCK" type="number" required value="<?php echo $fila["STOCK"]; ?>"/>	</h6>
						<!--	<h4><?php// echo $fila["NOMBRE"] ?></h4> -->
					</td>
				<?php }	else { ?>
						<!-- mostrando título -->

						<input id="NOMBRE" name="NOMBRE" type="hidden" value="<?php echo $fila["NOMBRE"]; ?>"/>
						<input id="STOCK" name="STOCK" type="hidden" value="<?php echo $fila["STOCK"]; ?>"/>
					<td>
						<span class="nombre"><b><?php echo $fila["NOMBRE"]; ?></b></span>
					</td>
					<td>
            <span class="stock"><?php echo $fila["STOCK"]?></span>
					</td>		
				
				<?php } ?>
				<!--</div>-->
				</tr>
				
			<!--</div>-->
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
									<li class="page-item"><a class="page-link" href="consultaProductos.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a></li>
						<?php } ?>
					</ul>
				</nav>
	 		
		

		
			<form  method="get" action="consultaProductos.php">
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