<?php
//Obtiene la pagina actual que se ejecuta
    function obtenerPaginaActual(){
      $archivo = basename($_SERVER['PHP_SELF']);
      $pagina = str_replace(".php","",$archivo);
      return $pagina;
    }
    obtenerPaginaActual();

    //Consultas
    //Obtener todos los proyectos
    function obtenerProyectos(){
      include 'conexion.php';
      try {
              return $conn->query('SELECT id_proyecto, nombre FROM proyectos');
      } catch (Exception $e) {
            echo "Error! :" . $e->getMessage();
            return false;
      }

    }
    //obtener nombre del proyecto
    function obtenerNombreProyecto($id = null){
         include 'conexion.php';
         try {
                 return $conn->query("SELECT nombre FROM proyectos WHERE id_proyecto = {$id}");
         } catch (Exception $e) {
               echo "Error! :" . $e->getMessage();
               return false;
         }
    }
    //Obteler las clases del proyecto
    function obtenerTareasProyecto($id = null){
         include 'conexion.php';
         try {
                 return $conn->query("SELECT id_tareas, nombre, estado FROM tareas WHERE id_proyecto = {$id}");
         } catch (Exception $e) {
               echo "Error! :" . $e->getMessage();
               return false;
         }
    }
 ?>
