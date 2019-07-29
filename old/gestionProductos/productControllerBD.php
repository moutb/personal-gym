<?php


function consultarTodosProductos($conexion) {
	$consulta = "SELECT * FROM PRODUCTO";
    return $conexion->query($consulta);
}
function insertar_producto($conexion,$idProducto,$Nombre,$Stock) {
	try {
		$stmt=$conexion->prepare('CALL INSERTAR_PRODUCTO(:ID_PRODUCTO,:NOMBRE,:STOCK)');
		$stmt->bindParam(':ID_PRODUCTO',$idProducto);
        $stmt->bindParam(':NOMBRE',$Nombre);
        $stmt->bindParam(':STOCK',$Stock);
		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}
function modificar_producto($conexion,$idProducto,$Nombre,$Stock) {
	try {
		$stmt=$conexion->prepare('CALL MODIFICAR_PRODUCTO(:ID_PRODUCTO,:NOMBRE,:STOCK)');
		$stmt->bindParam(':ID_PRODUCTO',$idProducto);
        $stmt->bindParam(':NOMBRE',$Nombre);
        $stmt->bindParam(':STOCK',$Stock);
		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}
function quitar_producto($conexion,$idProducto){
    
        try {
            $stmt=$conexion->prepare('CALL QUITAR_PRODUCTO(:ID_PRODUCTO)');
            $stmt->bindParam(':ID_PRODUCTO',$idProducto);
            $stmt->execute();
            return "";
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }
    
function listarProductos($conexion){
        try {
            $consulta = "SELECT NOMBRE, ID_PRODUCTO FROM PRODUCTO ORDER BY NOMBRE";
            $stmt=$conexion->prepare($consulta);
            //$stmt->bindParam(':prov',$provincia);	
            $stmt->execute();	
            return $stmt;
        } catch(PDOException $e) {
            return NULL;
        }
    }

?>