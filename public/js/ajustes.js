// JS para página de Ajustes (Administrador)
// Carga, guarda y sincroniza datos con la vista de Perfil si está presente

(function () {
  const baseUrl = typeof baseURL !== 'undefined' ? baseURL : (window.location.origin + '/');

  document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('adminDataForm');
    const esPaginaAjustes = !!form;
    const tienePerfil =
      document.getElementById('perfilNombre') ||
      document.getElementById('perfil_email') ||
      document.getElementById('perfilNombreCompleto') ||
      document.getElementById('firstName') ||
      document.getElementById('avatarPreview');

    if (!esPaginaAjustes && !tienePerfil) return;

    cargarDatos().then((data) => {
      if (esPaginaAjustes) rellenarFormulario(data);
      if (tienePerfil) actualizarPerfil(data);
    });

    if (esPaginaAjustes) {
      prepararEventos(form);
    }
  });

  async function cargarDatos() {
    try {
      const resp = await fetch(baseUrl + 'ajustes/obtener');
      const text = await resp.text();
      try {
        return JSON.parse(text).data || {};
      } catch {
        return {};
      }
    } catch (e) {
      console.error('No se pudieron cargar ajustes:', e);
      return {};
    }
  }

  function rellenarFormulario(data) {
    setVal('adminFirstName', data.nombre);
    setVal('adminLastName', data.apellidos);
    setVal('adminDni', data.dni);
    setVal('adminEmail', data.email);
    setVal('adminPhone', data.telefono);
    setVal('adminAddress', data.direccion);
    setVal('adminBirthdate', data.fecha_nacimiento);
    if (data.foto_url) {
      const prev = document.getElementById('preview_foto');
      if (prev) {
        prev.src = data.foto_url;
        prev.classList.remove('d-none');
      }
    }
  }

  function prepararEventos(form) {
    // Vista previa de imagen
    const inputFoto = document.getElementById('adminAvatarFile');
    if (inputFoto) {
      inputFoto.addEventListener('change', (e) => {
        const file = e.target.files && e.target.files[0];
        if (!file) return;
        const url = URL.createObjectURL(file);
        const prev = document.getElementById('preview_foto');
        if (prev) {
          prev.src = url;
          prev.classList.remove('d-none');
        }
      });
    }

    // Guardar
    const btnGuardar = document.getElementById('saveAdminDataBtn') || form.querySelector('button[type="submit"]');
    if (btnGuardar) {
      btnGuardar.addEventListener('click', (e) => {
        e.preventDefault();
        guardar(form);
      });
    }

    // Restablecer
    const btnReset = document.getElementById('resetAdminDataBtn');
    if (btnReset) {
      btnReset.addEventListener('click', async (e) => {
        e.preventDefault();
        const data = await cargarDatos();
        rellenarFormulario(data);
      });
    }
  }

  async function guardar(form) {
    const nombre = getVal('adminFirstName').trim();
    const email = getVal('adminEmail').trim();
    if (!nombre || !email) {
      alert('Nombre y Email son obligatorios');
      return;
    }

    const fd = new FormData();
    fd.append('nombre', nombre);
    fd.append('apellidos', getVal('adminLastName'));
    fd.append('dni', getVal('adminDni'));
    fd.append('email', email);
    fd.append('telefono', getVal('adminPhone'));
    fd.append('direccion', getVal('adminAddress'));
    fd.append('fecha_nacimiento', getVal('adminBirthdate'));

    const fileInput = document.getElementById('adminAvatarFile');
    if (fileInput && fileInput.files && fileInput.files[0]) {
      fd.append('foto', fileInput.files[0]);
    }

    const btnGuardar = document.getElementById('saveAdminDataBtn') || form.querySelector('button[type="submit"]');
    const original = btnGuardar ? btnGuardar.innerHTML : '';
    if (btnGuardar) {
      btnGuardar.disabled = true;
      btnGuardar.innerHTML = 'Guardando...';
    }

    try {
      const resp = await fetch(baseUrl + 'ajustes/guardar', { method: 'POST', body: fd });
      const text = await resp.text();
      const json = (() => {
        try {
          return JSON.parse(text);
        } catch {
          return {};
        }
      })();
      if (json && json.success) {
        mostrarOk(json.message || 'Guardado');
        actualizarPerfil(json.data || {});
      } else {
        mostrarError((json && (json.error || json.message)) || 'No se pudo guardar');
      }
    } catch (e) {
      console.error('Error al guardar ajustes:', e);
      mostrarError('Error al guardar');
    } finally {
      if (btnGuardar) {
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = original || 'Guardar';
      }
    }
  }

  // 🔹 Función unificada y extendida de actualización del perfil
  function actualizarPerfil(data) {
    // Textos o etiquetas simples
    setText('perfilNombre', data.nombre);
    setText('perfilApellidos', data.apellidos);
    setText('perfil_dni', data.dni);
    setText('perfil_email', data.email);
    setText('perfil_telefono', data.telefono);
    setText('perfil_direccion', data.direccion);
    setText('perfil_fecha', data.fecha_nacimiento);
    setText('perfilNombreCompleto', [data.nombre, data.apellidos].filter(Boolean).join(' '));

    // Inputs deshabilitados (modo edición)
    setVal('firstName', data.nombre);
    setVal('lastName', data.apellidos);
    setVal('dni', data.dni);
    setVal('email', data.email);
    setVal('phone', data.telefono);
    setVal('address', data.direccion);
    setVal('birthdate', data.fecha_nacimiento);

    // Imagen de perfil
    const img =
      document.getElementById('perfil_foto') ||
      document.getElementById('avatarPreview') ||
      document.getElementById('preview_foto');
    if (img && data.foto_url) {
      img.src = data.foto_url;
      img.classList.remove('d-none');
    }
  }

  // Helpers
  function setVal(id, val) {
    const el = document.getElementById(id);
    if (el) el.value = val || '';
  }
  function getVal(id) {
    const el = document.getElementById(id);
    return el ? el.value || '' : '';
  }
  function setText(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val || '';
  }

  function mostrarOk(msg) {
    if (window.Swal) {
      Swal.fire('Éxito', msg, 'success');
    } else {
      alert(msg);
    }
  }
  function mostrarError(msg) {
    if (window.Swal) {
      Swal.fire('Error', msg, 'error');
    } else {
      alert(msg);
    }
  }
})();
