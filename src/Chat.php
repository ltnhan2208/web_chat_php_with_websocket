<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require dirname (__DIR__) . '/model/ChatUser.php';
require dirname (__DIR__) . '/model/ChatRoom.php';
require dirname (__DIR__) . '/model/PrivateChat.php';

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        echo "Server started\n";
        $this->clients->attach($conn);

        $queryString = $conn->httpRequest->getUri()->getQuery();
        //$queryString = $_GET['user_token']

        parse_str($queryString, $queryArray);
       // $queryArray =[ 'user_token',..... ];

        $user_object = new \ChatUser;
        $user_object->setUserToken($queryArray['token']);
        $user_object->setConnectionId($conn->resourceId);

        $user_object->update_connection_id();

        $user_data = $user_object->get_user_data_by_token();

        $user_data = $user_object->get_user_data_by_token();
        $user_id = $user_data['user_id'];
        $data['status_type'] = "Online";
        $data['userId'] = $user_id;

        foreach ($this->clients as $client) {
            $client->send(json_encode($data));
        }

        echo "New connection! ({$conn->resourceId}})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg, true);
        $data['datetime'] = date("d-m-Y h:i:s");

        if($data['command']=="private")
        {
            $private_chat = new \PrivateChat;
            $private_chat->setFromId($data['userId']);
            $private_chat->setToId($data['receiver_id']);
            $private_chat->setChatMessage($data['msg']);
            $private_chat->setCreateDate(date("Y-m-d h:i:s"));
            $private_chat->setStatus('Yes');
            $chat_message_id = $private_chat->save_chat();

            $user_object = new \ChatUser;
            $user_object->setUserId($data['userId']);
            $sender_user_data = $user_object->get_user_data_by_id();

            $user_object->setUserId($data['receiver_id']);
            $receiver_user_data = $user_object->get_user_data_by_id();

            $sender_user_name = $sender_user_data['user_name'];
            $receiver_user_name = $receiver_user_data['user_name'];

            $receiver_connection_id = $receiver_user_data['user_connection_id'];

            foreach ($this->clients as $client) {
            // if ($from !== $client) {
            //  The sender is not the receiver, send to each client connected
            //     $client->send($msg);
                if($from == $client)
                {
                    $data['from'] = "Me";
                }
                else
                {
                    $data['from'] = $sender_user_name;
                }
                if($client->resourceId == $receiver_connection_id || $from == $client)
                {
                     $client->send(json_encode($data));
                }
                else
                {
                     $private_chat->setStatus('No');
                     $private_chat->setChatId($chat_message_id);
                     $private_chat->update_chat_status();
                }
            }

        }
        else
        {
            $room_chat = new \ChatRoom;
            $room_chat->setUserId($data['userId']);
            $room_chat->setMessage($data['msg']);
            $room_chat->setMessageCreate(date("Y-m-d h:i:s"));
            $room_chat->save_chat();
        }
      
        }
    

    public function onClose(ConnectionInterface $conn) {
        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $queryArray);
        $user_object = new \ChatUser;
        $user_object->setUserToken($queryArray['token']);
        $user_data = $user_object->get_user_data_by_token();

        $user_id = $user_data['user_id'];
        $data['status_type'] = "Offline";
        $data['userId'] = $user_id;

        foreach ($this->clients as $client) {
            $client->send(json_encode($data));
        }

        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
?>