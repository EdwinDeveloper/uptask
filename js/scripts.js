eventListeners();
NuevaTarea();
//Lista de proyectos
var listaProyectos = document.querySelector('ul#proyectos');

function eventListeners() {
  //boton ára crear proyecto
    document.querySelector('.crear-proyecto a').addEventListener('click',nuevoProyecto);
  //Botones para las acciones de las tareas
    document.querySelector('.listado-pendientes').addEventListener('click',accionesTareas);
    //Botones para las acciones de los proyectos
    document.querySelector('.lista-proyectos').addEventListener('click',accionesProyectos);

}
function NuevaTarea() {
  //Boton para una nueva tarea
  if(document.querySelector('nueva-tarea') !== null){
    document.querySelector('.nueva-tarea').addEventListener('click',agregarTarea);
  }

}
function nuevoProyecto(e) {
  e.preventDefault();
  console.log('Presionaste en nuevo Proyecto');

  //Crea un <input> para el nombre del nuevo proyecto
  var nuevoProyecto = document.createElement('li');
  nuevoProyecto.innerHTML = '<input type="text" id="nuevo-proyecto">';
  listaProyectos.appendChild(nuevoProyecto);

  //Seleccionar el ID con el nuevoProyecto
  var inputNuevoProyecto = document.querySelector('#nuevo-proyecto');
  //Al presionar enter crea el proyecto
  inputNuevoProyecto.addEventListener('keypress',function(e) {
        var tecla = e.which || e.keyCode;
        if(tecla ===13){
          guardarProyectoDB(inputNuevoProyecto.value);
          listaProyectos.removeChild(nuevoProyecto);
        }
  });
}
function guardarProyectoDB(nombreProyecto) {

    //crear el llamado ajax
    var xhr = new XMLHttpRequest();

    //enviar datos por FormData
    var datos = new FormData();
    datos.append('proyecto', nombreProyecto);
    datos.append('action','crear');
    //Abrir la conexion
    xhr.open('POST','inc/modelos/modelo-proyecto.php',true);
    //en la carga
    xhr.onload= function() {
        if (this.status === 200) {
          //obtener datos de la respuesta
              var respuesta = JSON.parse(xhr.responseText);
              var proyecto = respuesta.nombre_proyecto,
              id_proyecto = respuesta.id_insertado,
              tipo = respuesta.tipo,
              resultado = respuesta.respuesta;
              //comprovar la insercion
              if (resultado === 'correcto') {
                  //fue exitoso
                        if (tipo==='crear') {
                           //se creo un nuevo proyecto
                           //inyectar en el html
                           var nuevoProyecto = document.createElement('li');
                           nuevoProyecto.innerHTML = `
                                  <a href="index.php?id_proyecto=${id_proyecto}" id="proyecto:${id_proyecto}">
                                        ${proyecto}
                                  </a>
                           `;
                           //agregar al html
                           listaProyectos.appendChild(nuevoProyecto);
                           //enviar alerta
                           swal({
                             title : 'Proyecto Creado',
                             text : 'El proyecto : ' + proyecto + 'se creo correctamente',
                             type : 'success'
                           }).then(resultado=>{
                                            //redireccionar a la nueva url
                                           if(resultado.value){
                                                window.location.href = 'index.php?id_proyecto= ' + id_proyecto;
                                           }
                                  })


                        }else {
                          //Se actualizo o se elimino

                        }
              }else {
                //hubo un error
                swal({
                  title : 'Error',
                  text : 'Ubo un error al crear el usuario ',
                  type : 'error'
                });
              }
        }
    };
    //enviar el request
    xhr.send(datos);
}
function agregarTarea(e){
  e.preventDefault();
  var nombreTarea = document.querySelector('.nombre-tarea').value;

  //validar que el campo tenga algo escrito
  if (nombreTarea==='') {
    swal({
      title: 'Error',
      text: 'Una tarea no puede estar vacia',
      type:'error'
    });
  }else{
    //la tarea tiene algo, se inserta en php

    //CREAR EL LLAMADO AJAX
    var xhr = new XMLHttpRequest();

    //crear formdata
    var datos= new FormData();
    datos.append('tarea',nombreTarea);
    datos.append('action','crear');
    datos.append('id_proyecto', document.querySelector('#id_proyecto').value );
    //Abrir la conexion
    xhr.open('POST','inc/modelos/modelo-tareas.php',true);

    //ejecutarlo y respuesta
    xhr.onload= function() {
      if (this.status===200) {
        //todo correcto
        var respuesta = JSON.parse(xhr.responseText);
        var resultado = respuesta.respuesta,
            tarea= respuesta.tarea,
            id_insertado=respuesta.id_insertado,
            tipo=respuesta.tipo;

            if (resultado==='correcto') {
              //Se agrego correctamente
                      if (tipo==='crear') {
                            //Lanzar la alerta
                            swal({
                              title: 'Guardado ',
                              text: 'La tarea :' + ' Se creo correctamente',
                              type:'success'
                            });

                            //Seleccionar el parrafo con la lista vacia
                            var parrafoListaVacia = document.querySelectorAll('.lista-vacia');
                            if (parrafoListaVacia.length>0) {
                                document.querySelector('.lista-vacia').remove();
                            }

                            //Construir el template
                            var NuevaTarea=document.createElement('li');

                            //Agregamos el id
                            NuevaTarea.id='tarea: '+id_insertado;
                            //Insertar la clase tarea
                            NuevaTarea.classList.add('tarea');
                            //Construir el html
                            NuevaTarea.innerHTML = `
                                <p>${tarea}</p>
                                <div class ="acciones">
                                    <i class ="far fa-check-circle"></i>
                                    <i class = "fas fa-trash"></i>
                                </div>
                            `;
                            //Agregando en el html
                            var listado = document.querySelector('.listado-pendientes ul');
                            listado.appendChild(NuevaTarea);
                            //Limpiando el formulario
                            document.querySelector('.agregar-tarea').reset();
                      }
            }else {
              swal({
                title: 'Error ',
                text: 'Tuviste un error prro',
                type:'error'
              });
            }
      }
    }
    xhr.send(datos);
  }
}
//Cambia el estado de las tareas o las elimina
function accionesTareas(e) {
  e.preventDefault();
  if (e.target.classList.contains('fa-check-circle')) {//acceso a que elemento el usuario dio click target DELEGATION
          if (e.target.classList.contains('completo')) {
            e.target.classList.remove('completo');
            CambiarEstadoTarea(e.target, 0);
          }else {
            e.target.classList.add('completo');
            CambiarEstadoTarea(e.target,1);
          }
  }
  if (e.target.classList.contains('fa-trash')) {//acceso a que elemento el usuario dio click target
            swal({
                  title: 'Sure?',
                  text: "This action can not be revert",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!',
                  cancelButtonText: 'Cancelar'
                }).then((result) => {
                      if (result.value) {
                        var tareaEliminar = e.target.parentElement.parentElement;
                        //Borrar de la Base de datos
                        eliminarTareaBD(tareaEliminar);
                        //Borrar del HTML
                        tareaEliminar.remove();
                        swal(
                          'Eliminado!!',
                          'Your file has been deleted.',
                          'success'
                        )
                      }
                })
      }
}
//completa o descompleta una tarea
function CambiarEstadoTarea(tarea, estado) {
  var idTarea = tarea.parentElement.parentElement.id.split(':')
  //crear el llamado ajax
  var xhr = new XMLHttpRequest();

  //Informacion
  var datos = new FormData();
  datos.append('id',idTarea[1]);
  datos.append('action','actualizar');
  datos.append('estado', estado);
  //Abrir la conexion
  xhr.open('POST','inc/modelos/modelo-tareas.php',true);

  //onload
  xhr.onload = function() {
      if (this.status=== 200) {
           console.log(JSON.parse(xhr.responseText));
      }
  }
  xhr.send(datos);
}
//Elimina las tareas de la Base de Datos
function eliminarTareaBD(tarea) {
  var idTarea = tarea.id.split(':')
  //crear el llamado ajax
  var xhr = new XMLHttpRequest();

  //Informacion
  var datos = new FormData();
  datos.append('id',idTarea[1]);
  datos.append('action','eliminar');
  //Abrir la conexion
  xhr.open('POST','inc/modelos/modelo-tareas.php',true);

  //onload
  xhr.onload = function() {
      if (this.status=== 200) {
           console.log(JSON.parse(xhr.responseText));
           //Comprovar que haya tareas restantes
           var listaTareasRestantes = document.querySelectorAll('li.tarea');
           if (listaTareasRestantes.length === 0) {
                document.querySelector('.listado-pendientes ul').innerHTML = "<p class='lista-vacia'>No hay tareas en este proyecto</p>";
           }
      }
  }
  xhr.send(datos);
}
function accionesProyectos(e) {
  if (e.target.classList.contains('fa-trash')) {
      console.log('clic en eliminar proyecto');
  }
}
