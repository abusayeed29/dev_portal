<?php  

	global $conn_oracle, $con;
	$tns = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.1.11)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=navana)))";

	$db_username = "it_sayeed";
	$db_password = "navana123";
	try{
        $conn_oracle = new PDO("oci:dbname=".$tns,$db_username,$db_password);
        //echo "Database Connected";
	}
	catch(PDOException $e){
	    echo ($e->getMessage());
    }
    
    $db_name = "nreal_portal";
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

?>