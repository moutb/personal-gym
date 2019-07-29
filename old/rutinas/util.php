<?php

  function getEjerciciosByDiaSemana($ejercicios = array(), $diaSemana) {
    $result = [];
    foreach($ejercicios as $ejercicio) {
      if (!is_null($ejercicio -> getDiaSemana()) && $ejercicio -> getDiaSemana() == $diaSemana) {
        array_push($result, $ejercicio);
      }
    }
    return $result;
  }

?>
