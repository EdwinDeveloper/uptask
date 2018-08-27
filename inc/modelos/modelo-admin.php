<?php
      $usuario =$_POST['usuario'];
      $password =$_POST['password'];
      $action =$_POST['action'];

      if ($action === 'crear') {
              //codigo para crear los administradores
              //hasear el password
                $opcionesHash = array(
                  'cost' => 12
                );
                $hash_password = password_hash($password, PASSWORD_BCRYPT, $opcionesHash);
                include '../funciones/conexion.php';
              try {
                    //realizar la consulta de la base de datos

                    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, password) VALUES(?,?)");
                    $stmt->bind_param('ss',$usuario,$hash_password);
                    $stmt->execute();
                    if($stmt->affected_rows > 0){
                      $respuesta=array(
                        'respuesta' => 'correcto',
                        'id_insertado' => $stmt->insert_id,
                        'tipo' => $action,
                        'otro' => $stmt->affected_rows
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
else if ($action === 'login') {
   include '../funciones/conexion.php';
   try {
     //seleccionar el registro del administrador de la base de datos
     $stmt=$conn->prepare("SELECT usuario, id, password FROM usuarios WHERE usuario = ?");
     $stmt->bind_param('s',$usuario);
     $stmt->execute();
     //loguear el usuario
     $stmt->bind_result($nombre_usuario,$id_usuario,$password_usuario);
     $stmt->fetch();
         if ($nombre_usuario) {
                  //El usuario existe verificar el password
                  if(password_verify($password,$password_usuario)){
                  //INICIAR LA SESSION
                  session_start();
                  $_SESSION['nombre'] = $nombre_usuario;
                  $_SESSION['id'] = $id_usuario;
                  $_SESSION['login'] = true ;
                    //Login correcto
                      $respuesta = array(
                          'respuesta'=>'correcto',
                          'nombre'=>$nombre_usuario,
                          'tipo' => $action
                                      );
                    }else{
                          $respuesta = array(
                                'Respuesta'=> 'Contraseña Incorrecta'
                                            );
                    }

         }else {
                  $respuesta=array(
                        'error' => 'usuario no existe'
                  );
         }
     $stmt->close();
     $conn->close();
   } catch (Exception $e) {
                 $respuesta = array(
                         'pass' => $e->getMessage()
                 );
   }
   echo json_encode($respuesta);
}




 ?>
