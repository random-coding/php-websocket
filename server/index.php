<?php
    namespace App;
    use Ratchet\Server\IoServer;
    use Ratchet\Http\HttpServer;
    use Ratchet\WebSocket\WsServer;
    require_once './controllers/SocketController.php';

    require __DIR__ . './vendor/autoload.php';

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new SocketController()
            )
        ),
        8080
    );
    $server->run();
