<?php
  /**
   * Comprobar si una cadena empieza con otra cadena
   */
  function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
  }

  /**
   * Comprobar si una cadena termina con otra cadena
   */
  function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
      return true;
    }
    return (substr($haystack, -$length) === $needle);
   }

   /**
    * crear un string aleatorio
    * funcion de utilidad para crear ids de elementos HTML aleatorias (y asi evitar que se repitan)
    */
   function getRandomString($length = 7) {
     $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
     $charactersLength = strlen($characters);
     $randomString = '';
     for ($i = 0; $i < $length; $i++) {
         $randomString .= $characters[rand(0, $charactersLength - 1)];
     }
     return $randomString;
   }
?>
