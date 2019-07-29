<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Detalle de rutina</title>

    <?php $include_path = dirname(__FILE__) . '/../'; ?>

    <?php include_once($include_path . 'parts/common_head.php') ?>
    <?php include_once($include_path . 'persistence/db-connection.php') ?>
    <?php include_once($include_path . 'persistence/rutinas/controller.php') ?>
    <?php include_once($include_path . 'persistence/ejercicios/controller.php') ?>
    <?php include_once($include_path . 'rutinas/util.php') ?>

    <?php
      $connection = createConnection();
      $rutinasController = new RutinasControllerDB($connection);
      $ejerciciosController = new EjerciciosControllerDB($connection);

      $rutina = null;
      if (isset($_REQUEST['id']) && strlen($_REQUEST['id']) > 0) {
        try {
          $rutina = $rutinasController -> getRutina($_REQUEST['id']);
        } catch (Exception $e) {
          header('Location: /personal-gym/error.php');
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

          <?php if (is_null($rutina)) { ?>
            <?php $rutina = new RutinaDB(null); ?>
            <h2 class="mb-4">Crear nueva rutina</h2>
          <?php } else { ?>
            <h2 class="mb-4">Editar rutina</h2>
          <?php } ?>

          <form id="rutina-form" method="POST" action="/personal-gym/rutinas/validate.php" novalidate>
            <?php if (!is_null($rutina -> getId())) { ?>
                <input type="hidden" name="id" value="<?php echo $rutina -> getId() ?>">
            <?php } ?>
            <div class="form-group">
              <label for="name">Nombre</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Introduzca el nombre" value="<?php echo $rutina -> getNombre() ?>" required>
              <div class="invalid-feedback">
                Por favor, elija un nombre para la rutina.
              </div>
            </div>
            <h6 class="mt-4">Ejercicios</h6>

            <?php if (is_null($rutina -> getEjercicios())) { ?>
              <?php $rutina -> setEjercicios(array()); ?>
            <?php } ?>

            <?php $days = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes") ?>

            <div class="calendario-semanal-rutina mb-4">
              <?php for ($i = 0; $i < sizeof($days); $i ++) { ?>
                <div class="calendario-dia" data-day="<?php echo $i + 1 ?>">
                  <h6><?php echo $days[$i] ?></h6>
                  <div class="actividades-dia">
                    <?php $ejerciciosDia = getEjerciciosByDiaSemana($rutina -> getEjercicios(), $i + 1); ?>
                    <?php if (sizeof($ejerciciosDia) === 0) { ?>
                      <p class="empty p-4">-</p>
                    <?php } else { ?>
                      <?php foreach($ejerciciosDia as $ejercicio) { ?>
                        <div class="actividad card white-bg" data-exercise-id="<?php echo $ejercicio -> getId() ?>">
                          <div class="card-body">
                            <h5 class="card-title"><?php echo $ejercicio -> getNombre() ?></h5>
                          </div>
                          <div class="card-body actions">
                            <div class="d-inline-flex flex-nowrap justify-content-between w-100">
                              <span class="red-color" data-action="up">
                                <i class="fas fa-arrow-up"></i>
                              </span>
                              <span class="red-color" data-action="down">
                                <i class="fas fa-arrow-down"></i>
                              </span>
                                <span class="red-color" data-action="delete">
                                  <i class="far fa-trash-alt"></i>
                                </span>
                            </div>
                          </div>
                        </div>
                      <?php } ?>
                      <p class="empty p-4 d-none">-</p>
                    <?php } ?>
                  </div>
                  <div class="w100">
                    <button type="button" class="btn red-bg white-color" data-day="<?php echo $i + 1 ?>">
                      Añadir
                    </button>
                  </div>
                </div>
              <?php } ?>
            </div>
            <div class="form-group">
              <input type="submit" class="btn red-bg white-color" name="submit" value="Guardar">
            </div>
          </form>
        </main>
      </div>
    </div>

    <div id="ejerciciosModal" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Añadir Ejercicios</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <?php $ejercicios = $ejerciciosController -> listEjercicios(null, null, null, array("column" => "nombre", "asc" => true)) ?>
            <?php if (sizeof($ejercicios) > 0) { ?>
              <p>Seleccione los ejercicios que desea añadir</p>
              <ul class="list-group">
                <?php foreach($ejercicios as $ejercicio) { ?>
                <li class="list-group-item" data-exercise-id="<?php echo $ejercicio -> getId() ?>">
                  <?php echo $ejercicio -> getNombre() ?>
                </li>
                <?php } ?>
              </ul>
            <?php } else { ?>
              <p>Aún no se ha creado ningún ejercicio</p>
              <p>Pulse <a href="/personal-gym/ejercicios/detail.php">aquí</a> para crear uno</p>
            <?php } ?>
          </div>
          <div class="modal-footer">
            <button id="modalSubmit" type="button" class="btn red-bg white-color">Añadir</button>
            <button type="button" class="btn black-bg white-color" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>

    <?php include_once($include_path . 'parts/common_body.php') ?>

    <?php closeConnection($connection); ?>

    <script type="text/javascript">
      (function() {
        "use strict";

        const selectors = {
          form: $('#rutina-form'),
          modal: $('#ejerciciosModal'),
          addButton: $('.calendario-dia button'),
          listItem: $('#ejerciciosModal .list-group .list-group-item'),
          modalSubmit: $('#ejerciciosModal #modalSubmit'),
          dayContainer: function(day) {
            return $('.calendario-dia[data-day' + (day != null ? '=' + day : '') + '] .actividades-dia');
          },
          actionSelector: function($el, action) {
            return $el.find('[data-action=' + action + ']');
          },
          emptyDay: function(day) {
            return this.dayContainer(day).find('.empty');
          },
          dayExercise: function(day, id) {
            return this.dayContainer(day).find('[data-exercise-id=' + id + ']')
          }
        };

        const template = '<div class="actividad card white-bg" data-exercise-id="%id%"><div class="card-body"><h5 class="card-title">%nombre%</h5></div><div class="card-body actions"><div class="d-inline-flex flex-nowrap justify-content-between w-100"><span class="red-color" data-action="up"><i class="fas fa-arrow-up"></i></span><span class="red-color" data-action="down"><i class="fas fa-arrow-down"></i></span><span class="red-color" data-action="delete"><i class="far fa-trash-alt"></i></span></div></div></div>';

        <?php $mapfn = function($ejercicio) { return $ejercicio -> toArray(); }; ?>
        let added = <?php echo json_encode(array_map($mapfn, $rutina -> getEjercicios())) ?>;
        let currentDay = null;

        // display the days modal
        const showModal = function(day) {
          selectors.listItem.removeClass('d-none active');
          currentDay = day;

          added
            .filter(function(exercise) { return exercise.diaSemana === day })
            .map(function(exercise) { return selectors.listItem.parent().find('[data-exercise-id=' + exercise.id + ']') })
            .forEach(function($el) { $el.addClass('d-none') });

          selectors.modal.modal('show');
        };

        const exerciseUp = function(evt) {
          let $el = $(evt.target).closest('.actividad');

          if ($el.index() > 0) {
            let $before = $el.prev();
            $el.insertBefore($before);
          }
        };

        const exerciseDown = function(evt) {
          let $el = $(evt.target).closest('.actividad');
          let tot = $el.parent().find('.actividad').length;

          if ($el.index() < tot - 1) {
            let $after = $el.next();
            $el.insertAfter($after);
          }
        };

        const deleteDayExercise = function(evt) {
          let $el = $(evt.target).closest('.actividad');
          let $parent = $el.parent();

          $el.remove();
          if (!$parent.find('.actividad').length) {
            selectors.emptyDay(currentDay).removeClass('d-none');
          }

          let id = parseInt($el.attr('data-exercise-id'));
          let index = added.
            map(function(exercise, i) { return [exercise, i] })
            find(function(arr) { arr[0].diaSemana === currentDay && arr[0].id === id });

          if (index != null) {
            added.splice(index, 1);
          }
        };

        const bindExerciseEvents = function($el) {
          let action = selectors.actionSelector($el, 'up');
          action.off('click');
          action.on('click', exerciseUp);

          action = selectors.actionSelector($el, 'down');
          action.off('click');
          action.on('click', exerciseDown);

          action = selectors.actionSelector($el, 'delete');
          action.off('click');
          action.on('click', deleteDayExercise);
        };

        selectors.dayContainer().find('.actividad')
          .each(function() { bindExerciseEvents($(this)) });

        // bind add exercises buttons event
        selectors.addButton.on('click', function(evt) {
          let day = parseInt($(this).attr('data-day'));

          showModal(day);
        });

        selectors.listItem.on('click', function() {
          $(this).toggleClass('active');
        });

        selectors.modalSubmit.on('click', function() {
          let exercises = [];
          selectors.listItem
            .parent().find('.active')
            .each(function() {
              let exercise = {
                id: parseInt($(this).attr('data-exercise-id')),
                diaSemana: parseInt(currentDay),
                nombre: $(this).text().trim()
              };

              exercises.push(exercise);
            });

          exercises.forEach(function(exercise) {
            let parsed = Object.keys(exercise)
              .reduce(function(tot, curr) {
                return tot.replace(new RegExp('%' + curr + '%', 'ig'), exercise[curr]);
              }, template);

            let $dayContainer = selectors.dayContainer(currentDay);
            $dayContainer.prepend(parsed);
            bindExerciseEvents($dayContainer.find('[data-exercise-id=' + exercise.id + ']'));
          });

          if (exercises.length) {
            selectors.emptyDay(currentDay).addClass('d-none');
          }

          added = added.concat(exercises);

          selectors.modal.modal('hide');
        });

        let $form = selectors.form;
        $form.on('submit', function(evt) {
          let form = $form[0];
          $form.addClass('was-validated');
          if (form.checkValidity() === false) {
            evt.preventDefault();
            evt.stopPropagation();
          } else {
            // add exercises
            added = added.map(exercise => {
              let $exercise = selectors.dayExercise(exercise.diaSemana, exercise.id);
              exercise.orden = $exercise.index();
              return exercise;
            });

            console.log('exercises', added);

            $form.append('<input type="hidden" name="exercises" value=\'' + JSON.stringify(added) + '\' /> ');

            return true;
          }
          return false;
        });
      })();
    </script>
  </body>
</html>
