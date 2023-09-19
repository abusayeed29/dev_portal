<?php 
    class Database {

        private $tns = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.1.11)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=NWELDB)))";
        private $username = "NAVANA_ERP";
        private $password = "Admin123";

        public $conn;

        public function getConnection(){
            $this->conn = null;
            try{
				$this->conn = new PDO("oci:dbname=".$this->tns, $this->username, $this->password);
                $this->conn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Database could not be connected: " . $exception->getMessage();
            }
            return $this->conn;
        }
    }  
?>