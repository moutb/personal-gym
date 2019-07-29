<html lang="es">

  <head>
    <title>Inicio de sesión</title>

    <?php include_once('parts/common_head.php') ?>

    <link rel="stylesheet" type="text/css" href="/personal-gym/css/login.css">
  </head>

  <body>
    <div class="container-fluid vh-100">
      <div class="centered-pane">
          <div class="login-panel jumbotron">
            <h1 class="display-4">Inicio de sesión</h1>
            <p class="lead">Para poder continuar es necesario que introduzca sus credenciales</p>
            <form name="login">
              <div class="form-group row justify-content-center">
                <div class="col-8">
                  <input type="text" class="form-control form-control-lg" id="user" placeholder="usuario" value="">
                </div>
              </div>
              <div class="form-group row justify-content-center">
                <div class="col-8">
                  <input type="password" class="form-control form-control-lg" id="password" placeholder="clave" value="">
                </div>
              </div>
              <div class="form-group row justify-content-center">
                <div class="col text-center">
                  <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    <?php include_once('parts/common_body.php') ?>
  </body>
</html>
