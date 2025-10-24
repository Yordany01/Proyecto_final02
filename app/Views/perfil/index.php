<!-- titulo -->
<?= $this->section('titulo'); ?>
<div class="app-hero-header d-flex align-items-center">
          <div class="d-flex align-items-center">
            <div class="me-3 icon-box md bg-white rounded-4">
              <i class="bi bi-person fs-3 text-primary"></i>
            </div>
            <div>
              <h2 class="mb-1">Perfil del Administrador</h2>
              <h6 class="m-0 text-dark fw-light">Información personal</h6>
            </div>
          </div>
        </div>
        <div class="text-center text-muted small mb-2">Datos visibles solo para Administrador.</div>
<?= $this->endSection() ?>


<!-- contenido -->
<?= $this->section('content'); ?>
<div class="app-body">
          <div class="row gy-4">
            <!-- Información Personal -->
            <div class="col-12">
              <div class="card card-light">
                <div class="card-header d-flex align-items-center justify-content-between">
                  <h5 class="card-title mb-0">Información personal</h5>
                </div>
                <div class="card-body">
                  <form id="infoForm" onsubmit="return false;">
                    <div class="row g-4 align-items-start">
                      <div class="col-md-3 d-flex flex-column align-items-start gap-3">
                        <img id="avatarPreview" class="rounded-circle" src="<?=base_url('public/img/file.png')?>" alt="Avatar" loading="lazy" style="width:200px;height:200px;object-fit:cover;border:4px solid #fff;box-shadow:0 6px 18px rgba(0,0,0,0.15);background:#f3f4f6;">
                      </div>
                      <div class="col-md-9">
                        <div class="row g-3">
                      <div class="col-md-6">
                        <label for="firstName" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="firstName" placeholder="Nombre" required disabled>
                      </div>
                      <div class="col-md-6">
                        <label for="lastName" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="lastName" placeholder="Apellidos" required disabled>
                      </div>
                      <div class="col-md-4">
                        <label for="dni" class="form-label">DNI/NIE</label>
                        <input type="text" class="form-control" id="dni" placeholder="Documento" disabled>
                      </div>
                      <div class="col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="correo@ejemplo.com" required disabled>
                      </div>
                      <div class="col-md-4">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="phone" placeholder="+34 600 000 000" disabled>
                      </div>
                      <div class="col-md-8">
                        <label for="address" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="address" placeholder="Calle, Número, Ciudad" disabled>
                      </div>
                      <div class="col-md-4">
                        <label for="birthdate" class="form-label">Fecha de nacimiento</label>
                        <input type="date" class="form-control" id="birthdate" disabled>
                      </div>
                        </div>
                      </div>
                    </div>
                  </form>
                  <div id="infoAlert" class="alert alert-success mt-3 d-none" role="alert">
                    <i class="bi bi-check-circle me-1"></i> Información guardada localmente.
                  </div>
                </div>
              </div>
            </div>
          </div>
       </div>
     </div>

<?= $this->endSection() ?>

<!-- plantilla central -->
<?= $this->extend('dashboard/template') ?>

