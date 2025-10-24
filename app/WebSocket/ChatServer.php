<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Verificar si el mensaje es una imagen o audio en base64
        if (strpos($msg, 'data:image') === 0 || strpos($msg, 'data:audio') === 0) {
            // Para datos binarios, enviamos el mensaje completo
            foreach ($this->clients as $client) {
                // Verificar si la conexión está activa antes de enviar
                if ($client !== $from) { // No reenviar al remitente
                    try {
                        $client->send($msg);
                    } catch (\Exception $e) {
                        // Manejar error de envío
                        echo "Error al enviar mensaje: " . $e->getMessage() . "\n";
                    }
                }
            }
            // No es necesario hacer nada más, el remitente ya mostró su propia imagen
        } else {
            // Para mensajes de texto normales
            foreach ($this->clients as $client) {
                if ($client !== $from) { // No reenviar al remitente
                    try {
                        $client->send($msg);
                    } catch (\Exception $e) {
                        echo "Error al enviar mensaje: " . $e->getMessage() . "\n";
                    }
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}