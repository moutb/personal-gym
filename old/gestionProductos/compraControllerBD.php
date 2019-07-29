<?php
function ultimo_id_compra($conexion){
    $consulta=  "select ID_COMPRA FROM COMPRA where rowid=(select max(rowid) from COMPRA)";
    $stmt=$conexion->query($consulta);
    $stmt->execute();	
    $result = $stmt->fetch();
	return $result["ID_COMPRA"];
}

function insertar_compra($conexion,$idCompra,$descripcion,$total,$fecha) {
	try {
		$stmt=$conexion->prepare('CALL INSERTAR_COMPRA(:ID_COMPRA,:DESCRIPCION,:TOTAL,:FECHA)');
		$stmt->bindParam(':ID_COMPRA',$idCompra);
        $stmt->bindParam(':DESCRIPCION',$descripcion);
        $stmt->bindParam(':TOTAL',$total);
        $stmt->bindParam(':FECHA',$fecha);
		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}
function insertar_producto_por_compra($conexion,$idCompra,$idProducto,$precio,$cantidad){
    
        try {
            $stmt=$conexion->prepare('CALL INSERTAR_PRODUCTO_POR_COMPRA(:ID_COMPRA,:ID_PRODUCTO,:PRECIO,:CANTIDAD)');
            $stmt->bindParam(':ID_COMPRA',$idCompra);
            $stmt->bindParam(':ID_PRODUCTO',$idProducto);
            $stmt->bindParam(':PRECIO',$precio);
            $stmt->bindParam(':CANTIDAD',$cantidad);
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