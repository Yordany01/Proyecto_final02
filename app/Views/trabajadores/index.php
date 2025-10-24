<!-- seccion del titulo -->
<?= $this->section('titulo'); ?>
<section class="app-hero-header d-flex align-items-center">
            <div class="d-flex align-items-center">
              <div class="me-3 icon-box md bg-white rounded-4">
                <i class="bi bi-people fs-3 text-primary" aria-hidden="true"></i>
              </div>
              <div>
                <h1 class="mb-1">Gestión de Empleados</h1>
                <p class="mb-0 text-muted">Altas, edición y control del personal</p>
              </div>
            </div>

            <div class="ms-auto d-lg-flex d-none flex-row">
              <div class="d-flex flex-row gap-2 flex-wrap">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addNewContact">
                  <i class="bi bi-person-plus me-1" aria-hidden="true"></i>Añadir Trabajador
                </button>
              </div>
            </div>
          </section>
<?= $this->endSection() ?>

<!-- archivo platilla -->
<?= $this->extend('dashboard/template') ?>

<!-- contenido central -->
<?= $this->section('content'); ?>
<style>
  /* Mejora visual ligera sin alterar funcionalidades */
  .card.my-2 { box-shadow: 0 6px 18px rgba(0,0,0,.06); border: 0; }
  .card.my-2 .card-header { border: 0; }
  .card.card-info .card-header { background: #2563eb; }
  .card.card-success .card-header { background: #16a34a; }
  .card.card-danger .card-header { background: #dc2626; }
  .card.card-dark .card-header { background: #374151; }
  .card .card-body .display-3 { font-weight: 800; letter-spacing: -1px; }
  .card:hover { transform: translateY(-2px); transition: transform .2s ease; }
  .table thead th { white-space: nowrap; }
  .btn.btn-warning, .btn.btn-danger, .btn.btn-success { border: 0; }
  .btn.btn-warning { background-color: #d97706; }
  .btn.btn-danger { background-color: #dc2626; }
  .btn.btn-success { background-color: #16a34a; }
  .btn.btn-warning:hover { background-color: #b45309; }
  .btn.btn-danger:hover { background-color: #b91c1c; }
  .btn.btn-success:hover { background-color: #15803d; }
  .badge.bg-success { background-color: #16a34a !important; }
  .badge.bg-secondary { background-color: #6b7280 !important; }
  /* Ajuste botones DataTables */
  .dt-buttons .btn { margin-right: .25rem; }
  .dataTables_wrapper .dataTables_filter input { border-radius: .5rem; }
</style>
<!-- Contenido principal -->

          <!-- Hero Section -->

          <!-- KPIs -->
          <section class="mx-n4 p-4 bg-light mb-4" aria-label="Métricas de empleados">
            <div class="row gx-4 justify-content-center">
              <div class="col-xl-4 col-md-5 col-sm-6">
                <div class="card my-2 card-info" id="kpi-card-total">
                  <div class="card-header">
                    <h2 class="card-title text-white h5">
                      <i class="bi bi-people me-2" aria-hidden="true"></i>Total Trabajadores
                    </h2>
                  </div>
                  <div class="card-body text-center">
                    <p class="display-3 text-white m-0 lh-1" id="kpi-total" aria-live="polite">0</p>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-md-5 col-sm-6">
                <div class="card my-2 card-success" id="kpi-card-activos">
                  <div class="card-header">
                    <h2 class="card-title text-white h5">
                      <i class="bi bi-person-check me-2" aria-hidden="true"></i>Activos
                    </h2>
                  </div>
                  <div class="card-body text-center">
                    <p class="display-3 text-white m-0 lh-1" id="kpi-activos" aria-live="polite">0</p>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-md-5 col-sm-6">
                <div class="card my-2 card-danger" id="kpi-card-inactivos">
                  <div class="card-header">
                    <h2 class="card-title text-white h5">
                      <i class="bi bi-person-dash me-2" aria-hidden="true"></i>Inactivos
                    </h2>
                  </div>
                  <div class="card-body text-center">
                    <p class="display-3 text-white m-0 lh-1" id="kpi-inactivos" aria-live="polite">0</p>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- Tabla de empleados -->
          <section class="row gx-4" aria-label="Lista de empleados">
            <div class="col-12">
              <div class="card my-2 card-dark">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h2 class="card-title text-white m-0 h5">Listado de Empleados</h2>
                  <!-- Botón añadir trabajador para móviles -->
                  <button class="btn btn-success d-lg-none" data-bs-toggle="modal" data-bs-target="#addNewContact">
                    <i class="bi bi-person-plus me-1" aria-hidden="true"></i>Añadir
                  </button>
                </div>
                <div class="card-body">
                  <div class="bg-white rounded-2 p-2">
                    <div class="table-responsive">
                      <table id="customButtons" class="table align-middle" aria-describedby="tablaDescripcion">
                        <caption id="tablaDescripcion" class="visually-hidden">
                          Tabla que muestra la lista de empleados con información como nombre, compañía, correo, teléfono, estado y fecha de registro
                        </caption>
                        <thead>
                          <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Compañía</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Fecha de Registro</th>
                            <th scope="col">Acciones</th>
                          </tr>
                        </thead>
                        <tbody id="trabajadoresTable">
                          <!-- Filas dinámicas se insertarán aquí -->
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
      
  <!-- Modal Añadir/Editar -->
  <div class="modal fade" id="addNewContact" tabindex="-1" aria-labelledby="addNewContactLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title h5" id="addNewContactLabel">Nuevo Trabajador</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="trabajadorForm">
            <div class="row gx-4">
              <div class="col-sm-12">
                <div class="mb-3">
                  <label for="modalNombre" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="modalNombre" placeholder="Nombre" required>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="mb-3">
                  <label for="modalCompania" class="form-label">Compañía</label>
                  <input type="text" class="form-control" id="modalCompania" placeholder="Compañía" required>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="mb-3">
                  <label for="modalCorreo" class="form-label">Correo</label>
                  <input type="email" class="form-control" id="modalCorreo" placeholder="Correo electrónico" required>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="mb-3">
                  <label for="modalTelefono" class="form-label">Teléfono</label>
                  <input type="tel" class="form-control" id="modalTelefono" placeholder="Teléfono" required>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="mb-3">
                  <label for="modalEstado" class="form-label">Estado</label>
                  <select class="form-control" id="modalEstado" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="mb-3">
                  <label for="modalFecha" class="form-label">Fecha de Registro</label>
                  <input type="date" class="form-control" id="modalFecha" required>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
  <button type="button" class="btn btn-primary" id="addTrabajadorBtn">Añadir Trabajador</button>
     </div>
      </div>
    </div>
  </div>
<!-- Modal WhatsApp -->
<div class="modal fade" id="whatsappModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">
          <i class="bi bi-whatsapp me-2"></i>Enviar Mensaje de WhatsApp
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="whatsappForm" onsubmit="event.preventDefault(); enviarWhatsApp();">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nombre del Destinatario <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="whatsapp_nombre" name="whatsapp_nombre" placeholder="Ejemplo: YORDANY" required readonly style="background-color: #f8f9fa;">
          </div>
          <div class="mb-3">
            <label class="form-label">Número de Teléfono <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="whatsapp_telefono" name="whatsapp_telefono" placeholder="+51" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mensaje <span class="text-danger">*</span></label>
            <textarea class="form-control" id="whatsapp_mensaje" name="whatsapp_mensaje" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i>Cancelar
          </button>
          <button type="button" class="btn btn-success" onclick="enviarWhatsApp()">
            <i class="bi bi-whatsapp me-1"></i>Enviar Mensaje
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>