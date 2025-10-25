<div class="app-header d-flex align-items-center">
          <!-- Barra superior móvil con botón de menú -->
          <div class="d-flex d-lg-none align-items-center w-100 px-2 py-2">
            <button class="btn btn-outline-primary me-2" id="sidebarToggle" aria-label="Abrir menú">
              <i class="bi bi-list"></i>
            </button>
            <span class="ms-1 fw-semibold">Menú</span>
          </div>
      
          <div class="header-actions">
          <div class="dropdown">
              <a href="#" id="notifications-dropdown" class="d-flex align-items-center justify-content-center h-100" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell fs-4 position-relative">
                  <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                    <span class="visually-hidden">Notificación Nueva </span>
                  </span>
                </i>
              </a>
              <div class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="notifications-dropdown">
                <div class="dropdown-header d-flex justify-content-between align-items-center">
                  <h5 class="m-0">Notificaciones</h5>
                </div>
                <div class="dropdown-divider"></div>
                <div id="notifications-list">
                </div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item text-center">Ver todas</a>
              </div>
            </div>

            <div class="dropdown ms-4">
              <a id="userSettings" class="dropdown-toggle d-flex py-2 align-items-center" href="#!" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?= base_url('public/img/file.png') ?>" class="rounded-4 img-3x" alt="Usuario ZOE COSTA" loading="lazy" />
                <span class="ms-2 text-truncate d-lg-block d-none">Administrador</span>
              </a>
            </div>
          </div>
        </div>