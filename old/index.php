<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title></title>

    <?php include_once('parts/common_head.php') ?>

    <link rel="stylesheet" href="/personal-gym/css/index.css">
  </head>
  <body>

    <? /* barra de navegacion superior */ ?>
    <?php include_once('parts/navbar.php') ?>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar black-bg white-color">

          <? /* menu lateral */ ?>
          <?php include_once('parts/sidebar.php') ?>

        </nav>
      </div>
    </div>

    <?php include_once('parts/common_body.php') ?>
  </body>
</html>
