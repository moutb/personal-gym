<?php

  interface Ejercicio {

    function getId();

    function getNombre();
    function setNombre($nombre);

    function getDiaSemana();
    function setDiaSemana($diaSemana);

    function getOrden();
    function setOrden($orden);

    function toArray();
  }

  class EjercicioDB implements Ejercicio {

    private $id;
    private $nombre;
    private $codigo;
    private $diaSemana;
    private $orden;

    function __construct($row) {
      if (isset($row["ID_EJERCICIO"])) {
        $this -> id = intval($row["ID_EJERCICIO"]);
      }
      if (isset($row["NOMBRE"])) {
        $this -> nombre = $row["NOMBRE"];
      }
      if (isset($row["DIA_SEMANA"])) {
        $this -> diaSemana = intval($row["DIA_SEMANA"]);
      }
      if (isset($row["ORDEN"])) {
        $this -> orden = intval($row["ORDEN"]);
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

    function getDiaSemana() {
      return $this-> diaSemana;
    }

    function setDiaSemana($diaSemana) {
      $this-> diaSemana = $diaSemana;
    }

    function getOrden() {
      return $this-> orden;
    }

    function setOrden($orden) {
      $this-> orden = $orden;
    }

    function toArray() {
      $arr = array(
        "id" => $this -> id,
        "nombre" => $this -> nombre,
        "diaSemana" => $this -> diaSemana,
        "orden" => $this -> orden
      );
      return $arr;
    }
  }

?>
