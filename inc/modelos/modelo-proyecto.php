<?php
    $action= $_POST['action'];
    $proyecto = $_POST['proyecto'];

    if ($action === 'crear') {

      include '../funciones/conexion.php';
            try {
                  //realizar la consulta de la base de datos

                  $stmt = $conn->prepare("INSERT INTO proyectos (nombre) VALUES(?)");
                  $stmt->bind_param('s',$proyecto);
                  $stmt->execute();
                  if($stmt->affected_rows > 0){
                    $respuesta=array(
                      'respuesta' => 'correcto',
                      'id_insertado' => $stmt->insert_id,
                      'tipo' => $action,
                      'nombre_proyecto' => $proyecto
                    );
                  }else {
                     $respuesta = array(
                       'respuesta' => 'error',
                       'id_insertado' => $stmt->insert_id,
                       'tipo' => $action,
                       'nombre_proyecto' => $proyecto
                     );
                  }

                  $stmt->close();
                  $conn->close();
            } catch (Exception $e) {
                          $respuesta = array(
                                  'error' => $e->getMessage()
                          );
            }
            echo json_encode($respuesta);
    }

 ?>
