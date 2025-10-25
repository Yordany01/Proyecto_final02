// Dashboard JS
const dashBase = typeof baseURL !== 'undefined' ? baseURL : (window.location.origin + '/');
let filtros = { from: '', to: '', asignado: '' };
let charts = { rendimiento: null, estado: null };

function setText(id, val) {
  const el = document.getElementById(id);
  if (el) el.textContent = val ?? 0;
}

function showError(title, msg) {
  if (typeof Swal !== 'undefined') Swal.fire({ icon:'error', title: title || 'Error', text: msg || 'Ha ocurrido un error.' });
  else console.error(`${title||'Error'}: ${msg||''}`);
}

function qs() {
  const p = new URLSearchParams();
  if (filtros.from) p.append('from', filtros.from);
  if (filtros.to) p.append('to', filtros.to);
  if (filtros.asignado) p.append('asignado', filtros.asignado);
  const s = p.toString();
  return s ? ('?' + s) : '';
}

async function cargarKpis() {
  try {
    setText('kpi-tareas', '...');
    setText('kpi-pendientes', '...');
    setText('kpi-completados', '...');
    setText('kpi-empleados', '...');
    const res = await fetch(`${dashBase}dashboard/kpis${qs()}`);
    if (!res.ok) throw new Error('No se pudo obtener KPIs');
    const r = await res.json();
    if (r.success && r.data) {
      setText('kpi-tareas', r.data.total_tareas);
      setText('kpi-pendientes', r.data.pendientes);
      setText('kpi-completados', r.data.completados);
      setText('kpi-empleados', r.data.empleados);
    } else {
      showError('KPIs', r.error || 'No se pudo obtener KPIs');
    }
  } catch (e) { console.error('KPIs:', e); showError('KPIs', e.message); }
}

function colores(n) {
  const pal = ['#ef4444','#3b82f6','#10b981','#f59e0b','#8b5cf6','#06b6d4'];
  return Array.from({length:n}, (_,i)=> pal[i % pal.length]);
}

async function graficoRendimiento() {
  try {
    const res = await fetch(`${dashBase}dashboard/rendimiento${qs()}`);
    if (!res.ok) throw new Error('No se pudo obtener rendimiento');
    const r = await res.json();
    const labels = (r.data||[]).map(x=>x.asignado_a||'N/D');
    const data = (r.data||[]).map(x=>Number(x.total||0));
    const ctx = document.getElementById('employeePerformanceChart');
    if (!ctx) return;
    if (charts.rendimiento) { charts.rendimiento.destroy(); charts.rendimiento = null; }
    charts.rendimiento = new Chart(ctx, {
      type: 'bar',
      data: { labels, datasets: [{ label: 'Tareas', data, backgroundColor: colores(data.length) }]},
      options: { responsive: true, plugins:{ legend:{ display:false }}, animation:{ duration: 800 } }
    });
  } catch (e) { console.error('Rendimiento:', e); showError('Rendimiento de Empleados', e.message); }
}

async function graficoEstado() {
  try {
    const res = await fetch(`${dashBase}dashboard/estado${qs()}`);
    if (!res.ok) throw new Error('No se pudo obtener estado');
    const r = await res.json();
    const labels = (r.data||[]).map(x=> (x.estado||'').toUpperCase());
    const data = (r.data||[]).map(x=>Number(x.total||0));
    const ctx = document.getElementById('projectStatusChart');
    if (!ctx) return;
    if (charts.estado) { charts.estado.destroy(); charts.estado = null; }
    charts.estado = new Chart(ctx, {
      type: 'doughnut',
      data: { labels, datasets: [{ data, backgroundColor: colores(data.length) }]},
      options: { responsive: true, plugins:{ legend:{ position:'bottom' }}, animation:{ animateScale: true } }
    });
  } catch (e) { console.error('Estado:', e); showError('Estado de Proyectos', e.message); }
}

// (Se removió gráfico financiero)

async function cargarResponsables() {
  try {
    const res = await fetch(`${dashBase}dashboard/responsables${qs()}`);
    const r = await res.json();
    const sel = document.getElementById('filtroAsignado');
    if (sel && r.success) {
      // preservar opción "Todos"
      sel.innerHTML = '<option value="">Todos</option>' + (r.data||[]).map(x => `<option>${(x.nombre||'').trim()}</option>`).join('');
    }
  } catch(e) { console.error('Responsables:', e); }
}

function leerUIFiltros() {
  const from = (document.getElementById('filtroFrom')?.value || '').trim();
  const to = (document.getElementById('filtroTo')?.value || '').trim();
  const asignado = (document.getElementById('filtroAsignado')?.value || '').trim();
  filtros = { from, to, asignado };
}

function validarRango() {
  if (!filtros.from || !filtros.to) return true; // permitir si alguno vacío
  const a = new Date(filtros.from);
  const b = new Date(filtros.to);
  const ok = !isNaN(a) && !isNaN(b) && a.getTime() <= b.getTime();
  if (!ok) {
    const msg = 'El rango de fechas es inválido. "Desde" debe ser menor o igual que "Hasta".';
    if (typeof Swal !== 'undefined') Swal.fire({ icon:'warning', title:'Rango inválido', text: msg }); else console.warn(msg);
  }
  return ok;
}

function guardarFiltros() {
  try { localStorage.setItem('dashboard_filters', JSON.stringify(filtros)); } catch(_) {}
}

function cargarFiltrosGuardados() {
  try {
    const raw = localStorage.getItem('dashboard_filters');
    if (!raw) return;
    const obj = JSON.parse(raw);
    if (obj && typeof obj === 'object') filtros = { from: obj.from||'', to: obj.to||'', asignado: obj.asignado||'' };
    const fromEl = document.getElementById('filtroFrom');
    const toEl = document.getElementById('filtroTo');
    const asEl = document.getElementById('filtroAsignado');
    if (fromEl) fromEl.value = filtros.from || '';
    if (toEl) toEl.value = filtros.to || '';
    if (asEl) asEl.value = filtros.asignado || '';
  } catch(_) {}
}

async function aplicarFiltros() {
  leerUIFiltros();
  if (!validarRango()) return;
  guardarFiltros();
  await cargarKpis();
  await graficoRendimiento();
  await graficoEstado();
}

document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('btnAplicarFiltros');
  if (btn) btn.addEventListener('click', aplicarFiltros);
  cargarFiltrosGuardados();
  cargarResponsables().then(() => {
    // Reaplicar opción de responsable si existe tras poblar el select
    const asEl = document.getElementById('filtroAsignado');
    if (asEl && filtros.asignado) asEl.value = filtros.asignado;
    aplicarFiltros();
  });
});
