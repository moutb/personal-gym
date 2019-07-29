<?php

  interface EjerciciosController {

    function createEjercicio($ejercicio);

    function updateEjercicio($ejercicio);

    function removeEjercicio($id_ejercicio);

    function getEjercicio($id_ejercicio);

    function countEjercicios($filters);

    function listEjercicios($first, $size, $filters, $sort);
  }

  class EjerciciosControllerDB implements EjerciciosController {

    private $conexion;

    function __construct($conn) {
      $this->conexion = $conn;
    }

    private function getNextId() {
      // retrieve next id
      $statement = $this -> conexion -> prepare("select seq_ejercicio_id_ejercicio.nextval from dual");
      $statement -> execute();
      return $statement -> fetchColumn();
    }

    function createEjercicio($ejercicio) {
    /*  if (is_null($this -> conexion)) {
        throw new Exception('la variable de conexion no puede ser nula');
      }
      // obtener la siguiente id de la secuencia
      $id = $this -> getNextId();

      $statement = $this -> conexion -> prepare("INSERT INTO RUTINA VALUES (:id, :nombre)");

      $statement -> bindParam(':id', $id);

      $statement -> bindParam(':name', $birthdate);

      return $statement -> execute();*/
    }

    function updateEjercicio($ejercicio) {

    }

    function removeEjercicio($id_ejercicio) {

    }

    function getEjercicio($id_ejercicio) {
      if (is_null($this -> conexion)) {
        throw new Exception('la variable de conexion no puede ser nula');
      }

      if (!is_numeric($id_ejercicio)) {
        throw new Exception('el parámetro id es inválido');
      }

      $sql = 'SELECT ejer.* FROM EJERCICIO ejer WHERE ejer.id_ejercicio = :id';

      $statement = $this -> conexion -> prepare($sql);

      $statement -> bindParam(":id", $id_ejercicio);

      $statement -> execute();

      $map_fn = function($row) {
        if (!is_null($row["ID_EJERCICIO"])) {
          // $row["EJERCICIOS"] = $this -> _fetchEjerciciosRutina($row["ID_RUTINA"]);
        }
        return new EjercicioDB($row);
      };

      $result = array_map($map_fn, $statement -> fetchAll());

      if (sizeof($result) === 0) {
        throw new Exception('no existe ningún ejercicio con id '. $id_ejercicio);
      }

      return $result[0];
    }

    function countEjercicios($filters) {
      if (is_null($this -> conexion)) {
        throw new Exception('la variable de conexion no puede ser nula');
      }

      $sql = $this -> _filterSQL("SELECT count(*) FROM RUTINA rut", $filters);

      $statement = $this -> conexion -> prepare($sql);

      foreach ($filters as $key => $value) {
        $statement -> bindParam(":" . $key, $value);
      }

      $statement -> execute();
      return $statement -> fetchColumn();
    }

    function listEjercicios($first = 0, $size = 10, $filters = array(), $sort = array()) {
      if (is_null($this -> conexion)) {
        throw new Exception('la variable de conexion no puede ser nula');
      }

      $sql = $this -> _filterSQL("SELECT ejer.* FROM EJERCICIO ejer", $filters);

      if (sizeof($sort) > 0 && !is_null($sort["column"])) {
        $asc = is_null($sort["asc"]) || $sort["asc"];
        $sql .= " ORDER BY ejer." . $sort["column"] . ($asc ? " ASC" : " DESC");
      }

      if (!is_null($first) && !is_null($size)) {
        $sql = "SELECT * FROM ( SELECT ROWNUM RNUM, AUX.* FROM (" . $sql . ") AUX WHERE ROWNUM <= " . ($first + $size) . ") WHERE RNUM > " . $first;
      }

      $statement = $this -> conexion -> prepare($sql);

      if (!is_null($filters)) {
        foreach ($filters as $key => $value) {
          $statement -> bindParam(":" . $key, $value);
        }
      }

      $statement -> execute();

      $map_fn = function($row) {
        if (!is_null($row["ID_EJERCICIO"])) {
          // $row["EJERCICIOS"] = $this -> _fetchEjerciciosRutina($row["ID_RUTINA"]);
        }
        return new EjercicioDB($row);
      };

      return array_map($map_fn, $statement -> fetchAll());
    }

    private function _filterSQL($sql, &$filters) {
      if (sizeof($filters) > 0) {
        $sql .= " WHERE";
        $some_included = false;
        if (!is_null($filters["name"])) {
          $filters["name"] = '%' . $filters["name"] . '%';
          $sql .= " LOWER(ejer.nombre) LIKE LOWER(:name)";
          $some_included = true;
        }
      }

      return $sql;
    }

    private function _fetchSeriesEjercicio($id_ejercicio) {
      /*$sql = "SELECT exr.ID_EJERCICIO_X_RUTINA, exr.DIA_SEMANA, exr.ORDEN, ej.* FROM EJERCICIO_X_RUTINA exr INNER JOIN EJERCICIO ej ON exr.ID_EJERCICIO = ej.ID_EJERCICIO WHERE exr.ID_RUTINA = :id_rutina ORDER BY exr.DIA_SEMANA, exr.ORDEN";
      $statement = $this -> conexion -> prepare($sql);
      $statement -> bindParam(":id_rutina", $id_rutina);
      $statement -> execute();

      $map_fn = function($row) {
        return new EjercicioDB($row);
      };

      return array_map($map_fn, $statement -> fetchAll());*/
    }

  }

?>
