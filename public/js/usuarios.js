let tablaUsuarios;
const usuariosBaseUrl = typeof baseURL !== 'undefined' ? baseURL : (window.location.origin + '/');

$(document).ready(function () {
  if (!document.getElementById('usuariosTable')) return;
  inicializarDataTableUsuarios();
  cargarKpisUsuarios();
  document.getElementById('saveUserBtn')?.addEventListener('click', guardarUsuario);
});

function notify(type, text, title) {
  if (typeof Swal !== 'undefined') {
    Swal.fire({
      icon: type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'error',
      title: title || (type === 'success' ? 'Éxito' : type === 'warning' ? 'Atención' : 'Error'),
      text: text || '',
      confirmButtonText: 'Aceptar'
    });
  } else {
    alert(text || title || '');
  }
}

function inicializarDataTableUsuarios() {
  if ($.fn && $.fn.dataTable) { $.fn.dataTable.ext.errMode = 'none'; }
  tablaUsuarios = $('#usuariosTable').DataTable({
    destroy: true,
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
    lengthChange: true,
    autoWidth: false,
    responsive: true,
    order: [[0, 'asc']],
    ajax: {
      method: 'GET',
      url: usuariosBaseUrl + 'usuarios/listar',
      dataSrc: 'data'
    },
    columns: [
      { data: null, render: (data, type, row) => pickField(row, ['nombre','nombres','usuario','name']) || '-' },
      { data: null, render: (data, type, row) => renderEstadoUsuario(pickField(row, ['estado','user_estado','estatus']) || '') },
      { data: null, render: (data, type, row) => pickField(row, ['email','correo','correo_electronico']) || '-' },
      { data: null, render: (data, type, row) => pickField(row, ['telefono','celular','phone']) || '-' },
      {
        data: null,
        orderable: false,
        width: '12%',
        render: (data) => `
          <div class="btn-group">
            <button type="button" onclick="editarUsuario(${data.idusuario || data.id || ''})" class="btn btn-warning btn-sm" title="Editar">
              <i class="bi bi-pencil"></i>
            </button>
            <button type="button" onclick="eliminarUsuario(${data.idusuario || data.id || ''})" class="btn btn-danger btn-sm" title="Eliminar">
              <i class="bi bi-trash"></i>
            </button>
            <button type="button" class="btn btn-success btn-sm" title="WhatsApp" onclick="abrirModalWhatsAppUsuario('${(pickField(data,['nombre','nombres','usuario','name'])||'').replace(/"/g,'&quot;')}','${(pickField(data,['telefono','celular','phone'])||'').replace(/"/g,'&quot;')}')">
              <i class="bi bi-whatsapp"></i>
            </button>
          </div>`
      }
    ],
  });
  tablaUsuarios.on('draw', syncKpisUsuariosDesdeTabla);
  $('#usuariosTable').on('error.dt', function (e, settings, techNote, message) {
    notify('error', 'No se pudo cargar el listado de usuarios.');
  });
}

function renderEstadoUsuario(data) {
  const val = (data || '').toString().toLowerCase();
  if (val === 'activo') return '<span class="badge bg-success">Activo</span>';
  if (val === 'inactivo') return '<span class="badge bg-secondary">Inactivo</span>';
  if (val === 'restringido') return '<span class="badge bg-danger">Restringido</span>';
  return data || '-';
}

function cargarKpisUsuarios() {
  // Si hay tabla cargada, preferir sincronizar desde la tabla
  if (syncKpisUsuariosDesdeTabla()) return;
  fetch(usuariosBaseUrl + 'usuarios/kpis')
    .then(r => r.json())
    .then(({ total, activos, inactivos, restringidos }) => {
      const t = document.getElementById('kpi-total'); if (t) t.textContent = total ?? 0;
      const a = document.getElementById('kpi-activos'); if (a) a.textContent = activos ?? 0;
      const i = document.getElementById('kpi-inactivos'); if (i) i.textContent = inactivos ?? 0;
      const r2 = document.getElementById('kpi-restringidos'); if (r2) r2.textContent = restringidos ?? 0;
    })
    .catch(() => {});
}

function syncKpisUsuariosDesdeTabla() {
  if (!tablaUsuarios || !$.fn.DataTable) return false;
  const data = tablaUsuarios.column(1, { search: 'applied' }).data().toArray();
  const total = tablaUsuarios.rows({ filter: 'applied' }).count();
  let act = 0, ina = 0, res = 0;
  data.forEach(txt => {
    const s = txt.replace(/<[^>]*>/g,'').trim().toLowerCase();
    if (s.includes('activo')) act++; else if (s.includes('inactivo')) ina++; else if (s.includes('restringido')) res++;
  });
  const t = document.getElementById('kpi-total'); if (t) t.textContent = total;
  const a = document.getElementById('kpi-activos'); if (a) a.textContent = act;
  const i = document.getElementById('kpi-inactivos'); if (i) i.textContent = ina;
  const r2 = document.getElementById('kpi-restringidos'); if (r2) r2.textContent = res;
  return true;
}

function guardarUsuario() {
  const id = document.getElementById('userId').value.trim();
  const email = document.getElementById('userEmail').value.trim();
  const pass = document.getElementById('userPassword').value;
  const pass2 = document.getElementById('userConfirmPassword').value;
  const rol = document.getElementById('userRole').value;
  const estado = document.getElementById('userStatus').value;
  if (!email || !rol || !estado || (!id && !pass)) { notify('warning','Complete los campos obligatorios'); return; }
  if (!id && pass.length < 8) { notify('warning','La contraseña debe tener al menos 8 caracteres'); return; }
  if (pass || pass2) { if (pass !== pass2) { document.getElementById('passwordError').classList.remove('d-none'); return; } }
  const fd = new FormData();
  if (id) fd.append('id', id);
  fd.append('email', email);
  if (pass) fd.append('password', pass);
  fd.append('rol', rol);
  fd.append('estado', estado);
  fetch(usuariosBaseUrl + (id ? 'usuarios/actualizar' : 'usuarios/insertar'), { method: 'POST', body: fd })
    .then(r => r.json())
    .then(resp => {
      if (resp.success) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
        modal?.hide();
        tablaUsuarios?.ajax?.reload(null, false);
        cargarKpisUsuarios();
        notify('success','Usuario guardado correctamente');
      } else { notify('error', resp.error || 'No se pudo guardar'); }
    })
    .catch(() => notify('error','Error de red'));
}

function editarUsuario(id) {
  fetch(usuariosBaseUrl + 'usuarios/obtener/' + id)
    .then(r => r.json())
    .then(resp => {
      if (!resp.success) return notify('error', resp.error || 'No encontrado');
      const u = resp.data;
      document.getElementById('userId').value = u.id;
      document.getElementById('userEmail').value = u.email || '';
      document.getElementById('userPassword').value = '';
      document.getElementById('userConfirmPassword').value = '';
      document.getElementById('userRole').value = u.rol || '';
      document.getElementById('userStatus').value = u.estado || 'Activo';
      document.getElementById('userModalLabel').textContent = 'Editar Usuario';
      new bootstrap.Modal(document.getElementById('userModal')).show();
    })
    .catch(() => notify('error','Error al obtener usuario'));
}

async function eliminarUsuario(id) {
  const confirmado = window.Swal && Swal.fire ? await Swal.fire({
    icon:'warning', title:'¿Eliminar usuario?', text:'Esta acción no se puede deshacer', showCancelButton:true,
    confirmButtonText:'Sí, eliminar', cancelButtonText:'Cancelar', confirmButtonColor:'#d33'
  }).then(r=>r.isConfirmed) : confirm('¿Eliminar usuario?');
  if (!confirmado) return;
  const fd = new FormData(); fd.append('id', id);
  fetch(usuariosBaseUrl + 'usuarios/eliminar', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(resp => {
      if (resp.success) { tablaUsuarios?.ajax?.reload(null, false); cargarKpisUsuarios(); notify('success','Usuario eliminado'); }
      else notify('error', resp.error || 'No se pudo eliminar');
    })
    .catch(() => notify('error','Error eliminando'));
}

function abrirModalWhatsAppUsuario(nombre, telefono) {
  const n = document.getElementById('whatsappu_nombre'); if (n) n.value = nombre || '';
  const t = document.getElementById('whatsappu_telefono'); if (t) t.value = telefono || '';
  new bootstrap.Modal(document.getElementById('whatsappUsuarioModal')).show();
}

async function enviarWhatsAppUsuario() {
  const nombre = document.getElementById('whatsappu_nombre').value.trim();
  const telefono = document.getElementById('whatsappu_telefono').value.trim();
  const mensaje = document.getElementById('whatsappu_mensaje').value.trim();
  if (!telefono || !mensaje) { notify('warning','Complete teléfono y mensaje'); return; }
  const params = new URLSearchParams({ nombre, telefono, mensaje });
  const btn = document.querySelector('#whatsappUsuarioModal button.btn-success');
  if (btn) { btn.disabled = true; btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Enviando...'; }
  try {
    const res = await fetch(usuariosBaseUrl + 'usuarios/enviarWhatsApp', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: params });
    const json = await res.json();
    if (json.success) {
      notify('success', json.message || 'Mensaje enviado');
      bootstrap.Modal.getInstance(document.getElementById('whatsappUsuarioModal'))?.hide();
      document.getElementById('whatsappUsuarioForm')?.reset();
    } else { notify('error', json.error || 'No se pudo enviar'); }
  } catch(e) {
    notify('error','Error al enviar');
  } finally {
    if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-whatsapp me-1"></i>Enviar'; }
  }
}
