<?php

  function checkString(&$errors, $paramName, $value) {
    $valid = true;
    if (is_null($value)) {
      $valid = false;
      array_push($errors, $paramName . ' parameter is required');
    } else if (!is_string($value)) {
      $valid = false;
      array_push($errors, 'invalid ' . $paramName . ' parameter type');
    } else if (empty(trim($value))) {
      $valid = false;
      array_push($errors, $paramName . ' parameter cant be empty');
    }
    return $valid;
  }

  function checkPositiveNumber(&$errors, $paramName, $value) {
    $valid = true;
    if (is_null($value)) {
      $valid = false;
      array_push($errors, $paramName . ' parameter is required');
    } else if (!is_numeric($value)) {
      $valid = false;
      array_push($errors, 'invalid ' . $paramName . ' parameter type');
    } else if (intval($value) < 0) {
      $valid = false;
      array_push($errors, $paramName . ' must be positive');
    }
    return $valid;
  }

  function checkJSONArray(&$errors, $paramName, $value) {
    $valid = true;
    if (is_null($value)) {
      $valid = false;
      array_push($errors, $paramName . ' parameter is required');
    } else {
      $json = null;
      try {
        $json = json_decode($value);
      } catch (Exception $e) {
        $valid = false;
        array_push($errors, 'invalid ' .$paramName. ' parameter type (not json)');
      }
      if (is_null($json)) {
        $valid = false;
        array_push($errors, 'invalid ' .$paramName. ' parameter type (not json)');
      } else if (!is_array($json)) {
        $valid = false;
        array_push($errors, 'invalid ' . $paramName . ' parameter type (not array)');
      }
    }
    return $valid;
  }

?>
