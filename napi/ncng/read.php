<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once '../nwel/config/database.php';
    include_once '../nwel/class/leave.php';

    $database = new Database();
    $db = $database->getConnection();

    $items = new Leave($db);

    $items->employee_id = isset($_GET['emp_id']) ? $_GET['emp_id'] : die();
    
    $stmt = $items->getLeaves();
    //$itemCount = $stmt->rowCount();
    //var_dump($stmt);
   echo json_encode($stmt);


?>