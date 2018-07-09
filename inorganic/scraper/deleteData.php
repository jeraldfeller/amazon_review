<?php

require_once('../includes/functions.php');
//print_r($_GET);
	if(isset($_GET["marketplace"])){
		if($_GET["marketplace"] == 'all'){  
			$table1 = 'inorganic1';
			$table2 = 'inorganic1_de';
			$table3 = 'inorganic1_uk';
			$table4 = 'inorganic1_fr';
		}elseif($_GET["marketplace"] == 'it'){ 
			$table1 = 'inorganic1';
		}elseif($_GET["marketplace"] == 'co.uk'){ 
			$table1 = 'inorganic1_uk';
		}elseif($_GET["marketplace"] == 'de'){ 
			$table1 = 'inorganic1_de';
		}elseif($_GET["marketplace"] == 'fr'){ 
			$table1 = 'inorganic1_fr';
		}
		if($_GET["marketplace"] == 'all_out'){  
			$table1 = 'inorganic';
			$table2 = 'inorganic_de';
			$table3 = 'inorganic_uk';
			$table4 = 'inorganic_fr';
		}elseif($_GET["marketplace"] == 'it_out'){ 
			$table1 = 'inorganic';
		}elseif($_GET["marketplace"] == 'co.uk_out'){ 
			$table1 = 'inorganic_uk';
		}elseif($_GET["marketplace"] == 'de_out'){ 
			$table1 = 'inorganic_de';
		}elseif($_GET["marketplace"] == 'fr_out'){ 
			$table1 = 'inorganic_fr';
		}
		 
		
	}  
	$db = MysqliDatabaseconnect();
	if($_GET["marketplace"] == 'all' or $_GET["marketplace"] == 'all_out'){    
		$sql1 = "TRUNCATE ".$table1;  
		$sql2 = "TRUNCATE ".$table2;  
		$sql3 = "TRUNCATE ".$table3;   
		$sql4 = "TRUNCATE ".$table4;   
		$db->query($sql1)->execute() ; 
		$db->query($sql2)->execute() ; 
		$db->query($sql3)->execute() ; 
		$db->query($sql4)->execute() ; 
		
	}else{  
		$sql1 = "TRUNCATE ".$table1;    
		$db->query($sql1)->execute() ; 
	
	}
	
	 
	echo "Deleted Old data";


?>