<?php
    $action = $_POST['action'];
    $idproyecto = (int) $_POST['id_proyecto'];
    $tarea = $_POST['tarea'];
    //variables tareas
    $estado = $_POST['estado'];
    $id_tarea =  (int) $_POST['id'];

    if ($action === 'crear') {

      include '../funciones/conexion.php';
            try {
                  //realizar la consulta de la base de datos

                  $stmt = $conn->prepare("INSERT INTO tareas (nombre,id_proyecto ) VALUES(?,?)");
                  $stmt->bind_param('si',$tarea,$idproyecto);
                  $stmt->execute();
                  if($stmt->affected_rows > 0){
                    $respuesta=array(
                      'respuesta' => 'correcto',
                      'id_insertado' => $stmt->insert_id,
                      'tipo' => $action,
                      'tarea' => $tarea
                    );
                  }else {
                     $respuesta = array(
                       'respuesta' => 'error',
                       'id_insertado' => $stmt->insert_id,
                       'id_real' => $idproyecto,
                       'tipo' => $action,
                       'tarea' => $tarea,
                       'debug'=> $stmt->affected_rows
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
    else if ($action === 'actualizar') {
      include '../funciones/conexion.php';
            try {
                  //realizar la consulta de la base de datos

                  $stmt = $conn->prepare("UPDATE tareas SET estado = ? WHERE id_tareas = ? ");
                  $stmt->bind_param('ii',$estado,$id_tarea);
                  $stmt->execute();
                  if($stmt->affected_rows > 0){
                    $respuesta=array(
                      'respuesta' => 'correcto'
                    );
                  }else {
                     $respuesta = array(
                       'respuesta' => 'error'
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
                  //realizar la consulta de la base de datos

                  $stmt = $conn->prepare("DELETE FROM tareas WHERE id_tareas = ? ");
                  $stmt->bind_param('i',$id_tarea);
                  $stmt->execute();
                  if($stmt->affected_rows > 0){
                    $respuesta=array(
                      'respuesta' => 'correcto'
                    );
                  }else {
                     $respuesta = array(
                       'respuesta' => 'error'
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
