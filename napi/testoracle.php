<?php  
     global $conn_oracle, $conn;
     $tns = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.1.11)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=navana)))";
     $db_username = "it_sayeed";
     $db_password = "nrel1920";
     try{
         $conn_oracle = new PDO("oci:dbname=".$tns,$db_username,$db_password);
         //echo "Connected successfully";
     }
     catch(PDOException $e){
         echo ($e->getMessage());
     }


    $s_query = "SELECT com.get_unique_id('HR_EMPLOYEE_LEAVE','LEAVE_APPL_NO')  genId FROM DUAL";

    $stmt1 = $conn_oracle->query($s_query);
    $row = $stmt1->fetch(PDO::FETCH_ASSOC);
    print_r($row['genId']);
    //print_r($conn_oracle->errorInfo())

    // while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
    //     echo $row;
    // }
    


 ?>   

