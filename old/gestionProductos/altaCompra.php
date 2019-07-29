<?php
	session_start();

	require_once(dirname(__FILE__) . '/../persistence/db-connection.php');
  require_once("productControllerBD.php");
  
  $conexion = createConnection();

	if (isset($_SESSION["COMPRA"])){
		$compra = $_SESSION["COMPRA"];
		unset($_SESSION["COMPRA"]);
	}
  if(isset($_SESSION["errores"])){
		$errores = $_SESSION["errores"];
		
		unset($_SESSION["errores"]);
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>

  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<!--HEADER-->
  <?php include_once(dirname(__FILE__) . '/../parts/common_head.php'); ?>
  <title>Alta compra</title>
  <script src="../js/validacion.js"  type="text/javascript"></script>
</head>

<body>
  
	<script>
		// Inicialización de elementos y eventos cuando el documento se carga completamente
		$(document).ready(function() {
      var total =0;
			$("#añadirProducto").on("click",function(){
        //Array de errores para la valicacion
        let errores = [];
        let nombreProducto= $("#productoSelect option:selected").attr('label');
		    let idProducto = $("#productoSelect option:selected").val();
        errores.push(validateNoEmpty('producto', idProducto));
        let precio = $("#productoPrecio").val();
        errores.push(validateNoEmpty('precio', precio));
        errores.push(validateNoNegative('precio',precio));
        let cantidad= $("#productoCantidad").val();
        errores.push(validateNoEmpty('cantidad',cantidad));
        errores.push(validateNoNegative('cantidad',cantidad));
        
        // mensajes vacios
        errores = errores.filter(error => error !== '');

        if(errores.length >0){
          alert(errores.join("\n"));
        }else{
        //Añadir prodcuto a la tabla
          
          let inputFila = "<input type='hidden' name='PRODUCTO[]' value='"+idProducto+";"+cantidad+";"+precio+"'/>";
          let filaTabla ="<tr><td>"+ 
            nombreProducto+"</td><td class='cantidadFila'>" + 
            cantidad + "</td><td class='precioFila'>" +
            precio +"€ </td><td><button type='button' class='btn borrarFila'> <i class='far fa-trash-alt' alt='Borrar Producto'></i></button></td>"+inputFila+"</tr>" ;
          $("#tablaProductos" ).append(filaTabla);
          
          //Actualiza total
          total += cantidad*precio; 
          $("#TOTAL").val(total);
        }
      });

      $("#tablaProductos tbody").on("click",".borrarFila",function(){
        let precio= parseInt($(this).closest('tr').find('td:eq(1)').text());
        let cantidad = parseInt( $(this).closest('tr').find('td:eq(2)').text());    
        total -= precio*cantidad;
        $("#TOTAL").val(total);
        $(this).closest('tr').remove();

      });

     
    $('#form').submit(function(e) {
      e.preventDefault();
      $("#DESCRIPCION, #TOTAL,#FECHA").each(function(){
          if($.trim(this.value) == ""){
              alert('you did not fill out one of the fields');
          } else {
              // Submit 
          }
      })
    })
  
		});	

	</script>
    
    <?php include_once(dirname(__FILE__) . '/../parts/navbar.php');?>
		<!--DIV PARA LA BARRA LATERAL-->
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block sidebar black-bg white-color">
              <? /* menu lateral */ ?>
              <?php include_once(dirname(__FILE__) . '/../parts/sidebar.php'); ?>
            </nav>
        </div>
    
	    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <p class="display-4 text-center" background="light"> Nueva compra </p>

    <?php 
    // Mostrar los errores de validación (Si los hay)
	  	if (isset($errores) && count($errores)>0) { 
	    	echo "<div id=\"div_errores\" class=\"error\">";
			  echo "<h4> Errores:</h4>";
    		foreach($errores as $error) echo $error; 
    		echo "</div>";
  		}
	  ?>
    <form type="post" action="compraController.php">
        <!-- Input Descripcion -->
        <div class="form-row mb-3">
          <div class="col-9">
            <div class="descripcion input-group mb-3">
              <div class="submit-descripcion input-group-prepend">
                <span class="input-group-text">Descripcion</span>
              </div>
              <textarea id="DESCRIPCION" class="form-control" rows="3"
                  name="DESCRIPCION" type="text" placeholder="Descripcion de la compra"
                  value="<?php echo $compra["DESCRIPCION"]; ?>" required>
              </textarea>
            </div>
          </div>

          <div class="col"> 
           <div class= "container">
              <div class="row">
                <div class="fecha input-group mb-3">
                  <div class="submit-fecha input-group-prepend">
                    <span class="input-group-text">Fecha</span>
                  </div>
                  <input id="FECHA" class="form-control" rows="3"
                      name="FECHA" type="date"
                      value="<?php echo $compra["FECHA"]; ?>" required>
                  </input>
                </div>
              </div>

              <div class="row">
                <div class="total input-group mb-3">
                  <div class="submit-total input-group-prepend">
                    <span class="input-group-text">Total</span>
                  </div>
                  <input id="TOTAL" class="form-control" rows="3"
                      name="TOTAL" 
                      value="" type="number" readonly>
                  </input>
                </div>
              </div>
            
            </div>
          </div>
        </div>
        <div class="form-row justify-content-center mb-3">
            <button id="AÑADIRCOMPRA" type="submit" class="btn justify-content-center btn-link red-bg white-color white-color-hover ">Finalizar Compra</button>
        </div>

          <!-- Input ElegirProducto -->
          <div class="form-row mb-3">
            <div class="col">
              <div class="input-group ">
                <div class="input-group-prepend">
                  <label class="input-group-text" for="inputGroupSelect01">Producto</label>
                </div>
                <select id="productoSelect" class="custom-select" >
                  <option selected value="">Elegir...</option>
                  <?php
			  		        $productos = listarProductos($conexion);  
			  		         foreach($productos as $producto) {
			  		        	echo "<option label='".$producto['NOMBRE']."' value='".$producto['ID_PRODUCTO']."'>";
				          	}
			          	?>
                </select>
              </div>
            </div>
            <!-- Input Cantidad -->
            <div class="col">
              <input id="productoCantidad" type="number" class="form-control" placeholder="Cantidad" required>
            </div>
            <!-- Input precio -->
            <div class="col">
              <div class="input-group-append ">
              <input id="productoPrecio" type="text" class="form-control" placeholder="Precio" required>
                <span class="input-group-text ">€</span>
              </div>
            </div>
            <button id="añadirProducto" type="button" class="btn btn-primary">Añadir producto</button>
          </div>
          
        
        <!-- Tabla de productos dinamica -->
        <table id="tablaProductos" class="table table-hover">
            <thead>
              <tr>
                <th scope="col">Producto</th>
                <th scope="col">Cantidad</th> 
                <th scope="col">Precio</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                
                <!--<input type="hidden" name="producto" value="1;2;15" />
                 $_POST['producto'] = ['1;2;15', '2:1:30']-->
                
              </tr>
            </tbody>
        </table>
      </form>
    </main>


    </div>

  <?php	
    require_once(dirname(__FILE__) . '/../parts/common_body.php');
    
		closeConnection($conexion);

	
  ?>
</body>