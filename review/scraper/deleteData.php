<?php

require_once('../includes/functions.php'); 

	//print_r($_GET); 
	if(isset($_GET["marketplace"])){
		if($_GET["marketplace"] == 'all'){  
			$table1 = 'amazonReview';
			$table2 = 'Amazon_Asin_review'; 
		}elseif($_GET["marketplace"] == 'input'){ 
			$table1 = 'Amazon_Asin_review';
		}elseif($_GET["marketplace"] == 'output'){ 
			$table1 = 'amazonReview';
		} 
	}  
	$db = MysqliDatabaseconnect();
	if($_GET["marketplace"] == 'all'){    
		$sql1 = "TRUNCATE ".$table1;  
		$sql2 = "TRUNCATE ".$table2;  
		$db->query($sql1)->execute() ; 
		$db->query($sql2)->execute() ;   
	}else{  
		$sql1 = "TRUNCATE ".$table1;    
		$db->query($sql1)->execute() ; 
	
	} 
	 
	echo "Deleted Old data";


?>