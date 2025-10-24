<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ZOE COSTA - Iniciar Sesión</title>
    <meta name="description" content="Sistema de gestión ZOE COSTA" />
    <link rel="shortcut icon" href="<?=base_url('public/img/ZOE.png')?>" />
    <!-- CSS Files -->
    <link rel="stylesheet" href="<?=base_url('public/fonts/bootstrap/bootstrap-icons.css')?>" />
    <link rel="stylesheet" href="<?=base_url('public/css/main.min.css')?>" />
    <link rel="stylesheet" href="<?=base_url('public/css/responsive.css')?>" />

    <!-- SweetAlert2 para las alertas de login -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .login-bg {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .auth-box {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
        }
        .auth-logo img {
            max-width: 120px;
            height: auto;
        }
        .form-control:focus {
            border-color: #4a6cf7;
            box-shadow: 0 0 0 0.25rem rgba(74, 108, 247, 0.25);
        }
        .btn-primary {
            background-color: #4a6cf7;
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #3a5bd9;
        }
        .form-check-input:checked {
            background-color: #4a6cf7;
            border-color: #4a6cf7;
        }
        .input-group-text {
            cursor: pointer;
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            .auth-box { padding: 1.25rem; }
            .auth-logo img { max-width: 90px; }
            .min-vh-100 { min-height: calc(100vh - 40px) !important; }
        }
    </style>
</head>

<body class="login-bg">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-4 col-md-6">
                <div class="auth-box">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <a href="#" class="auth-logo d-inline-block">
                            <img src="<?=base_url('public/img/ZOE.png')?>" alt="ZOE COSTA" loading="lazy" />
                        </a>
                        <h4 class="mt-3">Bienvenido a ZOE COSTA</h4>
                    </div>

                    <!-- Formulario de inicio de sesión -->
                    <form id="formLogin" onsubmit="event.preventDefault(); loguear();">
                        
                        <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                             <select class="form-select" id="usuario" name="usuario" required>
                               <option selected disabled value="">Selecciona un email</option>
                               <option value="VICTOR@GMAIL.COM">VICTOR@GMAIL.COM</option>
                               <option value="ARTURI@GMAIL.COM">ARTURI@GMAIL.COM</option>
                               <option value="PETER@GMAIL.COM">PETER@GMAIL.COM</option>
                            </select>
                       </div>
                                
                       <!-- //contraseña -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="form-label">Contraseña</label>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       placeholder="••••••••" 
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                            </button>
                        </div>
                    </form>
                <div class="text-center mt-3">
                    <p class="text-muted">© 2025 ZOE COSTA.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="<?=base_url('public/js/jquery.min.js')?>"></script>
    <script src="<?=base_url('public/js/bootstrap.bundle.min.js')?>"></script>
    <script>
    // URL base para las peticiones AJAX
    const BASE_URL = "<?= base_url() ?>";
    const SITE_URL = "<?= site_url() ?>";
</script>
    <script>
        // Mostrar/ocultar contraseña
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });

        // Limpiar el mensaje de error después de 5 segundos
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    </script>
    <script src="<?= base_url('public/js/login.js') ?>"></script>

</body>
</html>
