<!-- plantilla general -->
<?= $this->extend('dashboard_empleado/template02') ?>


<!-- titulo -->
<?= $this->section('titulo'); ?>
 <div class="app-hero-header d-flex align-items-center">
          <div class="d-flex align-items-center">
            <div class="me-3 icon-box md bg-white rounded-4">
              <i class="bi-chat-dots fs-3 text-primary"></i>
            </div>
            <div>
              <h2 class="mb-1">Chat Empleado</h2>
              <small>Comunicate en Cualquier Momento</small>
            </div>
          </div>
          <div class="ms-auto d-lg-flex d-none flex-row"><!-- botones opcionales --></div>
        </div>
<?= $this->endSection() ?>


<!-- contenido principal -->
<?= $this->section('content'); ?>
<div class="card my-4">
  <div class="card-body" style="height:250px;overflow-y:auto;" id="chatMensajes"></div>
  <div class="card-footer d-flex">
    <input type="file" id="inputImagen" accept="image/*" style="display:none;">
    <button id="btnImagen" class="btn btn-success me-2" title="Enviar imagen">
      <i class="bi bi-image"></i>
    </button>
    <input type="text" id="inputMensaje" class="form-control me-2" placeholder="Escribe tu mensaje...">
    <button id="btnEnviar" class="btn btn-primary me-2">Enviar</button>
    <button id="btnAudio" class="btn btn-secondary" title="Enviar audio">
      <i class="bi bi-mic"></i>
    </button>
  </div>
</div>


<!-- Sonido de notificación -->
<audio id="notifSound" src="https://cdn.pixabay.com/audio/2022/07/26/audio_124bpm.mp3" preload="auto"></audio>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ws = new WebSocket('ws://localhost:8080'); // Cambia localhost si tu servidor es remoto

    ws.onopen = function() {
        console.log('Conectado al chat WebSocket');
    };

    ws.onmessage = function(event) {
        const data = event.data;
        
        // Solo mostrar notificación para mensajes que no sean del propio usuario
        if (!data.startsWith('data:image') || !document.querySelector(`img[src="${data}"]`)) {
            document.getElementById('notifSound').play();
            mostrarNotificacion('¡Nuevo mensaje recibido!');
        }

        if (data.startsWith('data:audio')) {
            mostrarAudio(data, 'remoto');
        } else if (data.startsWith('data:image')) {
            // Verificar si la imagen ya fue mostrada (evitar duplicados)
            if (!document.querySelector(`img[src="${data}"]`)) {
                mostrarImagen(data, 'remoto');
            }
        } else {
            mostrarMensaje(data, 'remoto');
        }
    };

    ws.onclose = function() {
        console.log('Desconectado del chat WebSocket');
    };

    document.getElementById('btnEnviar').onclick = function() {
        const msg = document.getElementById('inputMensaje').value;
        if(msg.trim() !== '' && ws.readyState === 1) {
            ws.send(msg);
            mostrarMensaje(msg, 'yo');
            document.getElementById('inputMensaje').value = '';
        } else if(ws.readyState !== 1) {
            alert('El chat no está conectado. Verifica el servidor WebSocket.');
        }
    };

    // --- AUDIO ---
    let mediaRecorder;
    let audioChunks = [];
    const btnAudio = document.getElementById('btnAudio');

    btnAudio.onclick = async function() {
        if (!mediaRecorder || mediaRecorder.state === 'inactive') {
            // Iniciar grabación
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];
            mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
            mediaRecorder.onstop = () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                const reader = new FileReader();
                reader.onloadend = function() {
                    const base64Audio = reader.result; // data:audio/webm;base64,...
                    ws.send(base64Audio);
                    mostrarAudio(base64Audio, 'yo');
                };
                reader.readAsDataURL(audioBlob);
            };
            mediaRecorder.start();
            btnAudio.textContent = 'Detener';
            btnAudio.classList.remove('btn-secondary');
            btnAudio.classList.add('btn-danger');
        } else if (mediaRecorder.state === 'recording') {
            // Detener grabación
            mediaRecorder.stop();
            btnAudio.textContent = '';
            btnAudio.innerHTML = '<i class="bi bi-mic"></i>';
            btnAudio.classList.remove('btn-danger');
            btnAudio.classList.add('btn-secondary');
        }
    };

    function mostrarMensaje(msg, tipo) {
        const chat = document.getElementById('chatMensajes');
        const div = document.createElement('div');
        div.className = tipo === 'yo' ? 'text-end text-primary mb-1' : 'text-start text-success mb-1';
        div.textContent = msg;
        chat.appendChild(div);
        chat.scrollTop = chat.scrollHeight;
    }

    function mostrarAudio(audioData, tipo) {
        const chat = document.getElementById('chatMensajes');
        const div = document.createElement('div');
        div.className = tipo === 'yo' ? 'text-end mb-1' : 'text-start mb-1';
        const audio = document.createElement('audio');
        audio.controls = true;
        audio.src = audioData;
        div.appendChild(audio);
        chat.appendChild(div);
        chat.scrollTop = chat.scrollHeight;
    }

    function mostrarImagen(imagenData, tipo) {
        const chat = document.getElementById('chatMensajes');
        const div = document.createElement('div');
        div.className = tipo === 'yo' ? 'text-end mb-1' : 'text-start mb-1';

        const img = document.createElement('img');
        img.src = imagenData;
        img.style.maxWidth = '200px';
        img.style.borderRadius = '10px';
        img.style.margin = '4px';
        img.style.boxShadow = '0 0 5px rgba(0,0,0,0.2)';
        img.style.cursor = 'pointer';

        // Abrir imagen en pestaña nueva al hacer clic
        img.onclick = () => window.open(imagenData, '_blank');

        div.appendChild(img);
        chat.appendChild(div);
        chat.scrollTop = chat.scrollHeight;
    }

    // Manejar envío de imágenes
    const inputImagen = document.getElementById('inputImagen');
    const btnImagen = document.getElementById('btnImagen');

    // Abrir selector de archivo al hacer clic en el botón de imagen
    btnImagen.onclick = () => inputImagen.click();

    // Cuando el usuario selecciona una imagen
    inputImagen.addEventListener('change', async function() {
        const file = this.files[0];
        if (!file) return;

        // Verificar tamaño máximo (2MB)
        const maxSize = 2 * 1024 * 1024; // 2MB
        if (file.size > maxSize) {
            alert('La imagen es demasiado grande. El tamaño máximo permitido es 2MB.');
            return;
        }

        // Convertir la imagen a base64
        const reader = new FileReader();
        reader.onload = function() {
            const base64Image = reader.result;
            
            // Verificar si la conexión está abierta
            if (ws.readyState === WebSocket.OPEN) {
                try {
                    // Enviar la imagen
                    ws.send(base64Image);
                    // Mostrar la imagen en el chat
                    mostrarImagen(base64Image, 'yo');
                } catch (error) {
                    console.error('Error al enviar la imagen:', error);
                    mostrarNotificacion('Error al enviar la imagen');
                }
            } else {
                mostrarNotificacion('Error: No hay conexión con el servidor');
            }
        };
        
        reader.onerror = function() {
            console.error('Error al leer la imagen');
            mostrarNotificacion('Error al procesar la imagen');
        };
        
        reader.readAsDataURL(file);
        
        // Limpiar el input para permitir cargar la misma imagen otra vez si es necesario
        this.value = '';
    });

    // Notificación visual tipo toast
    function mostrarNotificacion(texto) {
        let toast = document.createElement('div');
        toast.className = 'toast align-items-center text-bg-primary border-0 show position-fixed bottom-0 end-0 m-3';
        toast.role = 'alert';
        toast.style.zIndex = 9999;
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${texto}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => { toast.remove(); }, 3000);
    }
});
</script>
<?= $this->endSection() ?>



