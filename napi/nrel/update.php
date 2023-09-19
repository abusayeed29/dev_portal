<?php
	header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../nrel/config/database.php';
    include_once '../nrel/class/leave.php';

    $database = new Database();
    $db = $database->getConnection();

    $items = new Leave($db);

    $items->employee_id     = isset($_GET['employee_id']) ? $_GET['employee_id'] : die();
    $items->leave_type      = isset($_GET['leave_type']) ? $_GET['leave_type'] : die();
    $items->taken_days      = isset($_GET['taken_days']) ? $_GET['taken_days'] : die();
    $items->cur_bal         = isset($_GET['cur_bal']) ? $_GET['cur_bal'] : die();
    $items->company_id      = isset($_GET['company_id']) ? $_GET['company_id'] : die();
    $items->leave_id        = isset($_GET['leave_id']) ? $_GET['leave_id'] : die();
    $items->from_date       = isset($_GET['from_date']) ? date('d-M-Y',strtotime($_GET['from_date'])) : die();
    $items->to_date         = isset($_GET['to_date']) ? date('d-M-Y',strtotime($_GET['to_date'])) : die();
    $items->days            = isset($_GET['days']) ? $_GET['days'] : die();
    $items->location        = isset($_GET['location']) ? $_GET['location'] : die();
    $items->reason          = isset($_GET['reason']) ? $_GET['reason'] : die();
    $items->created_at      = isset($_GET['created_at']) ? $_GET['created_at'] : die();

    if($items->updateLeave()){
        echo json_encode("Employee data updated.");
    } else{
        echo json_encode("Data could not be updated");
    }
    

?>