<?php

  include_once(dirname(__FILE__) . '/../../util/strings.php');
  include_once(dirname(__FILE__) . '/model.php');
  include_once(dirname(__FILE__) . '/../ejercicios/model.php');

  // controlador de rutinas mediante conector de base de datos

  interface RutinasController {

    function createRutina($rutina);

    function updateRutina($rutina);

    function removeRutina($id_rutina);

    function getRutina($id_rutina);

    function countRutinas($filters);

    function listRutinas($first, $size, $filters, $sort);
  }

  class RutinasControllerDB implements RutinasController {

    private $conexion;

    function __construct($conn) {
      $this->conexion = $conn;
    }

    private function getNextId() {
      // retrieve next id
      $statement = $this -> conexion -> prepare("select seq_rutina_id_rutina.nextval from dual");
      $statement -> execute();
      return $statement -> fetchColumn();
    }

    function createRutina($rutina) {
      if (is_null($this -> conexion)) {
        throw new Exception('la variable de conexion no puede ser nula');
      }
      // obtener la siguiente id de la secuencia
      $id = $this -> getNextId();

      $statement = $this -> conexion -> prepare("INSERT INTO RUTINA VALUES (:id, :nombre)");

      $statement -> bindParam(':id', $id);

      $statement -> bindParam(':nombre', $rutina -> getNombre());

      $statement -> execute();

      if (!is_null($rutina -> getEjercicios())) {
        $this -> _updateEjerciciosRutina($id, $rutina -> getEjercicios());
      }

      return $id;
    }

    function updateRutina($rutina) {
      if (is_null($this -> conexion)) {
        throw new Exception('la variable de conexion no puede ser nula');
      }
      $statement = $this -> conexion -> prepare("UPDATE RUTINA SET NOMBRE = :nombre WHERE ID_RUTINA = :id");

      $statement -> bindParam(':id', $rutina -> getId());
      $statement -> bindParam(':nombre', $rutina -> getNombre());

      $statement -> execute();

      if (!is_null($rutina -> getEjercicios())) {
        $this -> _updateEjerciciosRutina($rutina -> getId(), $rutina -> getEjercicios());
      }
    }

    function removeRutina($id_rutina) {
      $sql = "DELETE FROM EJERCICIO_X_RUTINA exr WHERE exr.ID_RUTINA = :id_rutina";
      $statement = $this -> conexion -> prepare($sql);
      $statement -> bindParam(":id_rutina", $id_rutina);
      $statement -> execute();

      $sql = 'DELETE FROM RUTINA rut WHERE rut.id_rutina = :id';
      $statement = $this -> conexion -> prepare($sql);
      $statement -> bindParam(":id", $id_rutina);
      $statement -> execute();
    }

    function getRutina($id_rutina) {
      if (is_null($this -> conexion)) {
        throw new Exception('la variable de conexion no puede ser nula');
      }

      if (!is_numeric($id_rutina)) {
        throw new Exception('el parámetro id es inválido');
      }

      $sql = 'SELECT rut.* FROM RUTINA rut WHERE rut.id_rutina = :id';

      $statement = $this -> conexion -> prepare($sql);

      $statement -> bindParam(":id", $id_rutina);

      $statement -> execute();

      $map_fn = function($row) {
        if (!is_null($row["ID_RUTINA"])) {
          $row["EJERCICIOS"] = $this -> _fetchEjerciciosRutina($row["ID_RUTINA"]);
        }
        return new RutinaDB($row);
      };

      $result = array_map($map_fn, $statement -> fetchAll());

      if (sizeof($result) === 0) {
        throw new Exception('no existe ninguna rutina con id '. $id_rutina);
      }

      return $result[0];
    }

    function countRutinas($filters) {
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

    function listRutinas($first = 0, $size = 10, $filters = array(), $sort = array()) {
      if (is_null($this -> conexion)) {
        throw new Exception('la variable de conexion no puede ser nula');
      }

      $sql = $this -> _filterSQL("SELECT rut.* FROM RUTINA rut", $filters);

      if (sizeof($sort) > 0 && !is_null($sort["column"])) {
        $asc = is_null($sort["asc"]) || $sort["asc"];
        $sql .= " ORDER BY " . $sort["column"] . ($asc ? " ASC" : " DESC");
      }

      $sql = "SELECT * FROM ( SELECT ROWNUM RNUM, AUX.* FROM (" . $sql . ") AUX WHERE ROWNUM <= " . ($first + $size) . ") WHERE RNUM > " . $first;

      $statement = $this -> conexion -> prepare($sql);

      foreach ($filters as $key => $value) {
        $statement -> bindParam(":" . $key, $value);
      }

      $statement -> execute();

      $map_fn = function($row) {
        if (!is_null($row["ID_RUTINA"])) {
          $row["EJERCICIOS"] = $this -> _fetchEjerciciosRutina($row["ID_RUTINA"]);
        }
        return new RutinaDB($row);
      };

      return array_map($map_fn, $statement -> fetchAll());
    }

    private function _filterSQL($sql, &$filters) {
      if (sizeof($filters) > 0) {
        $sql .= " WHERE";
        $some_included = false;
        if (!is_null($filters["name"])) {
          $filters["name"] = '%' . $filters["name"] . '%';
          $sql .= " LOWER(rut.nombre) LIKE LOWER(:name)";
          $some_included = true;
        }
      }

      return $sql;
    }

    private function _updateEjerciciosRutina($id_rutina, $ejercicios) {
      $sql = "SELECT exr.ID_EJERCICIO_X_RUTINA, exr.ID_EJERCICIO FROM EJERCICIO_X_RUTINA exr WHERE exr.ID_RUTINA = :id_rutina";
      $statement = $this -> conexion -> prepare($sql);
      $statement -> bindParam(":id_rutina", $id_rutina);
      $statement -> execute();

      $current = $statement -> fetchAll();

      $existingExercisesIds = array_column($current, 'ID_EJERCICIO');

      foreach($ejercicios as $ejercicio) {
        $index = array_search($ejercicio -> getId(), $existingExercisesIds);
        if ($index !== false) {
          $this -> _updateEjercicioRutina($id_rutina, $ejercicio);
        } else {
          $this -> _createEjercicioRutina($id_rutina, $ejercicio);
        }
      }

      $persistedExercises = array_column(array_map(function($ejercicio) {
        return $ejercicio -> toArray();
      }, $ejercicios), "id");
      foreach($existingExercisesIds as $id) {
        if (array_search($id, $persistedExercises) === false) {
          $this -> _deleteEjercicioRutina($id_rutina, $id_ejercicio);
        }
      }

    }

    private function _createEjercicioRutina($id_rutina, $ejercicio) {
      $statement = $this -> conexion -> prepare("select seq_rutina_id_rutina.nextval from dual");
      $statement -> execute();
      $id = $statement -> fetchColumn();
      $sql = "INSERT INTO EJERCICIO_X_RUTINA VALUES (:id, :id_rutina, :id_ejercicio, :dia_semana, :orden)";
      $statement = $this -> conexion -> prepare($sql);
      $statement -> bindParam(":id", $id);
      $statement -> bindParam(":id_rutina", $id_rutina);
      $statement -> bindParam(":id_ejercicio", $ejercicio -> getId());
      $statement -> bindParam(":dia_semana", $ejercicio -> getDiaSemana());
      $statement -> bindParam(":orden", $ejercicio -> getOrden());
      $statement -> execute();
    }

    private function _updateEjercicioRutina($id_rutina, $ejercicio) {
      $sql = "UPDATE EJERCICIO_X_RUTINA SET DIA_SEMANA = :dia_semana, ORDEN = :orden WHERE ID_RUTINA = :id_rutina AND ID_EJERCICIO = :id_ejercicio";
      $statement = $this -> conexion -> prepare($sql);
      $statement -> bindParam(":id_rutina", $id_rutina);
      $statement -> bindParam(":id_ejercicio", $ejercicio -> getId());
      $statement -> bindParam(":dia_semana", $ejercicio -> getDiaSemana());
      $statement -> bindParam(":orden", $ejercicio -> getOrden());
      $statement -> execute();
    }

    private function _deleteEjercicioRutina($id_rutina, $id_ejercicio) {
      $sql = "DELETE FROM EJERCICIO_X_RUTINA exr WHERE exr.ID_RUTINA = :id_rutina AND exr.ID_EJERCICIO = :id_ejercicio";
      $statement = $this -> conexion -> prepare($sql);
      $statement -> bindParam(":id_rutina", $id_rutina);
      $statement -> bindParam(":id_ejercicio", $id_ejercicio);
      $statement -> execute();
    }

    private function _fetchEjerciciosRutina($id_rutina) {
      $sql = "SELECT exr.ID_EJERCICIO_X_RUTINA, exr.DIA_SEMANA, exr.ORDEN, ej.* FROM EJERCICIO_X_RUTINA exr INNER JOIN EJERCICIO ej ON exr.ID_EJERCICIO = ej.ID_EJERCICIO WHERE exr.ID_RUTINA = :id_rutina ORDER BY exr.DIA_SEMANA, exr.ORDEN";
      $statement = $this -> conexion -> prepare($sql);
      $statement -> bindParam(":id_rutina", $id_rutina);
      $statement -> execute();

      $map_fn = function($row) {
        return new EjercicioDB($row);;
      };

      return array_map($map_fn, $statement -> fetchAll());
    }

  }

?>
