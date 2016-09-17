<?php

class MysqlClass{
    private $hostname;
    private $database;
    private $username;
    private $password;
    private $port = 3306;
    private $Connection;

    //constructions
    public function __construct($hostname, $database, $username, $password, $port) {
        $this->set_hostname($hostname);
        $this->set_database($database);
        $this->set_username($username);
        $this->set_password($password);
        $this->set_port($port);
        $this->set_Connection($this->open_Connection());
    }

    private function get_Connection() {
        return $this->Connection;
    }

    //GET
    public function get_hostname() {
        return $this->hostname;
    }

    public function get_database() {
        return $this->database;
    }

    public function get_username() {
        return $this->username;
    }

    public function get_password() {
        return $this->password;
    }

    public function get_port() {
        return $this->port;
    }

    //SET
    private function set_Connection($Connection) {
        $this->Connection = $Connection;
    }

    public function set_hostname($hostname) {
        $this->hostname = $hostname;
    }

    public function set_database($database) {
        $this->database = $database;
    }

    public function set_username($username) {
        $this->username = $username;
    }

    public function set_password($password) {
        $this->password = $password;
    }

    public function set_port($port) {
        $this->port = $port;
    }
	//Connections  methods
    private function open_Connection() {
        $Connection = new mysqli($this->get_hostname(), $this->get_username(), $this->get_password(), $this->get_database(), $this->get_port());
        if ($Connection->connect_errno) {
            echo "Failed to connect to MySQL: (" . $Connection->connect_errno . ") " . $Connection->connect_error;
        }
        return $Connection;
    }
}
?>
