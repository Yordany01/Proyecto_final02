<!-- titulo -->
<?= $this->section('titulo'); ?>
<div class="app-hero-header d-flex align-items-center">
          <div class="d-flex align-items-center">
            <div class="me-3 icon-box md bg-white rounded-4">
              <i class="bi bi-speedometer2 fs-3 text-primary"></i>
            </div>
            <div>
              <h2 class="mb-1">Panel Empleado
              </h2>
              <small>Resumen personal</small>
            </div>
          </div>
        </div>
<?= $this->endSection() ?>

<!-- contenido  -->
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
        <!-- Gráficos -->
        <div class="row gx-4">
          <div class="col-lg-8 col-md-12">
            <div class="card my-2 card-dark">
              <div class="card-header">
                <h5 class="card-title text-white">Resumen de Tareas Semanal</h5>
              </div>
              <div class="card-body">
                <div class="bg-white rounded-2 p-3">
                  <canvas id="tasksChart"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-12">
            <div class="card my-2 card-dark">
              <div class="card-header">
                <h5 class="card-title text-white">Distribución de Proyectos</h5>
              </div>
              <div class="card-body">
                <div class="bg-white rounded-2 p-3">
                  <canvas id="projectsChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
<?= $this->endSection() ?>


<!-- Gráficos -->
<?= $this->section('scripts') ?>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Gráfico de Tareas Semanal (Barras)
    const tasksCtx = document.getElementById('tasksChart').getContext('2d');
    new Chart(tasksCtx, {
      type: 'bar',
      data: {
        labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
        datasets: [{ label: 'Tareas completadas', data: [5, 8, 3, 6, 4], backgroundColor: 'rgba(75, 192, 192, 0.5)' }]
      },
      options: {
        animation: false
      }
    });

    // Gráfico de Proyectos (Barras)
    const projectsCtx = document.getElementById('projectsChart').getContext('2d');
    new Chart(projectsCtx, {
      type: 'bar',
      data: {
        labels: ['Proyecto Alpha', 'Proyecto Beta', 'Proyecto Gamma'],
        datasets: [{ label: 'Horas dedicadas', data: [40, 25, 15], backgroundColor: ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)'] }]
      },
      options: {
        animation: false,
        indexAxis: 'y' // Para hacerlo horizontal y más legible
      }
    });
  });
</script>
<?= $this->endSection() ?>

<!-- plantilla general -->
<?= $this->extend('dashboard_empleado/template02') ?>