<?php

    header('Content-Type: application/json');
    require_once '../classes/Auth.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $input = json_decode(file_get_contents('php://input'),true);

        $usuario = trim($input['usuario'] ?? '');
        $clave = trim($input['clave'] ??'');

        if(empty($usuario) || empty($clave)){
            echo json_encode([
                'success' => false,
                'message' => 'Usuario y contraseña con requeridos'
            ]);
            exit;
        }

        $auth = new Auth();

        if($auth->login($usuario,$clave)){
            echo json_encode([
                'success'=> true,
                'message'=> 'Ingreso exitoso, entrando al sistema...',
                'redirect'=> 'dashboard.php'
            ]);
        } else {
            echo json_encode([
                'success'=> false,
                'message'=> 'Usuario o contraseña incorrectos'
            ]);
        }
    } else {
        echo json_encode([
            'success'=> false,
            'message'=> 'Método no permitido'
        ]);
    }

?>