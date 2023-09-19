<?php  
    global $conn_oracle, $conn;
	$tns = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.1.11)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=navana)))";
	$db_username = "it_sayeed";
	$db_password = "navana123";
	try{
        $conn_oracle = new PDO("oci:dbname=".$tns,$db_username,$db_password);
        //echo "Connected successfully";
	}
	catch(PDOException $e){
	    echo ($e->getMessage());
    }

    $db_name = "nrel_portal";
    $mysql_user = "root";
    $mysql_pass = "";
    $server_name = "localhost";

    try {
        $conn = new PDO("mysql:host=$server_name; dbname=$db_name", $mysql_user, $mysql_pass);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    
    $s_query = "SELECT 
    EMPLOYEE_ID, 
    EMPLOYEE_NAME, 
    navana_erp.com.GET_DESIGNATION (DESIGNATION_ID) DESIGNATION,
    navana_erp.com.GET_DEPARTMENT (DEPARTMENT_ID) DEPARTMENT_NAME,  
    navana_erp.com.GET_PROJECT (PLACE_OF_WORK) PROJECT_NAME, 
    PLACE_OF_WORK PROJECT_ID,
    DEPARTMENT_ID,
    DECODE (EMPLOYEE_STATUS, 'R', 'Regular', 'M', 'Master Role', 'P', 'Probationary') STATUS,
    EMPLOYEE_STATUS,
    DECODE (EMP_MANAGEMENT_STAFF, 'M', 'Management', 'N', 'Non Management') MANAGEMENT_S,
    navana_erp.com.GET_COMPANY (COMPANY_ID) COMPANY,
    CASE 
        WHEN MOBILE IS NULL THEN 
            CASE 
                WHEN CONTACT_NUMBER IS NULL THEN ' '
                WHEN CONTACT_NUMBER = '0' OR CONTACT_NUMBER = 'N/A' OR CONTACT_NUMBER = 'NA' THEN ' '
                ELSE CONTACT_NUMBER
            END
        WHEN MOBILE = '0' OR MOBILE = 'N/A' OR MOBILE = 'NA' THEN ' '
        ELSE MOBILE
    END MOBILE,
    ' ' PABX,
    CASE 
        WHEN EMAIL IS NULL THEN ' '
        WHEN EMAIL = '0' OR EMAIL = 'N/A' OR EMAIL = 'NA' THEN ' '
        ELSE EMAIL
    END EMAIL,
    DESIGNATION_ID
FROM HR_EMPLOYEE 
WHERE 1 = 1
    AND EMPLOYEE_ID NOT IN (SELECT   PREVIOUS_EMPID FROM   HR_EMPLOYEE WHERE   previous_empid IS NOT NULL)
    -- AND EMPLOYEE_ID NOT IN ('1237', '1314')
    -- AND EMPLOYEE_STATUS NOT IN ('E', 'RG', 'T', 'D', 'A')
    AND EMP_MANAGEMENT_STAFF NOT IN ('N')
ORDER BY EMPLOYEE_ID ASC";

    $stmt1 = $conn_oracle->query($s_query);


    while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {


        // $sql_emp_check = $conn->prepare("SELECT employees.emp_id FROM employees WHERE emp_id = '$EMPLOYEE_ID'");
        // $sql_emp_check->execute();

        $sql_emp_check = $conn->prepare("SELECT emp_id FROM employees WHERE emp_id = :EMPLOYEE_ID");
        $sql_emp_check->execute(array(':EMPLOYEE_ID' => $row['EMPLOYEE_ID']));
        //$rows = $sql_emp_check->fetch(PDO::FETCH_ASSOC);

       // print_r($rows); echo "<br>";
       while($result = $sql_emp_check->fetch(PDO::FETCH_ASSOC)) {

            if($result['emp_id'] = $row['EMPLOYEE_ID']){
                var_dump($result['emp_id']);
            }

                
        }


        // $sql_emp_insert = $conn->prepare("INSERT INTO employees
        // (emp_id, name, email, designation, designation_id, department,  department_id, project_name, project_id, status, avater, emp_status, management_s, company, mobile, pabx) 
        // VALUES (:emp_id, :name, :email, :designation, :designation_id, :department,  :department_id, :project_name, :project_id, :status, :avater, :emp_status, :management_s, :company, :mobile, :pabx)");
        // $sql_emp_insert->execute([
        //     'emp_id'            => $row['EMPLOYEE_ID'],
        //     'name'              => $row['EMPLOYEE_NAME'],
        //     'email'             => $row['EMAIL'],
        //     'designation'       => $row['DESIGNATION'],
        //     'designation_id'    => $row['DESIGNATION_ID'],
        //      'department'       => $row['DEPARTMENT_NAME'],
        //     'department_id'     => $row['DEPARTMENT_ID'],
        //     'project_name'      => $row['PROJECT_NAME'],
        //     'project_id'        => $row['PROJECT_ID'],
        //     'status'            => $row['STATUS'],
        //     'avater'            => '',
        //     'emp_status'        => $row['EMPLOYEE_STATUS'],
        //     'management_s'      => $row['MANAGEMENT_S'],
        //     'company'           => $row['COMPANY'],
        //     'mobile'            => $row['MOBILE'],
        //     'pabx'              => $row['PABX'],

        // ]);

        // echo "New record created successfully";

        // if ( $result = $sql_emp_check->setFetchMode(PDO::FETCH_ASSOC)){

        //    $sql_emp_insert = "INSERT INTO employees
        //     (emp_id, name, email, designation, designation_id, department,  department_id, project_name, project_id, status, avater, emp_status, management_s, company, mobile, pabx) 
        //     VALUES ('$EMPLOYEE_ID', '$EMPLOYEE_NAME', '$EMAIL', '$DESIGNATION', '$DESIGNATION_ID', '', '$DEPARTMENT_ID', '$PROJECT_NAME', '$PROJECT_ID', '$STATUS', ' ', '$EMPLOYEE_STATUS', '$MANAGEMENT_S', '$COMPANY', '$MOBILE', '$PABX')";
        //     $conn->exec($sql_emp_insert);
        //     echo "New record created successfully";
            
        // } else {
        //     $sql_emp_update = "UPDATE employee_info SET DESIGNATION = '$DESIGNATION', DEPARTMENT_NAME = '$DEPARTMENT_NAME', PROJECT_NAME = '$PROJECT_NAME', PROJECT_ID = '$PROJECT_ID', DEPARTMENT_ID = '$DEPARTMENT_ID', STATUS = '$STATUS', EMPLOYEE_STATUS = '$EMPLOYEE_STATUS', MANAGEMENT_S = '$MANAGEMENT_S', COMPANY = '$COMPANY', MOBILE = '$MOBILE', EMAIL = '$EMAIL', DESIGNATION_ID = '$DESIGNATION_ID' WHERE EMPLOYEE_ID = '$EMPLOYEE_ID'";
        //     $result_emp_update = mysqli_query($con, $sql_emp_update);
        // }

    }

    
?>