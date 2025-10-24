<!-- titulo -->
<?= $this->section('titulo'); ?>
<div class="app-hero-header d-flex align-items-center">
          <div class="d-flex align-items-center">
            <div class="me-3 icon-box md bg-white rounded-4">
              <i class="bi bi-gear fs-3 text-primary"></i>
            </div>
            <div>
              <h2 class="mb-1">Configuración Empleado</h2>
            </div>
          </div>
        </div>
<?= $this->endSection() ?>

<!-- contenido  -->
<?= $this->section('content'); ?>
   <!-- Contenido principal -->
   <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card card-light mt-4">
                <div class="card-header">
                  <h5 class="card-title">Datos del Empleado</h5>
                </div>
                <div class="card-body">
                  <form id="adminDataForm">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="adminFirstName" placeholder="Nombre">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="adminLastName" placeholder="Apellidos">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">DNI</label>
                        <input type="text" class="form-control" id="adminDni" placeholder="Documento">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="adminEmail" placeholder="correo@ejemplo.com">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="adminPhone" placeholder="+34 600 000 000">
                      </div>
                      <div class="col-md-8">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="adminAddress" placeholder="Calle, Número, Ciudad">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input type="date" class="form-control" id="adminBirthdate">
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">Foto de perfil</label>
                        <input type="file" class="form-control" id="adminAvatarFile" accept="image/*">
                      </div>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex gap-2">
                      <button id="saveAdminDataBtn" type="button" class="btn btn-primary">Guardar</button>
                      <button id="resetAdminDataBtn" type="button" class="btn btn-outline-secondary">Restablecer</button>
                    </div>
                    <div id="adminDataAlert" class="alert alert-success mt-3 d-none" role="alert">
                      <i class="bi bi-check-circle me-1"></i> Datos guardados correctamente.
                    </div>
                  </form>
                </div>
              </div>
            </div>
           <div class="col-12">
         </div>
        </div>
       </div>
      </div>
     </div>
    </div>
  </div>
<?= $this->endSection() ?>

<!-- plantilla general -->
<?= $this->extend('dashboard_empleado/template02') ?>