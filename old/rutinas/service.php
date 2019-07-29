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

  $errors = array();
  $id = getValue($_REQUEST, 'id', null);
  $action = getValue($_REQUEST, 'action', null);

  checkString($errors, 'action', $action);
  checkPositiveNumber($errors, "id ", $id);

  if (sizeof($errors) > 0) {
    sendError(400, $errors);
  } else {
    // request data is valid we can persist the new items

    include_once($include_path . 'persistence/db-connection.php');
    include_once($include_path . 'persistence/rutinas/controller.php');

    $connection = createConnection();
    $rutinasController = new RutinasControllerDB($connection);

    if ($action === 'delete') {
      $rutinasController -> removeRutina($id);

      header('Location: /personal-gym/rutinas/list.php');
    }
  }

?>
