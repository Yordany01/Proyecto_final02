// Variables globales
let tareaEditandoId = null;
// Base URL compatible con la plantilla (define baseURL) o con el origen actual
const tareasBaseUrl = typeof baseURL !== 'undefined' ? baseURL : (window.location.origin + '/');

// Cargar datos al iniciar
document.addEventListener('DOMContentLoaded', function() {
    // Ejecutar solo si existen elementos del módulo de tareas
    const esPaginaTareas = document.getElementById('tareasTbody') || document.getElementById('tareaForm');
    if (!esPaginaTareas) return;

    cargarTareas();
    cargarKPIs();
});

function mostrarExito(mensaje) {
    mostrarAlerta(mensaje, 'success');
}

// Sincroniza KPIs leyendo la tabla renderizada. Devuelve true si se actualizó desde DOM.
function actualizarKpisDesdeTabla_Simple() {
    const tbody = document.getElementById('tareasTbody');
    if (!tbody) return false;
    const rows = Array.from(tbody.querySelectorAll('tr'));
    // Detectar si realmente hay filas de datos (no el mensaje "No hay tareas...")
    const hasRealRows = rows.some(tr => tr.querySelectorAll('td').length >= 6);
    if (!hasRealRows) return false;

    let total = 0, completadas = 0, pendientes = 0;
    rows.forEach(tr => {
        const tds = tr.querySelectorAll('td');
        if (tds.length < 7) return; // ahora hay 7 columnas
        total += 1;
        const estadoText = (tds[5]?.textContent || '').trim().toLowerCase();
        if (estadoText.includes('completada')) completadas += 1;
        else if (estadoText.includes('pendiente')) pendientes += 1;
    });

    const tEl = document.getElementById('kpi-total');
    const cEl = document.getElementById('kpi-completadas');
    const pEl = document.getElementById('kpi-pendientes');
    if (tEl) tEl.textContent = total;
    if (cEl) cEl.textContent = completadas;
    if (pEl) pEl.textContent = pendientes;
    return true;
}

function cerrarModalWhatsApp() {
    const modalElement = document.getElementById('whatsappModal');
    if (!modalElement) return;
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) modal.hide();
}

// Cargar KPIs
function cargarKPIs() {
    // 1) Preferir sincronización desde la tabla visible
    if (actualizarKpisDesdeTabla_Simple()) return;

    // 2) Fallback: pedir al backend
    fetch(tareasBaseUrl + 'tareas/kpis')
        .then(response => response.json())
        .then(data => {
            const total = (data && typeof data.total !== 'undefined') ? data.total : (data && data.data && data.data.total);
            const completadas = (data && typeof data.completadas !== 'undefined') ? data.completadas : (data && data.data && data.data.completadas);
            const pendientes = (data && typeof data.pendientes !== 'undefined') ? data.pendientes : (data && data.data && data.data.pendientes);
            if (typeof total !== 'undefined') document.getElementById('kpi-total').textContent = total;
            if (typeof completadas !== 'undefined') document.getElementById('kpi-completadas').textContent = completadas;
            if (typeof pendientes !== 'undefined' && document.getElementById('kpi-pendientes')) document.getElementById('kpi-pendientes').textContent = pendientes;
        })
        .catch(error => {
            console.error('Error al cargar KPIs:', error);
        });
}

// Cargar tareas
function cargarTareas() {
    fetch(tareasBaseUrl + 'tareas/listar')
        .then(async response => {
            const text = await response.text();
            try { return JSON.parse(text); } catch { throw new Error('Respuesta no válida: ' + text); }
        })
        .then(data => {
            const tbody = document.getElementById('tareasTbody');
            tbody.innerHTML = '';

            if (data.data && data.data.length > 0) {
                data.data.forEach(tarea => {
                    const tr = document.createElement('tr');
                    
                    // Badge de prioridad
                    let prioridadBadge = '';
                    if (tarea.prioridad === 'alta') {
                        prioridadBadge = '<span class="badge bg-danger">Alta</span>';
                    } else if (tarea.prioridad === 'media') {
                        prioridadBadge = '<span class="badge bg-warning">Media</span>';
                    } else {
                        prioridadBadge = '<span class="badge bg-info">Baja</span>';
                    }

                    // Badge de estado
                    let estadoBadge = '';
                    if (tarea.estado === 'completada') {
                        estadoBadge = '<span class="badge bg-success">Completada</span>';
                    } else {
                        estadoBadge = '<span class="badge bg-secondary">Pendiente</span>';
                    }

                    tr.innerHTML = `
                        <td>${tarea.titulo}</td>
                        <td>${tarea.asignado_a}</td>
                        <td>${tarea.telefono || ''}</td>
                        <td>${formatearFecha(tarea.fecha_limite)}</td>
                        <td>${prioridadBadge}</td>
                        <td>${estadoBadge}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="verTarea(${tarea.id})">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="editarTarea(${tarea.id})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarTarea(${tarea.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button class="btn btn-sm btn-success" onclick="abrirModalWhatsApp('${tarea.asignado_a.replace(/'/g, "\\'")}', '${(tarea.telefono||'').toString().replace(/'/g, "\\'")}')" title="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center">No hay tareas registradas</td></tr>';
            }

            cargarKPIs();
        })
        .catch(error => {
            console.error('Error al cargar tareas:', error);
            mostrarAlerta('Error al cargar las tareas', 'error');
        });
}

// Guardar tarea (crear o actualizar)
function guardarTarea() {
    const form = document.getElementById('tareaForm');
    if (!form) return;

    // Validación mínima
    const titulo = document.getElementById('titulo').value.trim();
    const asignado = document.getElementById('asignado_a').value.trim();
    const telefono = (document.getElementById('telefono_asignado')?.value || '').trim();
    const fecha = document.getElementById('fecha_limite').value;
    const prioridad = document.getElementById('prioridad').value;
    const estado = document.getElementById('estado').value;
    if (!titulo || !asignado || !telefono || !fecha || !prioridad || !estado) {
        mostrarAlerta('Complete los campos requeridos', 'error');
        return;
    }

    const formData = new FormData(form);
    if (telefono) formData.set('telefono', telefono);

    let url = tareasBaseUrl + 'tareas/insertar';
    
    if (tareaEditandoId) {
        url = tareasBaseUrl + 'tareas/actualizar';
        formData.append('id', tareaEditandoId);
    }

    // Deshabilitar botón para evitar doble envío
    const submitBtn = document.querySelector('#nuevaTareaModal button[type="submit"]');
    if (submitBtn) submitBtn.disabled = true;

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        const text = await response.text();
        try { return JSON.parse(text); } catch { throw new Error('Respuesta no válida: ' + text); }
    })
    .then(data => {
        console.log('Respuesta insertar/actualizar tarea:', data);
        if (data && data.success) {
            const msg = data.message || 'Operación realizada correctamente';
            mostrarAlerta(msg, 'success');
            bootstrap.Modal.getInstance(document.getElementById('nuevaTareaModal')).hide();
            form.reset();
            // Sincroniza KPIs con lo que muestra la tabla
            actualizarKpisDesdeTabla_Simple();
            tareaEditandoId = null;
            cargarTareas();
        } else {
            const err = (data && (data.error || data.message)) || 'No se pudo completar la operación';
            mostrarAlerta(err, 'error');
        }
    })
    .catch(error => {
        console.error('Error al enviar tarea:', error);
        mostrarAlerta('Error al procesar la solicitud', 'error');
    })
    .finally(() => {
        if (submitBtn) submitBtn.disabled = false;
    });
}

// Ver detalles de tarea
function verTarea(id) {
    fetch(tareasBaseUrl + 'tareas/obtener/' + id)
        .then(async response => {
            const text = await response.text();
            try { return JSON.parse(text); } catch { throw new Error('Respuesta no válida: ' + text); }
        })
        .then(data => {
            if (data.success) {
                const tarea = data.data;
                document.getElementById('tareaTitulo').textContent = tarea.titulo;
                document.getElementById('tareaDescripcion').innerHTML = `
                    <strong>Asignado a:</strong> ${tarea.asignado_a}<br>
                    <strong>Prioridad:</strong> ${tarea.prioridad}<br>
                    <strong>Estado:</strong> ${tarea.estado}
                `;
                document.getElementById('tareaFecha').textContent = formatearFecha(tarea.fecha_limite);
                
                const modal = new bootstrap.Modal(document.getElementById('verTareaModal'));
                modal.show();
            } else {
                mostrarAlerta(data.error, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error al obtener la tarea', 'error');
        });
}

// Editar tarea
function editarTarea(id) {
    fetch(tareasBaseUrl + 'tareas/obtener/' + id)
        .then(async response => {
            const text = await response.text();
            try { return JSON.parse(text); } catch { throw new Error('Respuesta no válida: ' + text); }
        })
        .then(data => {
            if (data.success) {
                const tarea = data.data;
                tareaEditandoId = id;
                
                document.getElementById('titulo').value = tarea.titulo;
                document.getElementById('asignado_a').value = tarea.asignado_a;
                document.getElementById('fecha_limite').value = tarea.fecha_limite;
                document.getElementById('prioridad').value = tarea.prioridad;
                document.getElementById('estado').value = tarea.estado;
                const tel = document.getElementById('telefono_asignado'); if (tel) tel.value = tarea.telefono || '';
                
                document.querySelector('#nuevaTareaModal .modal-title').textContent = 'Editar Tarea';
                document.querySelector('#nuevaTareaModal button[type="submit"]').textContent = 'Actualizar Tarea';
                
                const modal = new bootstrap.Modal(document.getElementById('nuevaTareaModal'));
                modal.show();
            } else {
                mostrarAlerta(data.error, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error al obtener la tarea', 'error');
        });
}

// Eliminar tarea
async function eliminarTarea(id) {
    const confirmado = await (async () => {
        if (window.Swal && Swal.fire) {
            const res = await Swal.fire({
                icon: 'warning',
                title: '¿Eliminar tarea?',
                text: 'Esta acción no se puede deshacer',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });
            return res.isConfirmed;
        }
        // Evitar confirm nativo; en ausencia de Swal, cancelar
        return false;
    })();

    if (!confirmado) return;

    const formData = new FormData();
    formData.append('id', id);

    fetch(tareasBaseUrl + 'tareas/eliminar', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        const text = await response.text();
        try { return JSON.parse(text); } catch { throw new Error('Respuesta no válida: ' + text); }
    })
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'success');
            cargarTareas();
        } else {
            mostrarAlerta(data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarAlerta('Error al eliminar la tarea', 'error');
    });
}
// Abrir modal de WhatsApp
function abrirModalWhatsApp(nombreDestinatario, telefono) {
    document.getElementById('whatsapp_nombre').value = nombreDestinatario;
    document.getElementById('whatsapp_telefono').value = telefono || '';
    
    const modal = new bootstrap.Modal(document.getElementById('whatsappModal'));
    modal.show();
}

// Enviar mensaje de WhatsApp
async function enviarWhatsApp() {
    const nombre = document.getElementById('whatsapp_nombre').value.trim();
    const telefono = document.getElementById('whatsapp_telefono').value.trim();
    const mensaje = document.getElementById('whatsapp_mensaje').value.trim();

    if (!nombre || !telefono || !mensaje) {
        mostrarAlerta('Complete todos los campos', 'error');
        return;
    }

    // Validar formato de teléfono (debe empezar con +51 para Perú)
    const telefonoFormateado = telefono.startsWith('+51') ? telefono : '+51' + telefono;

    const submitBtn = document.querySelector('#whatsappModal button[onclick="enviarWhatsApp()"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Enviando...';
    }

    try {
        const formData = new URLSearchParams();
        formData.append('nombre', nombre);
        formData.append('telefono', telefonoFormateado);
        formData.append('mensaje', mensaje);

        console.log('📤 Enviando a:', `${tareasBaseUrl}tareas/enviarWhatsApp`);
        console.log('📋 Datos:', { nombre, telefono: telefonoFormateado, mensaje });

        const response = await fetch(`${tareasBaseUrl}tareas/enviarWhatsApp`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        });

        const result = await response.json();
        console.log('📥 Respuesta del servidor:', result);

        if (result.success) {
            mostrarExito(result.message || 'Mensaje enviado exitosamente');
            cerrarModalWhatsApp();
            const form = document.getElementById('whatsappForm');
            if (form) form.reset();
        } else {
            let err = result.error || 'Error al enviar el mensaje';
            if (result.http_code) err += ` (Código: ${result.http_code})`;
            mostrarAlerta(err, 'error');
        }
    } catch (error) {
        console.error('❌ Error de red:', error);
        mostrarAlerta('Error al enviar el mensaje de WhatsApp', 'error');
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-whatsapp me-1"></i>Enviar Mensaje';
        }
    }
}

// Resetear modal al cerrar
document.getElementById('nuevaTareaModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('tareaForm').reset();
    tareaEditandoId = null;
    document.querySelector('#nuevaTareaModal .modal-title').textContent = 'Nueva Tarea';
    document.querySelector('#nuevaTareaModal button[type="submit"]').textContent = 'Añadir Tarea';
});

// Función auxiliar para formatear fechas
function formatearFecha(fecha) {
    const opciones = { year: 'numeric', month: '2-digit', day: '2-digit' };
    return new Date(fecha + 'T00:00:00').toLocaleDateString('es-PE', opciones);
}

// Función para mostrar alertas (SweetAlert2) sin alert nativo
function mostrarAlerta(mensaje, tipo) {
    const texto = mensaje || (tipo === 'success' ? 'Operación realizada correctamente' : 'Ocurrió un error');
    if (window.Swal && Swal.fire) {
        Swal.fire({
            icon: tipo === 'success' ? 'success' : (tipo === 'info' ? 'info' : 'error'),
            title: tipo === 'success' ? 'Operación exitosa' : (tipo === 'info' ? 'Información' : 'Ocurrió un error'),
            text: texto,
            confirmButtonText: 'OK'
        });
    } else {
        console.log(`[${(tipo||'info').toUpperCase()}] ${texto}`);
    }
}

// Resetear modal al cerrar
document.getElementById('nuevaTareaModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('tareaForm').reset();
    tareaEditandoId = null;
    document.querySelector('#nuevaTareaModal .modal-title').textContent = 'Nueva Tarea';
    document.querySelector('#nuevaTareaModal button[type="submit"]').textContent = 'Añadir Tarea';
});

// Función auxiliar para formatear fechas