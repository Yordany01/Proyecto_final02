$(document).ready(function () {
    Promise.all([
       // llenarSucursalAsync(),
       // obtenerFraseAsync()
    ]);
});


function obtenerFraseAsync() {
    // let frase = sessionStorage.getItem('frase-dia');
    // if (frase) {
    //     $('#frase-dia').text(frase);
    //     return Promise.resolve();
    // } else {
    //     return $.ajax({
    //         type: "POST",
    //         url: URL_PY + 'guiat/frase',
    //         success: function (response) {
    //             let frase = response.frase;
    //             sessionStorage.setItem('frase-dia', frase);
    //             $('#frase-dia').fadeOut(200, function () {
    //                 $(this).text(frase).fadeIn(400);
    //             });
    //         },
    //         error: function () {
    //             $('#frase-dia').text('Frase no disponible');
    //         }
    //     });
    // }
}

var Español = {
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla =(",
    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
    "sSearch": "Buscar:",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {

        "sNext": ">",
        "sPrevious": "<"
    },
    "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    },
    "buttons": {
        "copy": "Copiar",
        "colvis": "Visibilidad"
    }
}
var mensajeAjaxBlock = "CONSULTANDO DATOS ...";
function ajaxblock() {
    // Crear el overlay
    const overlay = document.createElement('div');
    overlay.id = 'ajax-overlay';
    overlay.innerHTML = `
        <div id='ajax-overlay-body' class='text-center'>
            <img src="${URL_PY}public/dist/img/registrando.gif" alt="Cargando..." class="img-fluid w-60"/>
            <p class='mt-1' style='font-size: 30px;'>${mensajeAjaxBlock}</p>
        </div>
    `;
    document.body.prepend(overlay);

    // Estilos para el overlay
    Object.assign(overlay.style, {
        position: 'fixed',
        top: '0',
        left: '0',
        width: '100%',
        height: '100%',
        background: 'rgb(242, 242, 242)',
        color: '#000000',
        textAlign: 'center',
        zIndex: '9999',
        display: 'none'
    });

    // Estilos para el contenido
    const overlayBody = document.getElementById('ajax-overlay-body');
    Object.assign(overlayBody.style, {
        position: 'absolute',
        top: '40%',
        left: '50%',
        transform: 'translate(-50%, -50%)',
        width: 'auto',
        height: 'auto',
        borderRadius: '10px'
    });

    // Mostrar el overlay con fadeIn
    setTimeout(() => {
        overlay.style.display = 'block';
        overlay.style.opacity = 0;
        let op = 0;
        const fade = setInterval(() => {
            if (op >= 1) clearInterval(fade);
            overlay.style.opacity = op;
            op += 0.2;
        }, 10);
    }, 0);
}

function ajaxunblock() {
    const overlay = document.getElementById('ajax-overlay');
    if (overlay) {
        // FadeOut
        let op = 1;
        const fade = setInterval(() => {
            if (op <= 0) {
                clearInterval(fade);
                overlay.remove();
            } else {
                overlay.style.opacity = op;
                op -= 0.2;
            }
        }, 20);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Agregar un evento de escucha a todo el documento
    document.addEventListener('input', function (event) {
        // Verificar si el elemento que disparó el evento es un input o textarea
        if ((event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') &&

            event.target.id
            !== 'txtclave' &&
            event.target.id
            !== 'txtpassword') { // Ignorar el campo con id 'txtclave'
            // Convertir el valor del input/textarea a mayúsculas
            event.target.value = event.target.value.toUpperCase();
        }
    });
    
});

document.addEventListener('contextmenu', function (e) {
    e.preventDefault();
});

function abrirModalLogin() {
    $('#titulo').html('ELEGIR SUCURSAL/ALMACEN');

    // Abre el modal
    var myModal = new bootstrap.Modal(document.getElementById('mdlcambio'));
    myModal.show();
}

function bloquearCampos(bloquear) {
    // Selecciona todos los inputs, selects, textareas y botones excepto los del modal de login y los botones permitidos
    const campos = document.querySelectorAll('input, select, textarea, button');
    campos.forEach(function(campo) {
        // No bloquear los campos del modal de login ni los botones permitidos
        if (
            !campo.closest('#mdlcambio') &&
            campo.id !== 'btnCambiarSucursal' &&
            campo.id !== 'btnSalir'
        ) {
            campo.disabled = bloquear;
        }
    });
}






function llenarEmpresa() {
    var url = URL_PY + 'cambio/empresa';
    $.ajax({
        type: "POST",
        url: url,
        success: function (response) {
            //console.log(response)
            if (response.success) {
                const empresaSelect = $('#cmbempresas');
                empresaSelect.empty(); // Limpia el select existente
                // Llena el select con las sucursales
                $.each(response.empresas, function (index, empresa) {
                    empresaSelect.append(
                        $('<option>', { value: empresa.idempresa, text: empresa.descripcion })
                    );
                });
                // Llenar almacén basado en la primera sucursal
                llenarSucursal(empresaSelect.val());
            } else {
                alert('No hay empresas');
            }
        },
        error: function (jqXHR, textStatus) {
            console.log('Error: ' + textStatus);
        }
    });
}
function llenarSucursal() {
    var url = URL_PY + 'cambio/sucursal';
    $.ajax({
        type: "POST",
        url: url,       
        success: function (response) {
            //console.log(response)
            if (response.success) {
                const sucursalSelect = $('#cmbsucursal');
                sucursalSelect.empty(); // Limpia el select existente

                // Llena el select con las sucursales
                $.each(response.sucursales, function (index, sucursal) {
                    sucursalSelect.append(
                        $('<option>', { value: sucursal.idsucursal, text: sucursal.descripcion })
                    );
                });

                // Llenar almacén basado en la primera sucursal
                llenarAlmacen(sucursalSelect.val());
            } else {
                alert('No hay sucursales');
            }
        },
        error: function (jqXHR, textStatus) {
            //console.log('Error: ' + textStatus);
        }
    });
}
function llenarAlmacen(sucursal) {
    var url = URL_PY + 'cambio/almacen';
    $.ajax({
        type: "POST",
        url: url,
        data: { sucursal },
        success: function (response) {
            //console.log(response)
            if (response.success) {
                const almacenSelect = $('#cmbalmacen');
                almacenSelect.empty(); // Limpia el select existente

                // Llena el select con los almacenes
                $.each(response.almacenes, function (index, almacen) {
                    //console.log(almacen)
                    almacenSelect.append(
                        $('<option>', { value: almacen.idalmacen, text: almacen.descripcion })
                    );
                });
            } else {
                alert('No hay almacenes');
            }
        },
        error: function (jqXHR, textStatus) {
            //console.log('Error: ' + textStatus);
        }
    });
}

function cambioEmpresa() {
    var empresa = $('#cmbempresas').val();
    var sucursal = $('#cmbsucursal').val();
    var almacen = $('#cmbalmacen').val();

    var parametros = 'idempresa=' + empresa +
        '&idsucursal=' + sucursal + '&idalmacen=' + almacen;

    $.ajax({
        type: "post",
        url: URL_PY + 'cambio/ingresar',
        data: parametros,
        success: function (response) {
            //console.log(response);
            if (response.mensaje) {
                Swal.fire({
                    icon: "error",
                    title: "INICIO DE SESION",
                    text: response.mensaje
                });
            } else {
                location.reload();
            }
        }
    });
}

function cambioUsuario(nombreUsuario) {
  Swal.fire({
    title: '¿Cerrar sesión?',
    html: `Estás a punto de salir de la sesión de <b>${nombreUsuario || 'Usuario'}</b>`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'SÍ, SALIR',
    cancelButtonText: 'CANCELAR',
    allowOutsideClick: false
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = URL_PY + 'login/salir';
    }
  });
}

function validarNumero(input) {
    // Eliminar caracteres no permitidos (dejar solo números y un punto decimal)
    input.value = input.value.replace(/[^0-9.]/g, '');
  
    // Verificar si hay más de un punto decimal y eliminar los extras
    if (input.value.split('.').length > 2) {
      input.value = input.value.substring(0, input.value.lastIndexOf('.'));
    }
}

function validarSerieCorrelativo(input) {
    // Solo permitir letras, números y guiones
    input.value = input.value.replace(/[^A-Za-z0-9-]/g, '');

    // Eliminar caracteres iniciales inválidos
    if (!/^[A-Za-z0-9]/.test(input.value)) {
        input.value = input.value.substring(1); // Elimina el primer carácter si no es válido
    }

    // Permitir solo un guion "-"
    if ((input.value.split('-').length - 1) > 1) {
        input.value = input.value.substring(0, input.value.lastIndexOf('-'));
    }
}

