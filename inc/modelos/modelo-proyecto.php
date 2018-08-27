<?php
    $action= $_POST['action'];
    $proyecto = $_POST['proyecto'];
    //variables proyecto
    $id_proyecto = (int) $_POST['id'];

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
    else if ($action === 'eliminar') {
                include '../funciones/conexion.php';
                try {
                          $stmt = $conn->prepare("DELETE FROM tareas WHERE id_proyecto = ?");
                          $stmt = $conn->prepare("DELETE FROM proyectos WHERE id_proyecto = ?");
                          $stmt->bind_param('i',$id_proyecto);
                          $stmt->execute();
                          if($stmt->affected_rows > 0){
                            $respuesta = array(
                              'respuesta'=>'correcto',
                              'id_insertado' => $id_proyecto,
                              'tipo' => $action
                            );
                          }
                          else{
                            $respuesta=array(
                              'respuesta'=>'error',
                              'id_insertado' => $id_proyecto,
                              'tipo' => $action
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
