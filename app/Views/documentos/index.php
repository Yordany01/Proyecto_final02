<!-- titulo -->
<?= $this->section('titulo'); ?>
 <!-- Hero -->
 <div class="app-hero-header d-flex align-items-center">
          <div class="d-flex align-items-center">
            <div class="me-3 icon-box md bg-white rounded-4">
              <i class="bi bi-file-earmark-text fs-3 text-primary"></i>
            </div>
            <div>
              <h2 class="mb-1">Administración de Documentos</h2>
              <small>Gestión de archivos, estados y equipos</small>
            </div>
          </div>
        </div>
        <!-- /Hero -->
<?= $this->endSection() ?>



<!-- contenido -->
<?= $this->section('content'); ?>
<!-- Contenido principal -->

          <!-- Hero Section -->

          <!-- KPI cards (solo 3 y centrados) -->
        <div class="mx-n4 p-4 bg-light mb-4">
          <div class="row gx-4 justify-content-center">
            <div class="col-xl-3 col-md-4 col-sm-6">
              <div class="card my-2 card-info">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-file-earmark me-2"></i>Total Documentos</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-total">0</h1>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6">
              <div class="card my-2 card-success">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-check2-circle me-2"></i>Activos</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-activos">0</h1>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6">
              <div class="card my-2 card-warning">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-clock-history me-2"></i>Pendientes</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-pendientes">0</h1>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabla -->
        <div class="row gx-4">
          <div class="col-12">
            <div class="card my-2 card-dark">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title text-white m-0">Listado de Documentos</h5>
              </div>
              <div class="card-body">
                <div class="bg-white rounded-2 p-2">
                  <div class="table-responsive">
                    <table id="documentosTable" class="table align-middle">
                      <thead>
                        <tr>
                          <th>Nombre del Proyecto</th>
                          <th>Compañia</th>
                          <th>Estado</th>
                          <th>Equipo</th>
                          <th>Fecha de Inicio</th>
                          <th>Archivo</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- filas generadas por datatable / backend -->
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

       
<?= $this->endSection() ?>



<!-- plantilla principal -->
<?= $this->extend('dashboard/template') ?>