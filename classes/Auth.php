<?php

    require_once '../config/database.php';

    class Auth{
        private $conn;

        public function __construct() {
            $database = new Database();
            $this->conn = $database->getConnection();
        }

        public function login($usuario, $clave){
            try{
                $query = "SELECT id, usuario, clave FROM usuarios WHERE usuario = :usuario";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':usuario', $usuario);
                $stmt->execute();

                if($stmt->rowCount() > 0){
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if(password_verify($clave, $row['clave'])){
                        session_start();
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['username'] = $row['usuario'];
                        $_SESSION['logged_in'] = true;
                        return true;
                    }
                }
                return false;
            } catch(PDOException $exception){
                return false;
            }
        }

        public function logout(){
            session_start();
            session_unset();
            session_destroy();
            return true;
        }

        public function isLoggedIn(){
            session_start();
            return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
        }

        public function requireLogin(){
            if(!$this->isLoggedIn()){
                header('Location: ../login.php');
                exit();
            }
        }
    }

?>