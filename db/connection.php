<?php
//* Datos de conexión a la base de datos
$host = 'localhost'; //* Cambiar si la base de datos está en un servidor remoto
$dbname = 'Testing';
$username = 'aza'; //* Cambiar por tu nombre de usuario de la base de datos
$password = 'azasql1'; //* Cambiar por tu contraseña de la base de datos

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

function colonize($req, $sql = false)
{
    $req2 = array();

    foreach ($req as $key => $value) {
        if ($sql) {
            if (strpos($sql, ":$key") === false) continue;
        }
        $req2[":$key"] = $value;
    }

    return $req2;
}
function execute_query($sql, $req = false)
{
    global $dbh, $pdo_error;
    $pdo_error = false;
    if (!$dbh) return false;
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (stripos($sql, " as rows") !== false) {
        $sql = str_ireplace(" as rows", " as `rows`", $sql);
    }

    $sth = $dbh->prepare($sql);
    if ($req) {
        $req2 = colonize($req, $sql);
        try {
            $sth->execute($req2);
        } catch (PDOException $e) {
            $pdo_error = array(
                "error" => $e->getMessage(),
                "code" => $e->getCode(),
                "sql" => $sql
            );
        }
    } else {
        try {
            $sth->execute();
        } catch (PDOException $e) {
            $pdo_error = array(
                "error" => $e->getMessage(),
                "code" => $e->getCode(),
                "sql" => $sql
            );
        }
    }

    return $sth;
}
function fetch_all($sth)
{
    return execute_query($sth)->fetchAll(PDO::FETCH_ASSOC);
}
function last_insert_id()
{
    global $dbh;
    return $dbh->lastInsertId();
}
function fetch($sth)
{
    return execute_query($sth)->fetch(PDO::FETCH_ASSOC);
}
