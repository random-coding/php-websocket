<?php
    namespace App;
    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;

    require __DIR__ . '../vendor/autoload.php';

    class SocketController implements MessageComponentInterface
    {
        protected $users;

        public function __construct() {
            $this->users = new \SplObjectStorage;
        }

        /**
         * 
        */
        public function onOpen(ConnectionInterface $conn) {
            // Store the new connection to send messages to later
            $user = new \stdClass();
            $user->conn = $conn;
            $user->user = '';
            $user->group = '';
            $this->users->attach($user);
            echo "New connection! ({$conn->resourceId})\n";
        }

        /**
         * 
         */
        public function onMessage(ConnectionInterface $from, $msg) {
            // number of users except the sender
            $numRecv = count($this->users) - 1;

            // convert message
            $message = json_decode($msg);

            // save information of the sender
            foreach ($this->users as $user) {
                if ($from === $user->conn) {
                    $user->user = $message->user;
                    $user->group = $message->group;
                }
            }

            echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
                , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

            foreach ($this->users as $user) {
                if ($from !== $user->conn) {
                    // The sender is not the receiver, send to each user connected
                    $user->conn->send($msg);
                }
            }
        }

        /**
         * 
         */
        public function onClose(ConnectionInterface $conn) {
            $userClose = '';

            // search the user
            foreach ($this->users as $user) {
                if ($conn === $user->conn) {
                    // The sender is not the receiver, send to each user connected
                    $userClose = $user;
                }
            }

            // The connection is closed, remove it, as we can no longer send it messages
            $this->users->detach($userClose);

            echo "Connection {$conn->resourceId} has disconnected\n";
        }

        /**
         * 
         */
        public function onError(ConnectionInterface $conn, \Exception $e) {
            echo "An error has occurred: {$e->getMessage()}\n";
            $conn->close();
        }
    }