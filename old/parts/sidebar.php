<?php

  $menu = array(
    array(
      "icon" => "<i class='fas fa-users fa-fw'></i>",
      "title" => "Clientes",
      "link" => "/personal-gym/clientes/listV2.php",
      "children" => array()
    ),
    array(
      "icon" => "<i class='fas fa-running fa-fw'></i>",
      "title" => "Rutinas y actividades",
      "link" => "/rutinas",
      "children" => array(
        array(
          "icon" => "<i class='far fa-calendar-alt'></i>",
          "title" => "Ver rutinas",
          "link" => "/list.php"
        ),
        array(
          "icon" => "<i class='far fa-calendar-plus'></i>",
          "title" => "Crear rutina",
          "link" => "/detail.php"
        ),
        array(
          "icon" => "<i class='fas fa-dumbbell'></i>",
          "title" => "Ver ejercicios",
          "link" => "/ejercicios/list.php"
        ),
        array(
          "icon" => "<i class='fas fa-dumbbell'></i>",
          "title" => "Crear ejercicio",
          "link" => "/ejercicios/detail.php"
        )
      )
    ),
    array(
      "icon" => "<i class='fas fa-store'></i>",
      "title" => "Productos",
      "link" => "/gestionProductos",
      "children" => array(
        array(
          "icon" => "<i class='fas fa-list'></i>",
          "title" => "Ver Productos",
          "link" => "/consultaProductos.php"
         
        ),
        array(
          "icon" => "<i class='fas fa-shopping-bag'></i>",
          "title" => "Compras",
          "link" => "/consultaCompra.php"
        )
      )
    )
  );

  $current_path = getRequestPath();
?>

<div class="sidebar-sticky">
  <ul class="nav flex-column">
    <?php foreach ($menu as $entry) { ?>
      <li class="nav-item">
        <?php if (array_key_exists("children", $entry) && sizeof($entry["children"]) > 0) { ?>
          <? /* menu con elementos hijos */ ?>

          <?php
            // creamos un id aleatorio para el desplegable del menu
            $entry_dom_id = "entry_" . getRandomString();
            // comprobamos si la url actual pertenece a alguno de los hijos de esta entrada del menu
            $entry_link = '/personal-gym' . $entry["link"];
            $expanded = substr($current_path, 0, strlen($entry_link)) === $entry_link;
          ?>
          <a class="nav-link red-bg-hover white-color white-color-hover" href="#<?php echo $entry_dom_id ?>" role="button"
             data-toggle="collapse" aria-expanded="<?php echo $expanded ?>" aria-controls="<?php echo $entry_dom_id?>">
            <?php echo $entry["icon"] ?>
            <?php echo $entry["title"] ?>
          </a>

          <div class="collapse pl-3" id="<?php echo $entry_dom_id ?>">
            <?php foreach ($entry["children"] as $entryChildren) { ?>
              <?php
                $entry_children_link = $entry_link . $entryChildren["link"];

                $entry_children_classes = "nav-link red-bg-hover white-color white-color-hover";
                if ($entry_children_link == $current_path) {
                  $entry_children_classes .= " selected";
                }
              ?>
              <a class="<?php echo $entry_children_classes ?>" href="<?php echo $entry_children_link ?>">
                <?php echo $entryChildren["icon"] ?>
                <?php echo $entryChildren["title"] ?>
              </a>
            <?php } ?>
          </div>

        <?php } else { ?>
          <? /* elemento menu simple */ ?>
          <a class="nav-link red-bg-hover white-color white-color-hover" href="<?php echo $entry["link"]; ?>">
            <?php echo $entry["icon"]; ?>
            <?php echo $entry["title"]; ?>
          </a>
        <?php } ?>
      </li>
    <?php } ?>
  </ul>
</div>
