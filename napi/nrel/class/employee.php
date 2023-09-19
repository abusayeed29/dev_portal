<?php
    class Employee{

        // Connection
        private $conn;
        // Table
        private $db_table = "HR_EMPLOYEE";

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }


        // Employee Data 
        public function getEmployees(){
            $start_date = date("Y-m-d");
            $end_date = date("Y-m-d", strtotime("-20 days"));
            $sql = "SELECT * FROM ALL_HR_EMPLOYEE_2 
                    WHERE 1 = 1
                        AND EMPLOYEE_ID NOT IN (SELECT   PREVIOUS_EMPID FROM   HR_EMPLOYEE WHERE   previous_empid IS NOT NULL)
                        AND EMPLOYEE_STATUS NOT IN ('E', 'RG', 'T', 'D', 'A')
                        AND EMP_MANAGEMENT_STAFF NOT IN ('N')
                        AND joining_date BETWEEN '".$end_date."' AND '".$start_date."'
                        ORDER BY joining_date DESC";

                    $stmt = $this->conn->prepare($sql);

                    if($stmt->execute()){
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        return $result;
                    }
                    return false;

            
        }



    }

?>