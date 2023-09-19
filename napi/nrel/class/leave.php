<?php
    class Leave{

        // Connection
        private $conn;

        // Table
        private $db_table = "HR_EMPLOYEE_YEARLY_LEAVE_2";

        // Columns
        public $employee_id;
        public $leave_type;
        public $taken_days;
        public $cur_bal;
        public $company_id;
        public $leave_id;
        public $from_date;
        public $to_date;
        public $days;
        public $location;
        public $reason;
        public $created_at;

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        // GET ALL BY EMP_ID
        public function getLeaves(){
            $sqlQuery = "SELECT  
					COMPANY_ID,
					leave_id leave_type_id,
					leave_days,
					carried_days,
					taken_days,
					cur_bal,
					leave_year, 
					employee_id, 
					to_char(insert_date,'YYYY-MM-DD') created_at, 
					to_char(update_date,'YYYY-MM-DD') updated_at
				FROM $this->db_table
                WHERE LEAVE_YEAR = '2021'
                AND EMPLOYEE_ID = '$this->employee_id'";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        // UPDATE
        public function updateLeave(){
            
            if($this->leave_id){
                
                /*generate id */
                $s_query = "SELECT com.get_unique_id('HR_EMPLOYEE_LEAVE_2','LEAVE_APPL_NO') genId FROM DUAL";
                $stmt = $this->conn->prepare($s_query);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $gen_id = $result['GENID'];
                /*end generate id*/

                $sql = "INSERT INTO HR_EMPLOYEE_LEAVE_2 
                    (LEAVE_APPL_NO, APPLICATION_DATE, EMPLOYEE_ID, LEAVE_ID, FROM_DATE,TO_DATE, APPROVED_BY, APPROVED_DATE, APPROVED_BY_DESIG, REASON_FOR_LEAVE, RES_PERSON_DURING_LEAVE, ADDRESS_DURING_LEAVE,REPORTING_AUTHORITY,REPORTING_AUTHORITY_DESIG,REP_AUTHORITY_COMMENT,AUTHORITY_APPROVED,AUTHORITY_APPROVED_DATE,HOD,HOD_DESIG,HOD_APPROVED,HOD_APPROVED_DATE,HOD_COMMENT,HR,HR_DESIG,HR_APPROVED,HR_APPROVED_DATE,HR_COMMENT,MANAGER_ID,MGT_DESIG,MGT_APPROVED,MGT_APPROVED_DATE,MGT_COMMENT,ENTERED_BY,ENTERED_DATE,UPDATED_BY,UPDATED_DATE,CONTACT_NO,REFERENCE_NO,IS_APPROVED,INSERT_BY,INSERT_DATE,UPDATE_BY,UPDATE_DATE,COMPANY_ID,PREVIOUS_ID,CANCEL_REASON,APPLICATION_TO) 
                    VALUES ('".$this->leave_id."','".$this->created_at."','".$this->employee_id."', '".$this->leave_type."', '".$this->from_date."', '".$this->to_date."' , null,null,null, '".$this->reason."', null,'".$this->location."',null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,'HR_SALEH', '".$this->created_at."','HR_SALEH', '".$this->created_at."', '1', null,'Y','HR_SALEH', '".$this->created_at."','HR_FEROZ', '".$this->created_at."', $this->company_id, null,null,null)";

                $stmt = $this->conn->prepare($sql);

                if($stmt->execute()){

                    $sql2 = "UPDATE HR_EMPLOYEE_YEARLY_LEAVE_2 
                        SET CUR_BAL = $this->cur_bal, TAKEN_DAYS= $this->taken_days
                        WHERE LEAVE_YEAR = '2021'
                            AND EMPLOYEE_ID = '$this->employee_id'
                            AND LEAVE_ID = '$this->leave_type'";

                    $stmt = $this->conn->prepare($sql2);

                    if($stmt->execute()){
                        //var_dump($this->conn->errorInfo());
                        return true;
                    }
                    return false;
                }
                return false;

            }

            
        }
		


    }
	
	
?>