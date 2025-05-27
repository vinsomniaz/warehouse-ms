<?php

    require_once '../config/database.php';

    class Servicio{
        private $conn;

        public function __construct(){
            $database = new Database();
            $this->conn = $database->getConnection();
        }

        public function crear($nombre_cliente, $supervisor, $fecha_servicio, $encargado_carga = null, $notas = null){
            try{
                $query = "INSERT INTO servicios(nombre_cliente, supervisor, fecha_servicio, encargado_carga, notas) VALUES (:nombre_cliente, :supervisor, :fecha_servicio, :encargado_carga, :notas)";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':nombre_cliente', $nombre_cliente);
                $stmt->bindParam(':supervisor', $supervisor);
                $stmt->bindParam(':fecha_servicio', $fecha_servicio);
                $stmt->bindParam(':encargado_carga', $encargado_carga);
                $stmt->bindParam(':notas', $notas);

                if($stmt->execute()){
                    return [
                        'success' => true,
                        'id' => $this->conn->lastInsertId(),
                        'message' => 'Servicio creado exitosamente'
                    ];
                }
                return [
                    'success' => false, 
                    'message' => 'Error al crear el servicio'
                ];
            } catch(PDOException $e){
                return[
                    'success' => false, 
                    'message' => 'Error de base de datos: '.$e->getMessage()
                ];
            }
        }

        public function listar($busqueda = '', $limit = 50, $offset = 0){
            try{
                $whereClause = '';
                $params = [];

                if(!empty($busqueda)){
                    $whereClause = "WHERE nombre_cliente LIKE :busqueda OR supervisor LIKE :busqueda OR fecha_servicio LIKE :busqueda";
                    $params[':busqueda'] = '%' . $busqueda . '%';
                }

                $query = "SELECT s.*, COUNT(p.id) as total_productos, COUNT (CASE WHEN p.retirador = 1 THEN 1 END) as productos_retirados FROM servicios s LEFT JOIN productos p ON s.id = p.id:servicio $whereClause GROUP BY s.id ORDER BY s.fecha_servicio DESC, s.id DESC LIMIT :limit OFFSET :offset";

                $stmt = $this->conn->prepare($query);

                foreach($params as $key => $value){
                    $stmt->bindValue($key, $value);
                }

                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

                $stmt->execute();

                return[
                    'success' => true, 
                    'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
                ];

            } catch (PDOException $e){
                return[
                    'success' => false, 
                    'message' => 'Error al obtener servicios: '.$e->getMessage()
                ];
            }
        }

        public function obtenerPorId($id) {
            try {
                $query = "SELECT * FROM servicios WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                
                if($stmt->rowCount() > 0) {
                    return [
                        'success' => true,
                        'data' => $stmt->fetch(PDO::FETCH_ASSOC)
                    ];
                }
                return [
                    'success' => false, 
                    'message' => 'Servicio no encontrado'
                ];

            } catch(PDOException $e) {
                return [
                    'success' => false, 
                    'message' => 'Error de base de datos: ' . $e->getMessage()
                ];
            }
        }

        public function actualizar($id, $nombre_cliente, $supervisor, $fecha_servicio, $encargado_carga = null, $notas = null){
            try {
                $query = "UPDATE servicios SET nombre_cliente = :nombre_cliente, supervisor = :supervisor, fecha_servicio = :fecha_servicio, encargado_carga = :encargado_carga, notas = :notas WHERE id = :id";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':nombre_cliente', $nombre_cliente);
                $stmt->bindParam(':supervisor', $supervisor);
                $stmt->bindParam(':fecha_servicio', $fecha_servicio);
                $stmt->bindParam(':notas', $encargado_carga);

                if($stmt->execute()){
                    return [
                        'success' => true, 
                        'message' => 'Servicio actualizado exitosamente'
                    ];
                }
                return[
                    'success' => false, 
                    'message' => 'Error al actualizar el servicio'
                ];

            } catch(PDOException $e) {
                return[
                    'success' => false, 
                    'message' => 'Error de base de datos: '.$e->getMessage()
                ];
            }
        }

        public function obtenerResumen($id) {
            try {
                $query = "SELECT s.*,
                         COUNT(p.id) as total_productos,
                         COUNT(CASE WHEN p.retirado = 0 THEN 1 END) as productos_activos,
                         COUNT(CASE WHEN p.retirado = 1 THEN 1 END) as productos_retirados,
                         COUNT(CASE WHEN p.estado = 'excelente' AND p.retirado = 0 THEN 1 END) as productos_excelentes,
                         COUNT(CASE WHEN p.estado = 'normal' AND p.retirado = 0 THEN 1 END) as productos_normales,
                         COUNT(CASE WHEN p.estado = 'incidencia' AND p.retirado = 0 THEN 1 END) as productos_incidencias
                         FROM servicios s 
                         LEFT JOIN productos p ON s.id = p.id_servicio 
                         WHERE s.id = :id
                         GROUP BY s.id";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                
                if($stmt->rowCount() > 0) {
                    return [
                        'success' => true,
                        'data' => $stmt->fetch(PDO::FETCH_ASSOC)
                    ];
                }
                return ['success' => false, 'message' => 'Servicio no encontrado'];
            } catch(PDOException $e) {
                return ['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()];
            }
        }

    }
    
?>