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


    $s_query = "SELECT FILE_ID, FILE_ FROM ALL_BLOB WHERE FILE_ID = '2292'";

    $stmt1 = $conn_oracle->query($s_query);
    //$stmt1 = oci_parse($conn_oracle, $s_query);

    while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
        //echo $row['FILE_ID'];
        $imagedata = stream_get_contents($row['FILE_']);
		
		echo '<img src="data:image/jpeg;base64,' . base64_encode($imagedata) . '" width="200" height="200">';
    }
    

	//code
	/*$stmt = $dbcon->prepare( 'SELECT CREATIVE FROM creative WHERE id = 10314612'); 
	$stmt->execute(); 
	$res = $stmt->fetchAll( PDO::FETCH_ASSOC ); 

	for( $i=0; $i<count($res); $i++ ){ 
	  $data = stream_get_contents( $res[$i]['CREATIVE'] ); 
	} */

 ?>   

