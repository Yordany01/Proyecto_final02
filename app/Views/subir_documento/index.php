<!-- titulo -->
<?= $this->section('titulo'); ?>
<div class="app-hero-header d-flex align-items-center">
          <div class="d-flex align-items-center">
            <div class="me-3 icon-box md bg-white rounded-4">
              <i class="bi bi-file-earmark-arrow-up fs-3 text-primary"></i>
            </div>
            <div>
              <h2 class="mb-1">Subir Documento</h2>
              <small>Selecciona y guarda archivos localmente</small>
            </div>
          </div>
        </div>
<?= $this->endSection() ?>

<!-- contenido  -->
<?= $this->section('content'); ?>
<div class="row gx-4">
          <!-- Formulario -->
          <div class="col-12">
            <div class="card my-2 card-dark">
              <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title text-white m-0">Formulario</h5>
                <div class="d-flex gap-2">
                  <button id="saveBtn" type="button" class="btn btn-outline-light btn-sm" disabled>
                    <i class="bi bi-save me-1"></i>Guardar
                  </button>
                  <button id="resetBtn" type="button" class="btn btn-sm btn-outline-light">Limpiar</button>
                </div>
              </div>
              <div class="card-body">
                <div class="bg-white rounded-2 p-3">
                  <form id="uploadForm" onsubmit="return false;">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label for="docTitle" class="form-label">Título (opcional)</label>
                        <input type="text" class="form-control" id="docTitle" placeholder="Suba su Documento">
                      </div>
                      <div class="col-md-6">
                        <label for="docFile" class="form-label">Archivo</label>
                        <input
                          type="file"
                          class="form-control"
                          id="docFile"
                          accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.txt"
                        >
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Guardados -->
          <div class="col-12">
            <div class="card my-2 card-dark">
              <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title text-white m-0">Archivos guardados</h5>
                <span class="text-white-50 small">Local (no se sube al servidor)</span>
              </div>
              <div class="card-body">
                <div class="bg-white rounded-2 p-3">
                  <div id="savedEmpty" class="text-center text-muted">Aún no has guardado archivos.</div>
                  <div id="savedList" class="row g-3 mt-1"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
<?= $this->endSection() ?>

<!-- plantilla general -->
<?= $this->extend('dashboard_empleado/template02') ?>