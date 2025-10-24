<?= $this->extend('dashboard/template') ?>


<?= $this->section('titulo'); ?>
 <div class="app-hero-header d-flex align-items-center">
          <div class="d-flex align-items-center">
            <div class="me-3 icon-box md bg-white rounded-4">
              <i class="bi bi-pie-chart fs-3 text-primary"></i>
            </div>
            <div>
              <h2 class="mb-1">Panel del Administrador</h2>
              <small>Resumen general del sistema</small>
            </div>
          </div>
          <div class="ms-auto d-lg-flex d-none flex-row"><!-- botones opcionales --></div>
        </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script src="<?= base_url('public/js/dashboard.js') ?>"></script>
<?= $this->endSection() ?>


<?= $this->section('content'); ?>
<div class="mx-n4 p-4 bg-light mb-4">
          <div class="card mb-3">
            <div class="card-body">
              <div class="row g-3 align-items-end">
                <div class="col-sm-4 col-md-3">
                  <label class="form-label">Desde</label>
                  <input type="date" class="form-control" id="filtroFrom">
                </div>
                <div class="col-sm-4 col-md-3">
                  <label class="form-label">Hasta</label>
                  <input type="date" class="form-control" id="filtroTo">
                </div>
                <div class="col-sm-8 col-md-4">
                  <label class="form-label">Responsable</label>
                  <select class="form-select" id="filtroAsignado">
                    <option value="">Todos</option>
                  </select>
                </div>
                <div class="col-sm-4 col-md-2">
                  <button id="btnAplicarFiltros" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Aplicar</button>
                </div>
              </div>
            </div>
          </div>
          <div class="row gx-4">
            <div class="col-xl-3 col-sm-6 col-12">
              <div class="card my-2 card-danger">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-bar-chart me-2"></i>Total Tareas</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-tareas">0</h1>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
              <div class="card my-2 card-info">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-clock me-2"></i>Pendientes</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-pendientes">0</h1>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
              <div class="card my-2 card-success">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-check-circle me-2"></i>Completados</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-completados">0</h1>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
              <div class="card my-2 card-warning">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-people me-2"></i>Empleados</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-empleados">0</h1>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Gráficos Avanzados -->
        <div class="row gx-4 justify-content-center">
          <div class="col-lg-6 col-md-6">
            <div class="card my-2 card-dark">
              <div class="card-header">
                <h5 class="card-title text-white">Rendimiento de Empleados</h5>
              </div>
              <div class="card-body">
                <div class="bg-white rounded-2 p-3">
                  <canvas id="employeePerformanceChart"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-6">
            <div class="card my-2 card-dark">
              <div class="card-header">
                <h5 class="card-title text-white">Estado de Proyectos</h5>
              </div>
              <div class="card-body">
                <div class="bg-white rounded-2 p-3">
                  <canvas id="projectStatusChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

  
<?= $this->endSection() ?>
