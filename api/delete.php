<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    $configurations = include ("../appConfig.php");
    require_once ('../lib/Database.php');

    $db = new Database($configurations);

    $data = json_decode(file_get_contents("php://input"));
    
    
    $id = $data->id;

    echo var_dump($id);

    $db->delete_record($id);
?>