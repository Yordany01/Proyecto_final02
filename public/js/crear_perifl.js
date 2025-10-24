// JS para página de Perfil (solo lectura)
(function () {
  const baseUrl = typeof baseURL !== 'undefined' ? baseURL : (window.location.origin + '/');

  document.addEventListener('DOMContentLoaded', () => {
    const esPaginaPerfil = document.getElementById('perfil_email') || document.getElementById('perfilNombreCompleto');
    
    if (!esPaginaPerfil) return;

    cargarDatosPerfil();
    
    // Actualizar cada 5 segundos por si se edita en otra pestaña
    setInterval(cargarDatosPerfil, 5000);
  });

  async function cargarDatosPerfil() {
    try {
      const resp = await fetch(baseUrl + 'perfil/obtener');
      const result = await resp.json();
      
      if (result && result.success && result.data) {
        actualizarVistaPerfil(result.data);
      }
    } catch (e) {
      console.error('Error al cargar perfil:', e);
    }
  }

  function actualizarVistaPerfil(data) {
    // Nombre completo
    const nombreCompleto = [data.nombre, data.apellidos].filter(Boolean).join(' ') || '-';
    setText('perfilNombreCompleto', nombreCompleto);
    
    // Datos individuales
    setText('perfil_dni', data.dni || '-');
    setText('perfil_email', data.email || '-');
    setText('perfil_telefono', data.telefono || '-');
    setText('perfil_direccion', data.direccion || '-');
    setText('perfil_fecha', formatearFecha(data.fecha_nacimiento) || '-');
    
    // Foto de perfil
    const img = document.getElementById('perfil_foto');
    if (img && data.foto_url) {
      img.src = data.foto_url;
    }
  }

  function setText(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val || '-';
  }

  function formatearFecha(fecha) {
    if (!fecha) return '';
    try {
      const date = new Date(fecha);
      return date.toLocaleDateString('es-PE', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      });
    } catch {
      return fecha;
    }
  }
})();