@extends('layouts.master')
@section('title','Attendence')
@push('css')

@endpush

@section('content')

<?php  
    global $conn_oracle, $con;
	
	$tns = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.1.11)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=navana)))";
	
	$db_username = "it_sayeed";
	$db_password = "nrel1920";
    
	try{
	    $conn_oracle = new PDO("oci:dbname=".$tns, $db_username, $db_password);
	}
	catch(PDOException $e){
	    echo ($e->getMessage());
	}

	if ($conn_oracle) {
		echo "Database Connected";	
	} else {
		echo "Database connection failed";
	}
	
	$stmt = $conn_oracle->query("SELECT * FROM HR_EMPLOYEE_HISTORY");
	$users = $stmt->fetchAll();
	foreach ($users as $row) {
		echo $row['EMPLOYEE_NAME']."<br />\n";
	}

    
?>


@endsection

@push('js')

@endpush