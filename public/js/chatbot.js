document.addEventListener('DOMContentLoaded', function() {
    const chatbotContainer = document.getElementById('chatbot-container');
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const minimizeBtn = document.getElementById('minimize-chatbot');
    const chatMessages = document.getElementById('chatbot-messages');
    const userInput = document.getElementById('chatbot-user-input');
    const sendBtn = document.getElementById('chatbot-send-btn');
    const suggestionBtns = document.querySelectorAll('.suggestion-btn');

    // Don't show chat on page load
    // Chat will only open when the chat toggle is clicked
    if (chatbotContainer) {
        chatbotContainer.classList.remove('visible');
    }

    // Bot responses
    const responses = {
        // Greetings
        'hola': [
            '¡Hola! Soy el asistente de ZOE COSTA. ¿En qué puedo ayudarte hoy?',
            '¡Hola! Bienvenido al sistema ZOE COSTA. ¿Cómo puedo asistirte?',
            '¡Hola! Estoy aquí para ayudarte con el sistema ZOE COSTA. ¿En qué necesitas ayuda?'
        ],
        
        // System help
        'qué puedes hacer': [
            'Puedo ayudarte con:\n• Gestión de tareas (asignar, ver, completar)\n• Administración de documentos\n• Consulta de información de empleados\n• Configuración del sistema\n• Reportes y análisis\n\n¿Sobre qué necesitas ayuda?',
            'Estoy aquí para asistirte con:\n- Gestión de tareas y proyectos\n- Documentos y archivos\n- Perfiles de empleados\n- Ajustes del sistema\n- Generación de reportes\n\n¿En qué puedo ayudarte hoy?'
        ],
        
        // Tasks
        'tareas': [
            'Puedes gestionar las tareas en la sección de "Tareas" del menú. Allí podrás:\n• Ver tareas asignadas\n• Crear nuevas tareas\n• Marcar tareas como completadas\n• Asignar tareas a otros empleados',
            'Las tareas se encuentran en el menú principal. Accede para ver:\n- Tus tareas pendientes\n- Tareas completadas\n- Tareas vencidas\n- Calendario de actividades',
            'Para ver tus tareas, ve a la sección de "Tareas" en el menú lateral. ¿Necesitas ayuda con algo más?'
        ],
        
        // Documents
        'documentos': [
            'La gestión de documentos está disponible en la sección "Documentos". Allí puedes:\n• Subir nuevos documentos\n• Ver documentos compartidos\n• Organizar en carpetas\n• Buscar archivos específicos',
            'Puedes acceder a todos los documentos del sistema en la sección "Documentos". ¿Buscas algún archivo en particular?'
        ],
        
        // Employees
        'empleados': [
            'La información de los empleados está disponible en la sección "Trabajadores". Allí puedes:\n• Ver lista de empleados\n• Agregar nuevos trabajadores\n• Editar información laboral\n• Generar reportes de personal',
            'Puedes gestionar a los empleados desde el menú "Trabajadores". ¿Qué información necesitas?'
        ],
        
        // Profile
        'perfil': [
            'Puedes ver y editar tu perfil en la sección "Mi Perfil". Allí puedes:\n• Actualizar tu información personal\n• Cambiar tu contraseña\n• Ver tu historial de actividades\n• Configurar preferencias',
            'Tu perfil contiene toda tu información personal y configuraciones. ¿Qué te gustaría actualizar?'
        ],
        
        // Reports
        'reportes': [
            'Puedes generar reportes en la sección "Reportes". Los reportes disponibles incluyen:\n• Actividad de usuarios\n• Progreso de tareas\n• Documentos recientes\n• Estadísticas de productividad',
            'Los reportes te ayudan a analizar el rendimiento. ¿Qué tipo de reporte necesitas?'
        ],
        
        // Settings
        'ajustes': [
            'La configuración del sistema está disponible en "Ajustes". Allí puedes:\n• Configurar preferencias de la cuenta\n• Gestionar notificaciones\n• Personalizar la interfaz\n• Configurar integraciones',
            '¿Qué ajustes necesitas modificar? Puedo guiarte a través de las opciones disponibles.'
        ],
        
        // Help
        'ayuda': [
            '¿En qué necesitas ayuda? Puedo asistirte con:\n• Uso del sistema\n• Gestión de tareas\n• Manejo de documentos\n• Reportes y análisis\n\n¿Sobre qué tema necesitas ayuda?',
            'Estoy aquí para ayudarte. Por favor, indícame qué necesitas saber sobre el sistema ZOE COSTA.'
        ],
        
        // Common questions
        'cómo cambiar mi contraseña': 'Puedes cambiar tu contraseña en la sección "Mi Perfil". Busca la opción "Cambiar contraseña" y sigue las instrucciones.',
        'dónde están mis documentos': 'Tus documentos personales están en la sección "Documentos". Si no los encuentras, verifica los filtros de búsqueda o contacta a tu administrador.',
        'cómo crear una tarea': 'Para crear una tarea:\n1. Ve a la sección "Tareas"\n2. Haz clic en "Nueva tarea"\n3. Completa los detalles\n4. Asigna la tarea y guarda',
        
        // Common phrases
        'gracias': ['¡De nada!', '¡Para eso estoy!', '¡Es un placer ayudarte!', '¡Estoy aquí para ayudarte!'],
        'adiós': ['¡Hasta luego!', '¡Que tengas un excelente día!', '¡Vuelve pronto!', '¡Gracias por usar ZOE COSTA!'],
        'cómo estás': ['¡Muy bien, gracias! ¿Y tú?', '¡Todo en orden! ¿En qué puedo ayudarte hoy?', '¡Listo para ayudarte! ¿Qué necesitas?']
    };
    
    // Add common variations and synonyms
    const synonyms = {
        'hola': ['buenos días', 'buenas tardes', 'buenas noches', 'saludos'],
        'tareas': ['actividades', 'pendientes', 'trabajos', 'asignaciones'],
        'documentos': ['archivos', 'expedientes', 'registros', 'informes'],
        'empleados': ['trabajadores', 'personal', 'equipo', 'colaboradores'],
        'perfil': ['cuenta', 'información personal', 'datos personales'],
        'reportes': ['informes', 'estadísticas', 'métricas', 'análisis'],
        'ajustes': ['configuración', 'preferencias', 'opciones', 'herramientas'],
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
            'cómo': 'Parece que tienes una pregunta sobre cómo hacer algo. ¿Podrías ser más específico sobre qué necesitas hacer?',
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

        // If the input is very short or doesn't look like a complete question
        if (lowerInput.length < 5 || !/[¿?]/.test(lowerInput)) {
            helpOptions.push(
                '¿Tienes alguna pregunta específica sobre el sistema ZOE COSTA?',
                '¿En qué puedo ayudarte hoy?',
                '¿Neitas ayuda con alguna función en particular del sistema?'
            );
        }

        return helpOptions[Math.floor(Math.random() * helpOptions.length)];
    }

    // Add context awareness
    let lastQuestion = '';
    let followUpCount = 0;
    const followUpResponses = [
        '¿Hay algo más en lo que pueda ayudarte?',
        '¿Neitas ayuda con algo más?',
        '¿Hay alguna otra pregunta que tengas?',
        '¿Puedo ayudarte con algo más hoy?',
        '¿Hay algo más sobre lo que te gustaría saber?'
    ];

    function getFollowUpResponse() {
        followUpCount++;
        if (followUpCount >= 3) {
            followUpCount = 0;
            return 'Si tienes más preguntas más adelante, no dudes en preguntar. ¡Estoy aquí para ayudarte!';
        }
        return followUpResponses[Math.floor(Math.random() * followUpResponses.length)];
    }

    // Event listeners
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

    // Suggestion buttons
    if (suggestionBtns) {
        suggestionBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const question = this.getAttribute('data-question');
                if (userInput) {
                    userInput.value = question;
                    sendMessage();
                }
            });
        });
    }

    // Prevent clicks inside chat from closing it
    if (chatbotContainer) {
        chatbotContainer.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});
