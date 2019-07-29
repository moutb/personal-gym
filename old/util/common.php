<?php

  /**
   * obtener path de la llamada
   * p.e. "/personal-gym/index.php"
   */
  function getRequestPath() {
    // limpiar url con los parametros
    $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);

    return sizeof($uri_parts) > 0 ? $uri_parts[0] : '';
  }

  /**
   * obtener un valor de un array o
   * en su defecto un valor por defecto
   */
  function getValue($arr, $key, $default) {
    if (!empty($arr[$key])) {
      return $arr[$key];
    } else {
      return $default;
    }
  }

  function toQueryParams() {
    $result = '';
    $args = func_get_args();
    foreach ($args as $arg) {
      if (is_array($arg)) {
        if (sizeof($arg) == 0) {
          continue;
        }
        foreach ($arg as $p => $v) {
          $result .= '&' . $p . '=' . $v;
        }
      } else {
        $result .= '&' . $arg;
      }
    }

    return '?' . substr($result, 1);
  }
?>
