function loguear() {
    var clave = $('#password').val().trim();
    var usuario = $('#usuario').val();
    // var recordar = $('#recordarPass').is(':checked');

    // Validaciones antes de enviar la petición
    if (usuario === '' || usuario === null) {
        Swal.fire({
            icon: "error",
            title: "INICIO DE SESIÓN",
            text: "SELECCIONE UN USUARIO"
        });
        return;
    }

    if (clave === '') {
        Swal.fire({
            icon: "error",
            title: "INICIO DE SESIÓN",
            text: "INGRESE SU CONTRASEÑA"
        });
        return;
    }

    // // Guardar o borrar cookies según el checkbox
    // if (recordar) {
    //     setCookie('usuarioRecordado', usuario, 30);
    //     setCookie('passRecordado', clave, 30);
    //     setCookie('recordarMarcado', 'true', 30);
    // } else {
    //     borrarCookie('usuarioRecordado');
    //     borrarCookie('passRecordado');
    //     borrarCookie('recordarMarcado');
    // }

    var parametros = $.param({ clave: clave, usuario: usuario });
    const url = BASE_URL + 'login/login';

    $.ajax({
        type: "POST",
        url: url,
        data: parametros,
        dataType: "json",
        success: function(response) {
            if (response.success) {
                window.location.href = BASE_URL + 'dashboard';
            } else {
                Swal.fire({
                    icon: "error",
                    title: "INICIO DE SESIÓN",
                    text: response.message
                });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
                icon: "error",
                title: "ERROR EN LA PETICIÓN",
                text: "Ocurrió un problema al intentar iniciar sesión. Inténtelo de nuevo.",
                footer: "Detalles: " + textStatus + " - " + errorThrown
            });
        }
    });
}