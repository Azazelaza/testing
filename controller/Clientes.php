<?php
class Clientes
{
    static function select($fields)
    {
        $response = fetch_all("SELECT * FROM clientes c LEFT JOIN direcciones d on d.cliente_id = c.id");
        echo json_encode($response);
    }
    static function create($fields)
    {

        $nombre = $fields['nombre'];
        $apellido = $fields['apellido'];
        $email = $fields['email'];
        $telefono = $fields['telefono'];
        $direccion = $fields['direccion'];
        $estado = $fields['estado'];
        $ciudad = $fields['ciudad'];
        $codigo_postal = $fields['codigo_postal'];
        $error = "";

        if (empty($nombre)) {
            $error = 'El nombre es obligatorio';
        } elseif (empty($apellido)) {
            $error = 'El apellido es obligatorio';
        } elseif (empty($email)) {
            $error = 'El email no es válido';
        } elseif (empty($telefono)) {
            $error = 'El teléfono no es válido';
        } elseif (empty($direccion)) {
            $error = 'La dirección es obligatoria';
        } elseif (empty($estado)) {
            $error = 'El estado es obligatorio';
        } elseif (empty($ciudad)) {
            $error = 'La ciudad es obligatoria';
        } elseif (empty($codigo_postal)) {
            $error = 'El codigo postal es obligatorio';
        }
        if ($error != '') {
            http_response_code(400);
            echo json_encode($error);
            return;
        }

        execute_query("INSERT INTO clientes SET nombre ='$nombre', apellido='$apellido', email='$email', telefono='$telefono'");
        $cliente_id = last_insert_id();
        execute_query("INSERT INTO direcciones SET cliente_id='$cliente_id',direccion='$direccion',estado='$estado',ciudad='$ciudad',codigo_postal='$codigo_postal'");
        echo json_encode("Se realizo correctamente la actualizacion de datos");
    }
    static function update($fields)
    {
        $fields = (array)json_decode($fields);
        $id = isset($fields['id']) ? $fields['id'] : null;

        if (is_null($id)) {
            echo json_encode("ID obligatorio");
            return;
        }

        $sql = "";
        $sql2 = "";
        $nombre = isset($fields['nombre']) ? $fields['nombre'] : null;
        $apellido = isset($fields['apellido']) ? $fields['apellido'] : null;
        $email = isset($fields['email']) ? $fields['email'] : null;
        $telefono = isset($fields['telefono']) ? $fields['telefono'] : null;
        $direccion = isset($fields['direccion']) ? $fields['direccion'] : null;
        $estado = isset($fields['estado']) ? $fields['estado'] : null;
        $ciudad = isset($fields['ciudad']) ? $fields['ciudad'] : null;
        $codigo_postal = isset($fields['codigo_postal']) ? $fields['codigo_postal'] : null;
        if ($nombre !== null) {
            $sql .= "nombre='$nombre',";
        }
        if ($apellido !== null) {
            $sql .= "apellido='$apellido',";
        }
        if ($email !== null) {
            $sql .= "email='$email',";
        }
        if ($telefono !== null) {
            $sql .= "telefono='$telefono',";
        }
        if ($direccion !== null) {
            $sql2 .= "direccion='$direccion',";
        }
        if ($estado !== null) {
            $sql2 .= "estado='$estado',";
        }
        if ($ciudad !== null) {
            $sql2 .= "ciudad='$ciudad',";
        }
        if ($codigo_postal !== null) {
            $sql2 .= "codigo_postal='$codigo_postal',";
        }
        $sql = rtrim($sql, ',');
        $sql2 = rtrim($sql2, ',');
        if ($sql != "") {
            execute_query("UPDATE clientes SET $sql where id='$id'");
        }
        if ($sql2 != "") {
            execute_query("UPDATE direcciones SET $sql2 where client_id='$id'");
        }
        echo json_encode('Se actualizaron todos los campos');
    }
    static function delete($fields)
    {
        $fields = (array)json_decode($fields);
        $id = isset($fields['id']) ? $fields['id'] : null;

        if (is_null($id)) {
            echo json_encode("ID obligatorio");
            return;
        }

        execute_query("DELETE FROM clientes WHERE id='$id'");
        execute_query("DELETE FROM direcciones WHERE cliente_id='$id'");
        echo json_encode('Se eliminaron los campos');
    }
}
