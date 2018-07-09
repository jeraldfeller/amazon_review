<?php

	echo "<pre>"; 
	//ini_set('display_startup_errors', -1);
	// ini_set('display_errors', -1);
	error_reporting(0);	 
	require_once('../includes/functions.php');  
	$table ='Amazon_Asin_review';
	$table1 ='amazonReview';
	$field['status'] = 0;
	//$field['id >'] = 1000;
	$results = selectDBField($table,$field,null,false,1);  
	print_r($results);
	if($results){ 
		foreach($results as $result){
			/*$results1 = selectDBField($table1,"asin",$result['asin'],false,1); 
			print_r($results1[0]);
			if($results1){ 
				$status['total']  = $results1[0]['total'];
				$status['status'] = 5;
			}else{
				$status['status'] = 6;
			}*/
			$status['total']  = $result['total'];
			$status1['status'] = 5;
			print_r($status);
			updateDBField('asin',$result["asin"],$table1,$status);
			updateDBField('id',$result["id"],$table,$status1);
			echo'<meta http-equiv="refresh" content="0;">';
		}
	}else{
		echo 'all done';
	}
?>