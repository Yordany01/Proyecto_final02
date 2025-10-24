
<!-- titulo -->
<?= $this->section('titulo'); ?>
 <!-- Hero -->
 <div class="app-hero-header d-flex align-items-center">
          <div class="d-flex align-items-center">
            <div class="me-3 icon-box md bg-white rounded-4">
              <i class="bi bi-person-gear fs-3 text-primary"></i>
            </div>
            <div>
              <h2 class="mb-1">Gestión de Usuarios</h2>
              <small>Administración de cuentas de usuario del sistema</small>
            </div>
          </div>

          <div class="ms-auto d-lg-flex d-none flex-row">
            <div class="d-flex flex-row gap-2 flex-wrap">
              <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#userModal">
                <i class="bi bi-person-plus me-1"></i>Nuevo Usuario
              </button>
            </div>
          </div>
        </div>
<?= $this->endSection() ?>


<!-- contenido -->
<?= $this->section('content'); ?>

  <!-- KPI cards -->
  <div class="mx-n4 p-4 bg-light mb-4">
          <div class="row gx-4 justify-content-center">
            <div class="col-xl-3 col-md-4 col-sm-6">
              <div class="card my-2 card-info">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-people me-2"></i>Total Usuarios</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-total">0</h1>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6">
              <div class="card my-2 card-success">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-person-check me-2"></i>Activos</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-activos">0</h1>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6">
              <div class="card my-2 card-warning">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-person-x me-2"></i>Inactivos</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-inactivos">0</h1>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6">
              <div class="card my-2 card-danger">
                <div class="card-header">
                  <h5 class="card-title text-white"><i class="bi bi-person-x me-2"></i>Restringidos</h5>
                </div>
                <div class="card-body text-center">
                  <h1 class="display-3 text-white m-0 lh-1" id="kpi-restringidos">0</h1>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="row gx-4">
          <div class="col-12">
            <div class="card my-2 card-dark">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title text-white m-0">Listado de Usuarios</h5>
              </div>
              <div class="card-body">
                <div class="bg-white rounded-2 p-2">
                  <div class="table-responsive">
                    <table id="usuariosTable" class="table align-middle">
                      <thead>
                        <tr>
                          <th>Nombre</th>
                          <th>Estado</th>
                          <th>Email</th>
                          <th>Acciones</th> 
                        </tr>
                      </thead>
                      <tbody id="usuariosTbody">
                        <!-- Filas dinámicas -->
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

  <!-- Modal para crear/editar usuario -->
  <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="userModalLabel">Nuevo Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="userForm">
            <input type="hidden" id="userId">
            <div class="mb-3">
              <label class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="userEmail" placeholder="usuario@ejemplo.com" required autocomplete="username">
            </div>
            <div class="mb-3 password-container">
              <label class="form-label">Contraseña <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="userPassword" placeholder="Mínimo 8 caracteres" required minlength="8" autocomplete="new-password">
              <div class="form-text">La contraseña debe tener al menos 8 caracteres</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="userConfirmPassword" placeholder="Repetir contraseña" required autocomplete="new-password">
              <div class="invalid-feedback" id="passwordError">Las contraseñas no coinciden</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Rol <span class="text-danger">*</span></label>
              <select class="form-control" id="userRole" required>
                <option value="">Seleccionar rol</option>
                <option value="Administrador">Administrador</option>
                <option value="Empleado">Empleado</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Estado <span class="text-danger">*</span></label>
              <select class="form-control" id="userStatus" required>
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
                <option value="Restringido">Restringido</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="saveUserBtn">Guardar Usuario</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal WhatsApp Usuario -->
  <div class="modal fade" id="whatsappUsuarioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="bi bi-whatsapp me-2"></i>Enviar Mensaje</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="whatsappUsuarioForm" onsubmit="event.preventDefault(); enviarWhatsAppUsuario();">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Nombre</label>
              <input type="text" class="form-control" id="whatsappu_nombre" placeholder="Nombre del destinatario">
            </div>
            <div class="mb-3">
              <label class="form-label">Teléfono <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="whatsappu_telefono" placeholder="+51" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Mensaje <span class="text-danger">*</span></label>
              <textarea class="form-control" id="whatsappu_mensaje" rows="5" placeholder="Escribe tu mensaje" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i>Cancelar</button>
            <button type="button" class="btn btn-success" onclick="enviarWhatsAppUsuario()"><i class="bi bi-whatsapp me-1"></i>Enviar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<?= $this->endSection() ?>

<!-- plantilla central -->
<?= $this->extend('dashboard/template') ?>