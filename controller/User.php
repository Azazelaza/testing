<?php
class User
{
    static function login($fields)
    {
        if (isset($fields['username']) && isset($fields['password'])) {
            $username = $fields['username'];
            $password = $fields['password'];
            $user = fetch("SELECT * FROM users WHERE username = '$username' AND password = '$password'");

            if (!$user) {
                http_response_code(401);
                $response = array('success' => false, 'message' => 'Nombre de usuario o contraseña incorrectos');
            } else {
                $token = base64_encode(random_bytes(64));
                execute_query("UPDATE users SET token = '$token' WHERE username = '$username' AND password = '$password'");
                http_response_code(200);
                $response = array('success' => true, 'token' => $token);
            }
        } else {
            http_response_code(400);
            $response = array('success' => false, 'message' => 'Nombre de usuario y contraseña requeridos');
        }

        echo json_encode($response);
    }

    static function checkLogin($token)
    {
        $user = fetch("SELECT * FROM users WHERE token='$token'");
        if (!$user) {
            die('no se inicio sesion');
        }
        return $user;
    }
}
