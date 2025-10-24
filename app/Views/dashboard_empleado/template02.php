<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ZOE COSTA - Dashboard</title>
  <meta name="description" content="Panel administrativo de ZOE COSTA" />
  <meta name="author" content="ZOE COSTA" />
  <link rel="shortcut icon" href="<?= base_url('public/img/ZOE.png') ?>" />
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
     <?= $this->include('dashboard_empleado/nav') ?>
      <!-- Backdrop para sidebar móvil -->
      <div class="sidebar-backdrop d-lg-none"></div>
      <!-- /Sidebar -->

      <div class="app-container">
        <!-- Header -->
    <?= $this->include('dashboard_empleado/sidebar') ?>
        <!-- /Header -->

        <!-- Hero -->
       <?= $this->renderSection('titulo') ?>
       <!-- Hero -->
        
        <?=$this->renderSection('content')?>

        <!-- Footer -->
 <?= $this->include('dashboard_empleado/footer') ?>
        <!-- /Footer<|end_header|>

      </div>
    </div>
  <-- JS -->
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
  <script src="<?= base_url('public/js/custom.js') ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="<?=base_url('public/js/usuarios.js')?>"></script>
  <?= $this->renderSection('scripts') ?>


  <!-- Chatbot para Empleados -->
  <div id="chatbot-container" class="chatbot-container">
    <div class="chatbot-header">
      <div class="chatbot-title">
        <i class="bi bi-person-badge"></i>
        <span>Asistente de Empleado</span>
      </div>
      <button id="minimize-chatbot" class="minimize-btn">
        <i class="bi bi-dash"></i>
      </button>
    </div>
    
    <div id="chatbot-messages" class="chatbot-messages">
      <div class="chatbot-welcome">
        <p>¡Hola! Soy tu asistente de ZOE COSTA. ¿En qué puedo ayudarte hoy?</p>
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
    background: #10b981;
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
    background: #10b981;
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
    border-color: #10b981;
  }

  #chatbot-send-btn {
    background: #10b981;
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
    background: #0d9f75;
  }

  .chatbot-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #10b981;
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
    background: #0d9f75;
    transform: scale(1.05);
  }
</style>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const chatbotContainer = document.getElementById('chatbot-container');
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const minimizeBtn = document.getElementById('minimize-chatbot');
    const chatMessages = document.getElementById('chatbot-messages');
    const userInput = document.getElementById('chatbot-user-input');
    const sendBtn = document.getElementById('chatbot-send-btn');

    // Don't show chat on page load
    if (chatbotContainer) {
        chatbotContainer.classList.remove('visible');
    }

    // Bot responses for employees
    const responses = {
        // Greetings
        'hola': [
            '¡Hola! Soy tu asistente de ZOE COSTA. ¿En qué puedo ayudarte hoy?',
            '¡Hola! ¿Cómo estás? Estoy aquí para ayudarte con tus tareas diarias.',
            '¡Hola! ¿En qué puedo asistirte hoy en tu trabajo?'
        ],
        
        // Tasks
        'tareas': [
            'Puedes ver tus tareas en la sección de "Tareas". Allí encontrarás:\n• Tareas pendientes\n• Fechas de entrega\n• Prioridades\n• Estado de cada tarea',
            'En el menú de Tareas podrás:\n- Ver tus asignaciones actuales\n- Marcar tareas como completadas\n- Ver el historial de tareas\n- Filtrar por estado o fecha',
            '¿Neitas ayuda con alguna tarea en particular? Puedo guiarte sobre cómo gestionarlas.'
        ],
        
        // Documents
        'documentos': [
            'Puedes acceder a tus documentos en la sección "Documentos". Allí podrás:\n• Ver archivos compartidos\n• Subir nuevos documentos\n• Buscar archivos específicos\n• Ver documentos recientes',
            '¿Buscas algún documento en particular? Puedo ayudarte a encontrarlo.'
        ],
        
        // Profile
        'perfil': [
            'Puedes ver y actualizar tu información personal en la sección "Mi Perfil".',
            'En tu perfil puedes:\n• Actualizar tu información de contacto\n• Cambiar tu contraseña\n• Ver tu historial de actividades',
            '¿Qué información de tu perfil necesitas actualizar?'
        ],
        
        // Work Schedule
        'horario': [
            'Puedes consultar tu horario en la sección "Mi Horario". Allí verás:\n• Días y horas de trabajo\n• Días libres\n• Horas extraordinarias\n• Vacaciones programadas',
            '¿Neitas ayuda para entender tu horario de trabajo o solicitar cambios?'
        ],
        
        // Vacations
        'vacaciones': [
            'Para solicitar vacaciones:\n1. Ve a la sección "Solicitudes"\n2. Selecciona "Solicitar vacaciones"\n3. Ingresa las fechas\n4. Adjunta documentos si es necesario\n5. Envía la solicitud',
            'Puedes ver el estado de tus solicitudes de vacaciones en la sección "Mis Solicitudes".',
            '¿Tienes dudas sobre el proceso de solicitud de vacaciones?'
        ],
        
        // Payroll
        'nómina': [
            'Puedes consultar tu información de nómina en la sección "Mi Nómina". Allí encontrarás:\n• Desglose de pagos\n• Deducciones\n• Recibos de pago\n• Años de servicio',
            '¿Neitas ayuda para entender algún concepto de tu recibo de nómina?'
        ],
        
        // Benefits
        'beneficios': [
            'Los beneficios disponibles para ti incluyen:\n• Seguro médico\n• Fondo de ahorro\n• Vacaciones\n• Días festivos\n• Capacitaciones',
            'Puedes encontrar más información sobre cada beneficio en la sección "Mis Beneficios".'
        ],
        
        // Training
        'capacitación': [
            'Puedes acceder a las capacitaciones disponibles en la sección "Capacitación". Allí podrás:\n• Ver cursos disponibles\n• Inscribirte a capacitaciones\n• Llevar registro de tu progreso\n• Descargar certificados',
            '¿Te gustaría saber qué capacitaciones están disponibles actualmente?'
        ],
        
        // Reports
        'reportes': [
            'Puedes generar reportes en la sección "Reportes". Tipos de reportes disponibles:\n• Avance de tareas\n• Tiempo trabajado\n• Productividad\n• Asistencia',
            '¿Qué tipo de reporte necesitas generar?'
        ],
        
        // Technical Support
        'soporte': [
            '¿Neitas ayuda técnica? Por favor describe el problema que estás experimentando y te ayudaré a solucionarlo.',
            'Para reportar un problema técnico:\n1. Ve a "Soporte"\n2. Selecciona "Reportar problema"\n3. Describe el problema\n4. Adjunta capturas de pantalla si es necesario\n5. Envía el reporte'
        ],
        
        // Common questions
        'cómo cambiar mi contraseña': 'Para cambiar tu contraseña:\n1. Ve a "Mi Perfil"\n2. Haz clic en "Cambiar contraseña"\n3. Ingresa tu contraseña actual\n4. Crea una nueva contraseña\n5. Confirma la nueva contraseña',
        'dónde veo mis tareas': 'Tus tareas están disponibles en la sección "Tareas" del menú principal. Allí verás todas tus asignaciones organizadas por fecha y prioridad.',
        'cómo subir un documento': 'Para subir un documento:\n1. Ve a la sección "Documentos"\n2. Haz clic en "Subir documento"\n3. Selecciona el archivo de tu computadora\n4. Completa la información requerida\n5. Haz clic en "Guardar"',
        'cómo solicitar permiso': 'Para solicitar permiso:\n1. Ve a "Solicitudes"\n2. Selecciona "Nueva solicitud"\n3. Elige "Permiso"\n4. Completa los detalles\n5. Envía la solicitud',
        'dónde veo mi horario': 'Tu horario está disponible en la sección "Mi Horario". Allí podrás ver tus turnos y días de descanso.',
        'cómo actualizar mis datos': 'Para actualizar tus datos personales:\n1. Ve a "Mi Perfil"\n2. Haz clic en "Editar perfil"\n3. Actualiza la información\n4. Guarda los cambios',
        
        // Common phrases
        'gracias': ['¡De nada!', '¡Para eso estoy!', '¡Es un placer ayudarte!', '¡Estoy aquí para ayudarte!'],
        'adiós': ['¡Hasta luego!', '¡Que tengas un excelente día!', '¡Vuelve pronto!', '¡Gracias por usar ZOE COSTA!'],
        'cómo estás': ['¡Muy bien, gracias! ¿Y tú?', '¡Todo en orden! ¿En qué puedo ayudarte hoy?', '¡Listo para ayudarte! ¿Qué necesitas?'],
        'ayuda': ['¿En qué puedo ayudarte hoy?', 'Estoy aquí para ayudarte. ¿Qué necesitas saber?', '¿En qué puedo asistirte hoy?']
    };

    // Add common variations and synonyms
    const synonyms = {
        'hola': ['buenos días', 'buenas tardes', 'buenas noches', 'saludos'],
        'tareas': ['actividades', 'pendientes', 'trabajos', 'asignaciones'],
        'documentos': ['archivos', 'expedientes', 'registros', 'informes'],
        'perfil': ['cuenta', 'información personal', 'datos personales'],
        'ayuda': ['soporte', 'asistencia', 'cómo funciona', 'guía']
    };
    
    // Expand responses with synonyms
    Object.entries(synonyms).forEach(([key, words]) => {
        words.forEach(word => {
            if (!responses[word] && responses[key]) {
                responses[word] = responses[key];
            }
        });
    });

    // Toggle chat visibility
    function toggleChat() {
        if (chatbotContainer) {
            chatbotContainer.classList.toggle('visible');
            localStorage.setItem('chatVisible', chatbotContainer.classList.contains('visible'));
        }
    }

    // Initialize chat state from localStorage
    if (chatbotContainer && localStorage.getItem('chatVisible') === 'true') {
        chatbotContainer.classList.add('visible');
    }

    // Event listeners
    if (chatbotToggle) {
        chatbotToggle.addEventListener('click', toggleChat);
    }
    
    // Minimize chat
    if (minimizeBtn) {
        minimizeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (chatbotContainer) {
                chatbotContainer.classList.remove('visible');
                localStorage.setItem('chatVisible', 'false');
            }
        });
    }

    // Close chat when clicking outside
    document.addEventListener('click', function(e) {
        if (chatbotContainer && !chatbotContainer.contains(e.target) && e.target !== chatbotToggle) {
            chatbotContainer.classList.remove('visible');
            localStorage.setItem('chatVisible', 'false');
        }
    });

    // Prevent clicks inside chat from closing it
    if (chatbotContainer) {
        chatbotContainer.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Send message function
    function sendMessage() {
        const message = userInput.value.trim();
        if (message === '') return;

        // Add user message to chat
        addMessage(message, 'user');
        userInput.value = '';

        // Simulate typing
        setTimeout(() => {
            const botResponse = getBotResponse(message);
            addMessage(botResponse, 'bot');
        }, 500);
    }

    // Add message to chat
    function addMessage(text, sender) {
        if (!chatMessages) return;
        
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', sender);
        
        // Replace newlines with <br> for line breaks
        const formattedText = text.replace(/\n/g, '<br>');
        messageDiv.innerHTML = formattedText;
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Get bot response
    function getBotResponse(input) {
        const lowerInput = input.toLowerCase().trim();
        
        // Check for exact matches first
        for (const [key, value] of Object.entries(responses)) {
            if (lowerInput.includes(key)) {
                const possibleResponses = Array.isArray(value) ? value : [value];
                return possibleResponses[Math.floor(Math.random() * possibleResponses.length)];
            }
        }

        // Check for partial matches and keywords
        const keywords = {
            'cómo': 'Parece que tienes una pregunta sobre cómo hacer algo. ¿Podrías ser más específico?',
            'dónde': '¿Podrías indicarme exactamente qué estás buscando? Así podré ayudarte mejor a encontrarlo.',
            'cuándo': '¿Te refieres a una fecha o plazo específico? Por favor, proporciona más detalles.',
            'por qué': 'Entiendo que necesitas una explicación. ¿Podrías darme más contexto sobre tu pregunta?',
            'quién': '¿Podrías especificar sobre qué o quién necesitas información?',
            'puedo': 'Sí, puedes realizar muchas acciones en el sistema. ¿Podrías ser más específico sobre lo que necesitas hacer?',
            'necesito': 'Entiendo que necesitas ayuda. Por favor, describe con más detalle lo que estás intentando hacer.',
            'ayuda': 'Estoy aquí para ayudarte. Por favor, cuéntame más sobre lo que necesitas.'
        };

        // Check for question words or common phrases
        for (const [keyword, response] of Object.entries(keywords)) {
            if (lowerInput.includes(keyword)) {
                return response;
            }
        }

        // Check for common greetings
        if (/(hola|buenos|buenas|saludos|hey|holi)/i.test(lowerInput)) {
            return '¡Hola! ¿En qué puedo ayudarte hoy en el sistema ZOE COSTA?';
        }

        // Check for thanks
        if (/(gracias|agradecido|agradecida|te lo agradezco)/i.test(lowerInput)) {
            return '¡De nada! ¿Hay algo más en lo que pueda ayudarte?';
        }

        // Check for goodbyes
        if (/(adiós|chao|hasta luego|nos vemos|hasta pronto)/i.test(lowerInput)) {
            return '¡Hasta luego! No dudes en volver si necesitas más ayuda.';
        }

        // If no specific match, try to provide a helpful response
        const helpOptions = [
            'Parece que tienes una pregunta sobre el sistema. ¿Podrías reformularla de otra manera?',
            'No estoy seguro de entender completamente. ¿Podrías darme más detalles sobre lo que necesitas?',
            'Voy a necesitar más información para ayudarte mejor. ¿Podrías ser más específico?',
            '¿Podrías reformular tu pregunta? Así podré ayudarte de la mejor manera posible.',
            'Entiendo que necesitas ayuda. ¿Podrías contarme más sobre lo que estás intentando hacer?',
            'No tengo una respuesta específica para eso, pero puedo ayudarte con muchas otras cosas. ¿Qué necesitas saber?'
        ];

        return helpOptions[Math.floor(Math.random() * helpOptions.length)];
    }

    // Event listeners for sending messages
    if (sendBtn) {
        sendBtn.addEventListener('click', sendMessage);
    }
    
    if (userInput) {
        userInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }
  });
  </script>

</body>
</html>