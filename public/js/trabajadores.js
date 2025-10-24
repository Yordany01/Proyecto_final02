// Variables globales
const baseUrl = typeof baseURL !== 'undefined' ? baseURL : (window.location.origin + '/');
let editandoId = null;
let dataTable = null;

document.addEventListener('DOMContentLoaded', function() {
    const esPaginaTrabajadores = document.getElementById('trabajadoresTable')
        || document.getElementById('trabajadorForm')
        || document.getElementById('addNewContact');
    if (!esPaginaTrabajadores) return;

    cargarTrabajadores();
    cargarKPIs();
    inicializarEventos();
});

// Inicializar eventos
function inicializarEventos() {
    const btnAgregar = document.getElementById('addTrabajadorBtn');
    const modal = document.getElementById('addNewContact');
    const formulario = document.getElementById('trabajadorForm');
    const btnEnviarWhatsApp = document.getElementById('btnEnviarWhatsApp');
    const whatsappModal = document.getElementById('whatsappModal');

    if (btnAgregar) {
        btnAgregar.addEventListener('click', function(e) {
            e.preventDefault();
            if (formulario.checkValidity()) {
                guardarTrabajador();
            } else {
                formulario.reportValidity();
            }
        });
    }

    if (modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            resetearFormulario();
        });
    }

    if (btnEnviarWhatsApp) {
        btnEnviarWhatsApp.addEventListener('click', function(e) {
            e.preventDefault();
            enviarMensajeWhatsApp();
        });
    }

    if (whatsappModal) {
        whatsappModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('whatsappForm').reset();
        });
    }
}

// Cargar KPIs
async function cargarKPIs() {
    try {
        const response = await fetch(`${baseUrl}trabajadores/kpis`);
        const result = await response.json();

        if (result.success) {
            const data = result.data || {};
            const totalEl = document.getElementById('kpi-total');
            const activosEl = document.getElementById('kpi-activos');
            const inactivosEl = document.getElementById('kpi-inactivos');
            if (totalEl) totalEl.textContent = data.total ?? 0;
            if (activosEl) activosEl.textContent = data.activos ?? 0;
            if (inactivosEl) inactivosEl.textContent = data.inactivos ?? Math.max(0, (data.total ?? 0) - (data.activos ?? 0));
        }
    } catch (error) {
        console.error('Error al cargar KPIs:', error);
    }
}

// Cargar trabajadores en la tabla
async function cargarTrabajadores() {
    try {
        const response = await fetch(`${baseUrl}trabajadores/listar`);
        const result = await response.json();

        if (result.success) {
            renderizarTabla(result.data);
        } else {
            mostrarError(result.error || 'Error al cargar trabajadores');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarError('Error de conexión al cargar trabajadores');
    }
}

// Renderizar tabla con DataTables
function renderizarTabla(trabajadores) {
    const tbody = document.getElementById('trabajadoresTable');
    
    if (dataTable) {
        dataTable.destroy();
    }

    tbody.innerHTML = '';

    trabajadores.forEach(trabajador => {
        const estadoBadge = trabajador.estado === 'activo' 
            ? '<span class="badge bg-success">Activo</span>' 
            : '<span class="badge bg-secondary">Inactivo</span>';

        const fechaFormateada = formatearFecha(trabajador.fecha_registro);
        
        // Escapar comillas para evitar errores en onclick
        const nombreEsc = (trabajador.nombre || '').replace(/'/g, "\\'");
        const telEsc = (trabajador.telefono || '').replace(/'/g, "\\'");

        const fila = `
            <tr>
                <td>${trabajador.nombre}</td>
                <td>${trabajador.compania}</td>
                <td>${trabajador.correo}</td>
                <td>${trabajador.telefono}</td>
                <td>${estadoBadge}</td>
                <td>${fechaFormateada}</td>
                <td>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-warning" onclick="editarTrabajador(${trabajador.id})" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarTrabajador(${trabajador.id})" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-success" onclick="abrirModalWhatsApp('${nombreEsc}', '${telEsc}')" title="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += fila;
    });

    // Inicializar DataTable
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        dataTable = $('#customButtons').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                    className: 'btn btn-danger btn-sm'
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer me-1"></i>Imprimir',
                    className: 'btn btn-primary btn-sm'
                }
            ],
            order: [[5, 'desc']]
        });
        configurarFiltrosKPI();
    }
}

// Guardar trabajador (crear o actualizar)
async function guardarTrabajador() {
    const nombre = document.getElementById('modalNombre').value.trim();
    const compania = document.getElementById('modalCompania').value.trim();
    const correo = document.getElementById('modalCorreo').value.trim();
    const telefono = document.getElementById('modalTelefono').value.trim();
    const estado = document.getElementById('modalEstado').value;
    const fecha_registro = document.getElementById('modalFecha').value;

    const datos = {
        nombre,
        compania,
        correo,
        telefono,
        estado,
        fecha_registro
    };

    const url = editandoId 
        ? `${baseUrl}trabajadores/actualizar/${editandoId}` 
        : `${baseUrl}trabajadores/insertar`;

    try {
        const formData = new URLSearchParams();
        Object.keys(datos).forEach(key => formData.append(key, datos[key]));

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            mostrarExito(result.message);
            cerrarModal();
            cargarTrabajadores();
            cargarKPIs();
        } else {
            mostrarError(result.error || 'Error al guardar el trabajador');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarError('Error de conexión al guardar');
    }
}

// Editar trabajador
async function editarTrabajador(id) {
    try {
        const response = await fetch(`${baseUrl}trabajadores/obtener/${id}`);
        const result = await response.json();

        if (result.success) {
            const trabajador = result.data;
            
            document.getElementById('addNewContactLabel').textContent = 'Editar Trabajador';
            document.getElementById('addTrabajadorBtn').textContent = 'Actualizar Trabajador';
            
            document.getElementById('modalNombre').value = trabajador.nombre;
            document.getElementById('modalCompania').value = trabajador.compania;
            document.getElementById('modalCorreo').value = trabajador.correo;
            document.getElementById('modalTelefono').value = trabajador.telefono;
            document.getElementById('modalEstado').value = trabajador.estado;
            document.getElementById('modalFecha').value = trabajador.fecha_registro;
            
            editandoId = id;
            
            const modal = new bootstrap.Modal(document.getElementById('addNewContact'));
            modal.show();
        } else {
            mostrarError(result.error || 'Error al cargar el trabajador');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarError('Error de conexión al cargar el trabajador');
    }
}

// Eliminar trabajador
async function eliminarTrabajador(id) {
    const confirmado = await confirmarAccion('¿Eliminar trabajador?', 'Esta acción no se puede deshacer');
    if (!confirmado) return;

    try {
        const response = await fetch(`${baseUrl}trabajadores/eliminar/${id}`, {
            method: 'POST'
        });

        const result = await response.json();

        if (result.success) {
            mostrarExito(result.message);
            cargarTrabajadores();
            cargarKPIs();
        } else {
            mostrarError(result.error || 'Error al eliminar el trabajador');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarError('Error de conexión al eliminar');
    }
}

// Abrir modal de WhatsApp con datos prellenados
function abrirModalWhatsApp(nombre, telefono) {
    console.log('📱 Abriendo modal WhatsApp para:', nombre, telefono);
    
    document.getElementById('whatsapp_nombre').value = nombre;
    document.getElementById('whatsapp_telefono').value = telefono;
    document.getElementById('whatsapp_mensaje').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('whatsappModal'));
    modal.show();
}

// Hacer la función global para que funcione el onclick
if (typeof window !== 'undefined') {
    window.abrirModalWhatsApp = abrirModalWhatsApp;
    window.editarTrabajador = editarTrabajador;
    window.eliminarTrabajador = eliminarTrabajador;
}

// Enviar mensaje de WhatsApp
async function enviarMensajeWhatsApp() {
    const nombre = document.getElementById('whatsapp_nombre').value.trim();
    const telefono = document.getElementById('whatsapp_telefono').value.trim();
    const mensaje = document.getElementById('whatsapp_mensaje').value.trim();

    if (!nombre || !telefono || !mensaje) {
        mostrarError('Todos los campos son obligatorios');
        return;
    }

    const btnEnviar = document.getElementById('btnEnviarWhatsApp');
    const textoOriginal = btnEnviar ? btnEnviar.innerHTML : '';
    if (btnEnviar) {
        btnEnviar.disabled = true;
        btnEnviar.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Enviando...';
    }

    try {
        const formData = new URLSearchParams();
        formData.append('nombre', nombre);
        formData.append('telefono', telefono);
        formData.append('mensaje', mensaje);

        console.log('📤 Enviando a:', `${baseUrl}trabajadores/enviarWhatsApp`);
        console.log('📋 Datos:', { nombre, telefono, mensaje });

        const response = await fetch(`${baseUrl}trabajadores/enviarWhatsApp`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        });

        const result = await response.json();
        
        console.log('📥 Respuesta del servidor:', result);

        if (result.success) {
            mostrarExito(result.message);
            cerrarModalWhatsApp();
        } else {
            let errorDetallado = result.error || 'Error al enviar el mensaje';
            if (result.http_code) {
                errorDetallado += ` (Código: ${result.http_code})`;
            }
            console.error('❌ Error:', result);
            mostrarError(errorDetallado);
        }
    } catch (error) {
        console.error('❌ Error de red:', error);
        mostrarError('Error de conexión al enviar el mensaje: ' + error.message);
    } finally {
        if (btnEnviar) {
            btnEnviar.disabled = false;
            btnEnviar.innerHTML = textoOriginal;
        }
    }
}

// Cerrar modal de WhatsApp
function cerrarModalWhatsApp() {
    const modalElement = document.getElementById('whatsappModal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
}

// Resetear formulario
function resetearFormulario() {
    document.getElementById('trabajadorForm').reset();
    document.getElementById('addNewContactLabel').textContent = 'Nuevo Trabajador';
    document.getElementById('addTrabajadorBtn').textContent = 'Añadir Trabajador';
    editandoId = null;
}

// Cerrar modal
function cerrarModal() {
    const modalElement = document.getElementById('addNewContact');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
}

// Formatear fecha
function formatearFecha(fecha) {
    const date = new Date(fecha);
    const opciones = { year: 'numeric', month: '2-digit', day: '2-digit' };
    return date.toLocaleDateString('es-PE', opciones);
}

// Filtros por KPI
function configurarFiltrosKPI() {
    const totalCard = document.getElementById('kpi-card-total');
    const activosCard = document.getElementById('kpi-card-activos');
    const inactivosCard = document.getElementById('kpi-card-inactivos');
    if (totalCard) totalCard.addEventListener('click', () => aplicarFiltroEstado(null));
    if (activosCard) activosCard.addEventListener('click', () => aplicarFiltroEstado('Activo'));
    if (inactivosCard) inactivosCard.addEventListener('click', () => aplicarFiltroEstado('Inactivo'));

    // Sincronizar KPIs con el contenido filtrado de la tabla
    if (dataTable && $.fn.DataTable) {
        dataTable.on('draw', () => actualizarKpisDesdeTabla());
        actualizarKpisDesdeTabla();
    }
}

function aplicarFiltroEstado(estado) {
    if (!dataTable || !$.fn.DataTable) return;
    const colEstado = 4; // índice de columna Estado
    if (!estado) {
        dataTable.column(colEstado).search('').draw();
    } else {
        // Coincidencia exacta (evita que 'Activo' coincida con 'Inactivo')
        const pattern = `^${estado}$`;
        dataTable.column(colEstado).search(pattern, true, false).draw();
    }
}

// Actualiza los KPIs en base a las filas actualmente filtradas en la tabla
function actualizarKpisDesdeTabla() {
    if (!dataTable || !$.fn.DataTable) return;
    const api = dataTable;
    const totalFiltrado = api.rows({ filter: 'applied' }).count();
    const colEstado = 4;
    const activosFiltrados = api
        .column(colEstado, { search: 'applied' })
        .data()
        .toArray()
        .filter(texto => /(^|>)\s*Activo\s*(<|$)/i.test(texto) || /^\s*Activo\s*$/i.test(stripHtml(texto)))
        .length;
    const inactivosFiltrados = api
        .column(colEstado, { search: 'applied' })
        .data()
        .toArray()
        .filter(texto => /(^|>)\s*Inactivo\s*(<|$)/i.test(texto) || /^\s*Inactivo\s*$/i.test(stripHtml(texto)))
        .length;

    const totalEl = document.getElementById('kpi-total');
    const activosEl = document.getElementById('kpi-activos');
    const inactivosEl = document.getElementById('kpi-inactivos');
    if (totalEl) totalEl.textContent = totalFiltrado;
    if (activosEl) activosEl.textContent = activosFiltrados;
    if (inactivosEl) inactivosEl.textContent = inactivosFiltrados;
}

// Utilidad para quitar HTML y obtener texto plano
function stripHtml(html) {
    const div = document.createElement('div');
    div.innerHTML = html;
    return (div.textContent || div.innerText || '').trim();
}

// Confirmar acción
async function confirmarAccion(titulo, texto) {
    if (typeof Swal !== 'undefined') {
        const result = await Swal.fire({
            icon: 'warning',
            title: titulo,
            text: texto,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        });
        return result.isConfirmed;
    }
    return confirm(titulo + '\n' + texto);
}

// Mostrar mensaje de éxito
function mostrarExito(mensaje) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Operación exitosa',
            text: mensaje || 'Operación realizada correctamente',
            confirmButtonText: 'OK'
        });
    } else {
        alert(mensaje || 'Operación realizada correctamente');
    }
}

// Mostrar mensaje de error
function mostrarError(mensaje) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Ocurrió un error',
            text: mensaje || 'Ha ocurrido un error',
            confirmButtonText: 'OK'
        });
    } else {
        alert(mensaje || 'Ha ocurrido un error');
    }
}