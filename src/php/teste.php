<?php 
require_once '../models/Database.php';

$database = new Database();
$conn = $database->connect();

if($conn){
    echo("Teste Feito com sucesso");
}

?>