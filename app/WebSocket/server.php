<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require dirname(__DIR__, 2) . '/vendor/autoload.php';
require 'ChatServer.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    8080 
);

echo "Servidor WebSocket iniciado en el puerto 8080\n";
$server->run();