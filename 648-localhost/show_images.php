<?php

include_once './db_connect.php';

session_start();
$id = $_SESSION['image_id'];

try {
    $connection = new PDO("mysql:host=$servername;dbname=$db_name", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql_get_images = "SELECT * FROM images WHERE parent_id='$id'";
    $statement = $connection->prepare($sql_get_images);
    $statement->execute();

    $results = $statement->fetchAll();

    

} catch (Exception $exc) {
    echo $exc->getTraceAsString();
}
