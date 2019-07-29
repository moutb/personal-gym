<?php

  $include_path = dirname(__FILE__) . '/../';

  include_once($include_path . 'util/common.php');
  include_once($include_path . 'util/strings.php');
  include_once($include_path . 'util/validation.php');

  function sendError($status, $messages) {
      http_response_code($status);
      header('Content-Type: application/json; charset=UTF-8');
      $error = array("messages" => $messages);
      echo json_encode($error);
  }

  $id = getValue($_POST, 'id', null);
  $name = getValue($_POST, 'name', null);
  $exercises = getValue($_POST, 'exercises', null);

  $errors = array();
  checkString($errors, 'name', $name);

  if (checkJSONArray($errors, 'exercises', $exercises)) {
    $decoded = json_decode($exercises, true);
    foreach($decoded as $exercise) {
      if (!checkPositiveNumber($errors, "exercise id ", $exercise[ "id" ])
          || !checkPositiveNumber($errors, "exercise day of week ", $exercise[ "diaSemana" ])
          || !checkPositiveNumber($errors, "exercise position index", $exercise[ "orden" ])) {
        break;
      }
    }
  }

  if (sizeof($errors) > 0) {
    sendError(400, $errors);
  } else {
    // request data is valid we can persist the new items

    include_once($include_path . 'persistence/db-connection.php');
    include_once($include_path . 'persistence/rutinas/controller.php');

    $connection = createConnection();
    $rutinasController = new RutinasControllerDB($connection);

    // build the rutina to persist
    $rutina = new RutinaDB(array("ID_RUTINA" => $id));
    $rutina -> setNombre($name);

    $map_fn = function($decoded) {
      $ejercicio = new EjercicioDB(array("ID_EJERCICIO" => $decoded["id"]));
      $ejercicio -> setDiaSemana($decoded["diaSemana"]);
      $ejercicio -> setOrden($decoded["orden"]);
      return $ejercicio;
    };
    $ejercicios = array_map($map_fn, json_decode($exercises, true));

    $rutina -> setEjercicios($ejercicios);

    try {
      if (is_null($rutina -> getId())) {
        $rutina = $rutinasController -> createRutina($rutina);
      } else {
        $rutina = $rutinasController -> updateRutina($rutina);
      }

      header('Location: /personal-gym/rutinas/list.php');
    } catch (Exception $e) {
      $_SESSION["exception"] = $e -> getMessage();
      header('Location: /personal-gym/error.php');
    }

  }

?>
