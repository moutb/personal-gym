<?php

  interface Rutina {

    function getId();

    function getNombre();
    function setNombre($nombre);

    function getEjercicios();
    function setEjercicios($ejercicios);
  }

  class RutinaDB implements Rutina {

    private $id;
    private $nombre;
    private $ejercicios;

    function __construct($row) {
      if (!is_null($row) && sizeof($row) > 0) {
        if (isset($row["ID_RUTINA"])) {
          $this -> id = $row["ID_RUTINA"];
        }
        if (isset($row["NOMBRE"])) {
          $this -> nombre = $row["NOMBRE"];
        }
        if (isset($row["EJERCICIOS"])) {
          $this -> ejercicios = $row["EJERCICIOS"];
        }
      }
    }

    function getId() {
      return $this-> id;
    }

    function getNombre() {
      return $this-> nombre;
    }

    function setNombre($nombre) {
      $this-> nombre = $nombre;
    }

    function getEjercicios() {
      return $this -> ejercicios;
    }

    function setEjercicios($ejercicios) {
      $this -> ejercicios = $ejercicios;
    }

  }

?>
