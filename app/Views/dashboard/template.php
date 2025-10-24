<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ZOE COSTA - Dashboard</title>
  <meta name="description" content="Panel administrativo de ZOE COSTA" />
  <meta name="author" content="ZOE COSTA" />
  <link rel="shortcut icon" href="<?= base_url('public/img/ZOE.png') ?>" />
  <!-- Styles -->
  <link rel="stylesheet" href="<?= base_url('public/fonts/bootstrap/bootstrap-icons.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('public/css/main.min.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('public/css/responsive.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('public/vendor/overlay-scroll/OverlayScrollbars.min.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('public/vendor/datatables/dataTables.bs5.css') ?>" />
  <link rel="stylesheet" href="<?=base_url('public/vendor/datatables/buttons/dataTables.bs5-custom.css')?>" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('public/css/custom_styles.css') ?>" />
  <style>
    .card-header h5{font-size:1rem;font-weight:600}
    .display-3{font-size:2.5rem}


    /* Ajuste de espaciado del contenido */
    .app-content {
      margin-top: 1.5rem;
    }

    /* Barra de búsqueda sticky */
    #searchContainer{
      position:sticky; top:0; z-index:1000;
      transition:top .3s ease;
      background:transparent; box-shadow:none; padding:0;
      display:flex; justify-content:center; align-items:center;
    }
    .hide-search{top:-80px}

    /* Centrado y estilo del input de búsqueda */
    #searchContainer .card-body{ background:transparent; padding:8px 0; width:100%; display:flex; justify-content:center; }
    #searchBar{ max-width:520px; width:100%; margin:0 auto; }

    /* Mejoras de Responsividad */
    @media (max-width: 992px) {
      .app-container.sidebar-toggled .app-content {
        margin-left: 0;
      }
      .app-sidebar {
        left: -280px; /* Oculto por defecto */
      }
      .app-container.sidebar-toggled .app-sidebar {
        left: 0;
      }
      .app-content {
        margin-left: 0;
        width: 100%;
      }
    }

    /* Acciones rápidas */
    .quick-actions .btn{
      border-radius:12px; padding:.9rem 1rem; height:100%;
      display:flex; flex-direction:column; align-items:center; justify-content:center;
      transition:transform .2s ease, box-shadow .2s ease;
    }
    .quick-actions .btn i{font-size:1.6rem; margin-bottom:.4rem}
    .quick-actions .btn:hover{transform:translateY(-2px); box-shadow:0 6px 16px rgba(0,0,0,.08)}

    /* Chatbot Styles */
    .chatbot-container {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1000;
    }

    .chatbot-button {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: linear-gradient(135deg, #4f46e5, #7c3aed);
      border: none;
      color: white;
      font-size: 24px;
      cursor: pointer;
      box-shadow: 0 4px 20px rgba(79, 70, 229, 0.4);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .chatbot-button:hover {
      transform: scale(1.1);
      box-shadow: 0 6px 25px rgba(79, 70, 229, 0.6);
    }

    .chatbot-window {
      position: absolute;
      bottom: 70px;
      right: 0;
      width: 350px;
      height: 500px;
      background: white;
      border-radius: 20px;
      box-shadow: 0 15px 30px rgba(0,0,0,0.3);
      display: none;
      flex-direction: column;
      overflow: hidden;
    }

    .chatbot-header {
      background: #4f46e5;
      color: white;
      padding: 15px 20px;
      text-align: center;
    }

    .chatbot-messages {
      flex: 1;
      padding: 15px;
      overflow-y: auto;
      background: #f8fafc;
    }

    .chatbot-message {
      margin-bottom: 12px;
      display: flex;
    }

    .user-message {
      justify-content: flex-end;
    }

    .bot-message {
      justify-content: flex-start;
    }

    .message-bubble {
      max-width: 80%;
      padding: 10px 14px;
      border-radius: 18px;
      font-size: 13px;
      line-height: 1.4;
    }

    .user-bubble {
      background: #4f46e5;
      color: white;
      border-bottom-right-radius: 4px;
    }

    .bot-bubble {
      background: white;
      color: #333;
      border: 1px solid #e2e8f0;
      border-bottom-left-radius: 4px;
    }

    .chatbot-input {
      padding: 15px;
      border-top: 1px solid #e2e8f0;
      background: white;
    }

    .chatbot-input-group {
      display: flex;
      gap: 8px;
    }

    #chatbot-user-input {
      flex: 1;
      padding: 10px 14px;
      border: 1px solid #d1d5db;
      border-radius: 20px;
      outline: none;
      font-size: 13px;
    }

    #chatbot-user-input:focus {
      border-color: #4f46e5;
    }

    #chatbot-send-btn {
      background: #4f46e5;
      color: white;
      border: none;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.3s;
    }

    #chatbot-send-btn:hover {
      background: #4338ca;
    }

    .typing-indicator {
      display: none;
      padding: 10px 14px;
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 18px;
      border-bottom-left-radius: 4px;
      max-width: 80%;
    }

    .typing-dots {
      display: flex;
      gap: 3px;
    }

    .typing-dot {
      width: 6px;
      height: 6px;
      background: #9ca3af;
      border-radius: 50%;
      animation: typing 1.4s infinite;
    }

    .typing-dot:nth-child(2) {
      animation-delay: 0.2s;
    }

    .typing-dot:nth-child(3) {
      animation-delay: 0.4s;
    }

    @keyframes typing {
      0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.4;
      }
      30% {
        transform: translateY(-8px);
        opacity: 1;
      }
    }

    /* Responsive */
    @media (max-width: 576px) {
      .app-hero-header { margin: 10px 8px; padding: 12px 14px; }
      .card-header.d-flex, .card-header.d-flex.align-items-center { flex-direction: column; align-items: flex-start !important; gap: .5rem; }
      .d-flex.gap-2, .d-flex.gap-3 { flex-wrap: wrap; }
      .table-responsive { overflow-x: auto; }
      #searchBar{ max-width:100%; }
      
      .chatbot-window {
        width: 300px;
        height: 450px;
        right: 10px;
      }
      
      .chatbot-container {
        bottom: 10px;
        right: 10px;
      }
    }

    /* Sidebar móvil */
    @media (max-width: 991.98px) {
      #sidebar {
        position: fixed;
        left: -260px;
        top: 0;
        bottom: 0;
        width: 260px;
        z-index: 1050;
        transition: left .3s ease;
      }
      body.sidebar-open #sidebar { left: 0; }

      .app-container { padding-left: 0 !important; }
      .app-header { padding-left: 0 !important; }

      .sidebar-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.35);
        z-index: 1040;
      }
      body.sidebar-open .sidebar-backdrop { display: block; }
    }
  </style>
</head>

<body>
  <div class="page-wrapper">
    <div class="main-container">

      <!-- Sidebar -->
     <?= $this->include('dashboard/nav') ?>
      <!-- Backdrop para sidebar móvil -->
      <div class="sidebar-backdrop d-lg-none"></div>
      <!-- /Sidebar -->

      <div class="app-container">
        <!-- Header -->
    <?= $this->include('dashboard/sidebar') ?>
        <!-- /Header -->

        <!-- Hero -->
       <?= $this->renderSection('titulo') ?>
       <!-- Hero -->
        
        <?=$this->renderSection('content')?>

        <!-- Footer -->
 <?= $this->include('dashboard/footer') ?>
        <!-- /Footer -->

      </div>
    </div>


  <!-- JS -->
  <script src="<?= base_url('public/js/jquery.min.js') ?>"></script>
  <script src="<?= base_url('public/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('public/js/moment.min.js') ?>"></script>
  <script src="<?= base_url('public/vendor/overlay-scroll/jquery.overlayScrollbars.min.js') ?>"></script>
  <script src="<?= base_url('public/vendor/overlay-scroll/custom-scrollbar.js') ?>"></script>
  <script src="<?=base_url('public/vendor/datatables/dataTables.min.js')?>"></script>
  <script src="<?=base_url('public/vendor/datatables/dataTables.bootstrap.min.js')?>"></script>
  <script src="<?=base_url('public/vendor/datatables/buttons/dataTables.buttons.min.js')?>"></script>
  <script src="<?=base_url('public/vendor/datatables/buttons/jszip.min.js')?>"></script>
  <script src="<?=base_url('public/vendor/datatables/buttons/pdfmake.min.js')?>"></script>
  <script src="<?=base_url('public/vendor/datatables/buttons/vfs_fonts.js')?>"></script>
  <script src="<?=base_url('public/vendor/datatables/buttons/buttons.html5.min.js')?>"></script>
  <script src="<?=base_url('public/vendor/datatables/buttons/buttons.print.min.js')?>"></script>
  <script src="<?=base_url('public/vendor/datatables/buttons/buttons.colVis.min.js')?>"></script>
  <script>
  const baseURL = "<?= base_url() ?>";
  </script>
  <script src="<?= base_url('public/js/custom.js') ?>"></script>
  <script src="<?=base_url('public/js/generales.js')?>"></script>
  <script src="<?=base_url('public/js/trabajadores.js')?>"></script>
  <script src="<?=base_url('public/js/tareas.js')?>"></script>
  <script src="<?=base_url('public/js/ajustes.js')?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?= base_url('public/js/crear_perifl.js') ?>"></script>
  <script src="<?= base_url('public/js/perfil.js') ?>"></script>
  <script src="<?=base_url('public/js/usuarios.js')?>"></script>
  

  <!-- Scripts específicos de la página -->
  <?= $this->renderSection('scripts') ?>

  <!-- Chatbot -->
  <div id="chatbot-container" class="chatbot-container">
    <div class="chatbot-header">
      <div class="chatbot-title">
        <i class="bi bi-robot"></i>
        <span>Asistente Virtual</span>
      </div>
      <button id="minimize-chatbot" class="minimize-btn">
        <i class="bi bi-dash"></i>
      </button>
    </div>
    
    <div id="chatbot-messages" class="chatbot-messages">
      <div class="chatbot-welcome">
        <p>¡Hola! Soy tu asistente virtual. ¿En qué puedo ayudarte hoy?</p>
      </div>
    </div>
    
    <div class="chatbot-input">
      <input 
        type="text" 
        id="chatbot-user-input" 
        placeholder="Escribe tu mensaje..."
        autocomplete="off"
      >
      <button id="chatbot-send-btn">
        <i class="bi bi-send-fill"></i>
      </button>
    </div>
  </div>

  <button id="chatbot-toggle" class="chatbot-toggle">
    <i class="bi bi-chat-dots"></i>
  </button>

  <style>
  /* Chatbot Styles */
  .chatbot-container {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 350px;
    height: 500px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    z-index: 1000;
    transform: translateY(20px);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
  }

  .chatbot-container.visible {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
  }

  .chatbot-header {
    background: #4f46e5;
    color: white;
    padding: 12px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .chatbot-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
  }

  .minimize-btn {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 1.2rem;
  }

  .chatbot-messages {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    background: #f9fafb;
  }

  .message {
    margin-bottom: 15px;
    max-width: 80%;
    padding: 10px 15px;
    border-radius: 15px;
    line-height: 1.4;
    position: relative;
  }

  .message.user {
    background: #4f46e5;
    color: white;
    margin-left: auto;
    border-bottom-right-radius: 5px;
  }

  .message.bot {
    background: #e5e7eb;
    color: #1f2937;
    margin-right: auto;
    border-bottom-left-radius: 5px;
  }

  .chatbot-welcome {
    text-align: center;
    padding: 20px 0;
  }

  .suggestions {
    margin-top: 15px;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .suggestion-btn {
    background: #e5e7eb;
    border: none;
    border-radius: 20px;
    padding: 8px 15px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: background 0.2s;
    text-align: left;
  }

  .suggestion-btn:hover {
    background: #d1d5db;
  }

  .chatbot-input {
    display: flex;
    padding: 15px;
    background: white;
    border-top: 1px solid #e5e7eb;
  }

  #chatbot-user-input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #d1d5db;
    border-radius: 20px;
    outline: none;
    font-size: 0.9rem;
  }

  #chatbot-user-input:focus {
    border-color: #4f46e5;
  }

  #chatbot-send-btn {
    background: #4f46e5;
    color: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-left: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
  }

  #chatbot-send-btn:hover {
    background: #4338ca;
  }

  .chatbot-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #4f46e5;
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    z-index: 1001;
    transition: transform 0.3s, background 0.2s;
  }

  .chatbot-toggle:hover {
    background: #4338ca;
    transform: scale(1.05);
  }
  </style>

  <script src="<?= base_url('public/js/chatbot.js') ?>"></script>

</body>

</html>