
<!-- titulo -->
<?= $this->section('titulo'); ?>
  <!-- Hero -->
  <div class="app-hero-header d-flex align-items-center">
          <div class="d-flex align-items-center">
            <div class="me-3 icon-box md bg-white rounded-4">
              <i class="bi bi-list-task fs-3 text-primary"></i>
            </div>
            <div>
              <h2 class="mb-1">Gestión de Tareas</h2>
              <small>Asignación y seguimiento de tareas</small>
            </div>
          </div>

          <div class="ms-auto d-lg-flex d-none flex-row">
            <div class="d-flex flex-row gap-2 flex-wrap">
              <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#nuevaTareaModal">
                <i class="bi bi-plus-lg me-1"></i>Nueva Tarea
              </button>
            </div>
          </div>
        </div>
<?= $this->endSection() ?>

<!-- contenido -->
<?= $this->section('content'); ?>
 <!-- KPIs (SOLO 2, centrados) -->
 <div class="mx-n4 p-4 bg-light mb-4">
          <div class="row gx-4 justify-content-center">
            <div class="col-xl-4 col-md-5 col-sm-6">
              <div class="card my-2 card-info">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-list-task me-2"></i>Total Tareas</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-total">0</h1>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-md-5 col-sm-6">
              <div class="card my-2 card-success">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-check-circle me-2"></i>Completadas</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-completadas">0</h1>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-md-5 col-sm-6">
              <div class="card my-2 card-danger">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-hourglass-split me-2"></i>Pendientes</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-pendientes">0</h1>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabla dentro de card-dark -->
        <div class="row gx-4">
          <div class="col-12">
            <div class="card my-2 card-dark">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title text-white m-0">Listado de Tareas</h5>
              </div>
              <div class="card-body">
                <div class="bg-white rounded-2 p-2">
                  <div class="table-responsive">
                    <table id="customButtons" class="table align-middle">
                      <thead>
                        <tr>
                          <th>Título</th>
                          <th>Asignado a</th>
                          <th>Teléfono</th>
                          <th>Fecha Límite</th>
                          <th>Prioridad</th>
                          <th>Estado</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody id="tareasTbody">
                        <!-- Filas dinámicas -->
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


  <!-- Modal Nueva Tarea -->
  <div class="modal fade" id="nuevaTareaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nueva Tarea</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="tareaForm" onsubmit="event.preventDefault(); guardarTarea();">
          <div class="modal-body">
            <div class="row gx-4">
              <div class="col-sm-12">
                <div class="mb-3">
                  <label class="form-label">Título <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="titulo" name="titulo" required>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="mb-3">
                  <label class="form-label">Asignado a <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="asignado_a" name="asignado_a" required>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="mb-3">
                  <label class="form-label">Teléfono <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="telefono_asignado" name="telefono" placeholder="+51" required>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="mb-3">
                  <label class="form-label">Fecha Límite <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="fecha_limite" name="fecha_limite" required>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="mb-3">
                  <label class="form-label">Prioridad <span class="text-danger">*</span></label>
                  <select class="form-control" id="prioridad" name="prioridad" required>
                    <option value="baja">Baja</option>
                    <option value="media" selected>Media</option>
                    <option value="alta">Alta</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="mb-3">
                  <label class="form-label">Estado <span class="text-danger">*</span></label>
                  <select class="form-control" id="estado" name="estado" required>
                    <option value="pendiente">Pendiente</option>
                    <option value="completada">Completada</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" name="crear_tarea" class="btn btn-primary">Añadir Tarea</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Ver Tarea -->
  <div class="modal fade" id="verTareaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalles de la Tarea</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h6 class="mb-3" id="tareaTitulo"></h6>
          <p class="mb-3" id="tareaDescripcion"></p>
          <div class="d-flex justify-content-between">
            <small class="text-muted">Fecha Límite: <span id="tareaFecha"></span></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
              <input type="text" class="form-control" id="whatsapp_nombre" name="whatsapp_nombre" placeholder="Ejemplo: YORDANY" required>
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

<!-- scripts -->
<?= $this->section('scripts') ?>
<?= $this->endSection() ?>

<!-- plantilla central -->
<?= $this->extend('dashboard/template') ?>