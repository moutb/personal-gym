<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Listado de rutinas</title>

    <?php $include_path = dirname(__FILE__) . '/../'; ?>

    <?php include_once($include_path . 'parts/common_head.php') ?>
    <?php include_once($include_path . 'persistence/db-connection.php') ?>
    <?php include_once($include_path . 'persistence/rutinas/controller.php') ?>

    <?php
      $connection = createConnection();
      $rutinasController = new RutinasControllerDB($connection);

      $filters = array();
      if (isset($_REQUEST['name']) && strlen($_REQUEST['name']) > 0) {
        $filters['name'] =  $_REQUEST['name'];
      }

      $page = 0;
      $max_results = 10;
      if (isset($_REQUEST['p']) && is_numeric($_REQUEST['p'])) {
        $page = $_REQUEST['p'] - 1;
        if ($page < 0) {
          $page = 0;
        }
      }
    ?>

    <link rel="stylesheet" href="/personal-gym/css/rutinas.css">
  </head>
  <body>

    <? /* barra de navegacion superior */ ?>
    <?php include_once($include_path . 'parts/navbar.php') ?>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar black-bg white-color">

          <? /* menu lateral */ ?>
          <?php include_once($include_path . 'parts/sidebar.php') ?>

        </nav>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

          <form id="rutinas-searchform">
            <? /* barra de busqueda */ ?>
            <div id="rutinas-searchbar" class="mb-5">
              <div class="form-inline">
                <input id="searchText" type="search" class="form-control mb-3" placeholder="Búsqueda" name="name" value="<?php echo getValue($_REQUEST, 'name', '') ?>">
                <button type="submit" class="btn red-bg white-color white-color-hover ml-2 mb-3">
                  <i class="fas fa-search"></i>
                </button>
              </div>
              <div class="form-row align-items-right">
                <div class="col">
                  <a class="btn btn-link red-bg white-color white-color-hover" href="./detail.php">Nueva rutina</a>
                </div>
              </div>
            </div>

            <div class="result-list" class="p-4">
              <?php $rutinas = $rutinasController -> listRutinas($page * $max_results, $max_results, $filters) ?>
              <?php $total = $rutinasController -> countRutinas($filters) ?>
              <?php if ($total > 0 && sizeof($rutinas) > 0) { ?>
                <div class="row">
                  <?php foreach ($rutinas as $rutina) { ?>
                    <div class="col col-md-4 col-lg-3 mb-4">
                      <div class="card black-bg">
                        <div class="card-body">
                          <h5 class="card-title white-color"><?php echo $rutina -> getNombre() ?></h5>
                        </div>
                        <?php if (!is_null($rutina -> getEjercicios()) && sizeof ($rutina -> getEjercicios()) > 0) { ?>
                          <div class="card-actions">
                            <?php foreach ($rutina -> getEjercicios() as $ejercicio) { ?>
                              <?php /*<div class="ejericio"><?php echo $ejercicio -> getNombre() ?></div>*/ ?>
                            <?php } ?>
                          </div>
                        <?php } ?>
                        <div class="card-body">
                          <a href="./detail.php?id=<?php echo $rutina -> getId() ?>" class="card-link btn btn-link red-bg white-color white-color-hover"><i class="fas fa-pencil-alt"></i>&nbsp;Editar</a>
                          <a href="./service.php?action=delete&id=<?php echo $rutina -> getId() ?>" class="card-link btn btn-link red-bg white-color white-color-hover"><i class="fas fa-trash"></i>&nbsp;Eliminar</a>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              <?php } else { ?>
                <span class="no-results">No se ha encontrado ninguna rutina</span>
              <?php } ?>
            </div>

            <div class="pagination">
              <?php $num_pages = ceil($total / $max_results); ?>
              <?php if ($num_pages > 1) { ?>
                <nav aria-label="Page navigation example">
                  <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($page == 0 || $num_pages < 2 ? 'disabled' : '') ?>">
                      <a class="page-link" href="<?php echo toQueryParams($filters, array('p' => $page)) ?>" tabindex="-1" aria-disabled="true">Anterior</a>
                    </li>

                    <?php if ($num_pages > 2) { ?>
                      <?php if ($page == ($num_pages - 1) && $num_pages >= 3) { ?>
                        <li class="page-item">
                          <a class="page-link" href="<?php echo toQueryParams($filters, array('p' => $page - 1)) ?>"><?php echo $page - 1 ?></a>
                        </li>
                      <?php } ?>

                      <?php if ($page > 0) { ?>
                        <li class="page-item">
                          <a class="page-link" href="<?php echo toQueryParams($filters, array('p' => $page)) ?>"><?php echo $page ?></a>
                        </li>
                      <?php } ?>

                      <li class="page-item">
                        <a class="page-link" href="<?php echo toQueryParams($filters, array('p' => $page + 1)) ?>"><?php echo $page + 1 ?></a>
                      </li>

                      <?php if ($page < ($num_pages - 1)) { ?>
                        <li class="page-item">
                          <a class="page-link" href="<?php echo toQueryParams($filters, array('p' => $page + 2)) ?>"><?php echo $page + 2 ?></a>
                        </li>
                      <?php } ?>

                      <?php if ($page == 0 && $num_pages >= 3) { ?>
                        <li class="page-item">
                          <a class="page-link" href="<?php echo toQueryParams($filters, array('p' => $page + 3)) ?>"><?php echo $page + 3 ?></a>
                        </li>
                      <?php } ?>
                    <?php } ?>

                    <li class="page-item <?php echo ($page == ($num_pages - 1) || $num_pages < 2 ? 'disabled' : '') ?>">
                      <a class="page-link" href="<?php echo toQueryParams($filters, array('p' => $page + 2)) ?>">Siguiente</a>
                    </li>
                  </ul>
                </nav>
              <?php } ?>
            </div>
          </form>
        </main>
      </div>
    </div>

    <?php include_once($include_path . 'parts/common_body.php') ?>

    <?php closeConnection($connection); ?>

    <script type="text/javascript">
      (function() {
        "use strict";

        let form = $('#rutinas-searchform');

        // disable empty form fields
        if (form.length) {
          form.submit(function() {
            $(this).find(':input[value=""]').each(function() {
              var _this = $(this);
              if (_this.val().trim() === '') {
                _this.attr("disabled", "disabled");
              }
            });
            return true;
          })
        }
      })();
    </script>
  </body>
</html>
