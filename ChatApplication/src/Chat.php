<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {

    private $clients;
    private $db_con;

    public function __construct($conn){
        $this->clients = array();
        $this->db_con = $conn; 
    }

    public function onOpen(ConnectionInterface $conn) {
        array_push($this->clients, $conn);
        echo "New Connection Received!\n";
        echo "Sending old messages\n";
        $conn->send($this->getOldMessages());
        echo "old messages sent!\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $msgJson = json_decode($msg);

        $this->insertMessage($msgJson->sender,$msgJson->message);

        foreach($this->clients as $client){
                $client->send($msg);
        }
    }

    public function onClose(ConnectionInterface $conn) {
        unsetValue($this->clients, $conn);
        echo "Connection Close\n";
    }

    private function unsetValue(array $array, $value, $strict = TRUE) {
        if(($key = array_search($value, $array, $strict)) !== FALSE) {
            unset($array[$key]);
        }
        return $array;
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    private function insertMessage($name,$message){
        $sql = "INSERT INTO messages VALUES (0,'$name','$message');";
        mysqli_query($this->db_con,$sql);
    }

    private function getOldMessages(){
        $msgs = array();
        if ($result = $this->db_con->query("SELECT * FROM messages")) {

            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    $msgs[] = $row;
            }
        }
        $result->close();
        return json_encode($msgs);
    }
}