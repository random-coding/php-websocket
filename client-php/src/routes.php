<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    $app->post('/', function (Request $request, Response $response, array $args) {
        try {
            // decode data from the caller
            $GLOBALS['body'] = json_decode($request->getBody());
            /**
             * Send to Socket
             */
            \Ratchet\Client\connect('ws://127.0.0.1:8683')->then(function($conn) {
                $conn->send(
                    json_encode(array(
                        "user" => $GLOBALS['body']->user,
                        "group" => $GLOBALS['body']->group,
                    ))
                );
                $conn->close();
            }, function ($e) {
                $this->logger->info("Can not connect: {$e->getMessage()}");
            });
            return json_encode(array("status" => true));
        } catch (Exception $e) {
            $this->logger->info("Exception: ", $e->getMessage());
            return json_encode(array("status" => false));
        }
    });