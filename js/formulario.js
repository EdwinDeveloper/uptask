eventListeners();

function eventListeners(){
  document.querySelector('#formulario').addEventListener('submit',validarRegistro);
}
function validarRegistro(e) {
  e.preventDefault();
  var usuario = document.querySelector('#usuario').value,
  password = document.querySelector('#password').value,
  tipo = document.querySelector('#tipo').value;

  if (usuario ==='' || password==='') {
    swal({
        type: 'error',
        title: 'Error muy grave!!! resulevelo prro!!',
        text: 'Ambos campos son obligatorios!'
    })
  }else {
       //Ambos campos son correctos y manda datos
       //Datos que se envian al servidor
       var datos = new FormData();
       datos.append('usuario',usuario);
       datos.append('password',password);
       datos.append('action',tipo);

       //crear el llamado ajax
       var con = new XMLHttpRequest();
       con.open('POST','inc/modelos/modelo-admin.php',true);
       con.onload = function() {
         if (this.status === 200) {
           var respuestaR = JSON.parse(con.responseText);
           console.log(respuestaR);
              if (respuestaR.respuesta === 'correcto') {
                    if (respuestaR.tipo==='crear') {
                          swal({
                            title : 'Usuario Creado',
                            text : 'El usuario se creo correctamente',
                            type : 'success'
                          });
                    }
                    else if(respuestaR.tipo === 'login'){
                      swal({
                        title : 'Login Correcto',
                        text : 'Presiona OK para abrir el dashboard',
                        type : 'success'
                      })
                      .then(resultado => {
                        console.log(resultado);
                        if(resultado.value){
                            window.location.href = 'index.php';
                        }
                      })
                    }

              }else {
                swal({
                  title : 'Error',
                  text : 'Ubo un error',
                  type : 'error'
                });
              }
         }
       }

         con.send(datos);


  }
}
