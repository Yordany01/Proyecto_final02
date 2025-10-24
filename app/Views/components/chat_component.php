<?php
// Default values
$chatId = $chatId ?? 'chat-' . uniqid();
$title = $title ?? 'Chat';
$buttonIcon = $buttonIcon ?? 'bi-chat-dots-fill';
$buttonBg = $buttonBg ?? 'linear-gradient(135deg, #0d6efd, #0dcaf0)';
$headerBg = $headerBg ?? '#0d6efd';
$userBubbleBg = $userBubbleBg ?? '#0d6efd';
$welcomeMessage = $welcomeMessage ?? 'Hola! ¿En qué puedo ayudarte?';
$botResponse = $botResponse ?? 'Entendido.';
$positionBottom = $positionBottom ?? '20px';
$positionRight = $positionRight ?? null;
$positionLeft = $positionLeft ?? null;

$containerPosition = $positionLeft !== null ? "left: {$positionLeft};" : "right: " . ($positionRight ?? '20px') . ";";
$windowAlignment = $positionLeft !== null ? 'left' : 'right';
?>

<div class="chat-container" style="bottom: <?= $positionBottom ?>; <?= $containerPosition ?>">
  <button id="toggle-<?= $chatId ?>" class="chat-button">
    <i class="bi <?= $buttonIcon ?>"></i>
  </button>
  <div id="window-<?= $chatId ?>" class="chat-window" data-align="<?= $windowAlignment ?>">
    <div class="chat-header">
      <h5><?= $title ?></h5>
    </div>
    <div id="messages-<?= $chatId ?>" class="chat-messages">
      <div class="chat-message bot-message">
        <div class="message-bubble"><?= $welcomeMessage ?></div>
      </div>
    </div>
    <div class="chat-input">
      <input type="text" id="input-<?= $chatId ?>" placeholder="Escribe un mensaje...">
      <button id="send-<?= $chatId ?>"><i class="bi bi-send-fill"></i></button>
    </div>
  </div>
</div>

<style>
    #toggle-<?= $chatId ?> { background: <?= $buttonBg ?>; }
    #window-<?= $chatId ?> .chat-header { background: <?= $headerBg ?>; }
    #window-<?= $chatId ?> .user-message .message-bubble { background: <?= $userBubbleBg ?>; }
    #send-<?= $chatId ?> { background: <?= $userBubbleBg ?>; }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const chatToggle = document.getElementById('toggle-<?= $chatId ?>');
    const chatWindow = document.getElementById('window-<?= $chatId ?>');
    const chatMessages = document.getElementById('messages-<?= $chatId ?>');
    const chatInput = document.getElementById('input-<?= $chatId ?>');
    const chatSend = document.getElementById('send-<?= $chatId ?>');

    if(chatToggle) {
      chatToggle.addEventListener('click', () => {
        const isFlex = chatWindow.style.display === 'flex';
        document.querySelectorAll('.chat-window').forEach(win => win.style.display = 'none');
        chatWindow.style.display = isFlex ? 'none' : 'flex';
      });

      const addChatMessage = (text, senderClass) => {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${senderClass}`;
        messageDiv.innerHTML = `<div class="message-bubble">${text}</div>`;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
      };

      const sendChatMessage = () => {
        const message = chatInput.value.trim();
        if (message) {
          addChatMessage(message, 'user-message');
          chatInput.value = '';
          setTimeout(() => { addChatMessage('<?= $botResponse ?>', 'bot-message'); }, 1000);
        }
      };

      chatSend.addEventListener('click', sendChatMessage);
      chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendChatMessage();
      });
    }
  });
</script>

<!-- General Chat Styles (loaded once per component, but idempotent) -->
<style>
  .chat-container {
    position: fixed;
    z-index: 1000;
  }
  .chat-button {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .chat-window {
    position: absolute;
    bottom: 70px;
    width: 350px;
    height: 500px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.3);
    display: none;
    flex-direction: column;
    overflow: hidden;
  }
  .chat-window[data-align="right"] { right: 0; }
  .chat-window[data-align="left"] { left: 0; }
  .chat-header {
    color: white;
    padding: 15px 20px;
    text-align: center;
  }
   .chat-header h5 { margin: 0; font-size: 1rem; font-weight: 600; }
  .chat-messages {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    background: #f8fafc;
  }
  .chat-message { margin-bottom: 12px; display: flex; }
  .chat-message.user-message { justify-content: flex-end; }
  .chat-message.bot-message { justify-content: flex-start; }
  .chat-message .message-bubble {
    max-width: 80%;
    padding: 10px 14px;
    border-radius: 18px;
    font-size: 13px;
    line-height: 1.4;
  }
  .user-message .message-bubble { color: white; border-bottom-right-radius: 4px; }
  .bot-message .message-bubble { background: #e9ecef; color: #333; border-bottom-left-radius: 4px; }
  .chat-input {
    padding: 15px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 8px;
    background: white;
  }
  .chat-input input {
    flex: 1;
    border: 1px solid #d1d5db;
    border-radius: 20px;
    padding: 10px 14px;
    font-size: 13px;
  }
  .chat-input button {
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>
