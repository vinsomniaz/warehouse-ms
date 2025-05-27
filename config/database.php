<?php
    class Database{
        private $host = 'localhost';
        private $dbname = 'almacendb';
        private $username = 'root';
        private $password = '';
        private $conn;

        public function getConnection(){
            $this->conn = null;

            try{
                $this->conn = new PDO(
                    "mysql:host=".$this->host.";dbname=".$this->dbname.";charset=utf8",
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $exception){
                echo "Error de conexión: ". $exception->getMessage();
            }
            return $this->conn;
        }

    }
?>