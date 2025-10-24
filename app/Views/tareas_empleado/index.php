
<!-- plantilla general -->
<?= $this->extend('dashboard_empleado/template02') ?>


<!-- titulo -->
<?= $this->section('titulo'); ?>
<div class="app-hero-header d-flex align-items-center">
    <div class="d-flex align-items-center">
        <div class="me-3 icon-box md bg-white rounded-4">
            <i class="bi bi-list-task fs-3 text-primary"></i>
        </div>
        <div>
            <h2 class="mb-1">Tareas Empleado</h2>
            <small>Resumen de tus tareas</small>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<!-- contenido central -->
<?= $this->section('content'); ?>
  <!-- KPIs (3 tarjetas centradas) -->
        <div class="mx-n4 p-4 bg-light mb-4">
          <div class="row gx-4 justify-content-center">
            <div class="col-xl-3 col-md-4 col-sm-6">
              <div class="card my-2 card-info">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-check2-square me-2"></i>Tareas Pendientes</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-tasks">0</h1>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6">
              <div class="card my-2 card-danger">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-file-earmark-text me-2"></i>Mis Documentos</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-docs">0</h1>
                </div>
              </div>
            </div>
          </div>
        </div>

       <!-- Tabla moderna de tareas -->
<div class="card card-glass mb-4">
  <div class="card-header bg-transparent d-flex justify-content-between align-items-center flex-wrap">
    <h5 class="card-title mb-0 text-primary">Mis Tareas Asignadas</h5>
    <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
      <i class="bi bi-arrow-clockwise me-1"></i>Actualizar
    </button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-modern align-middle mb-0">
        <thead>
          <tr>
            <th>Título</th>
            <th>Descripción</th>
            <th>Fecha Límite</th>
            <th>Prioridad</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
<?= $this->endSection() ?>