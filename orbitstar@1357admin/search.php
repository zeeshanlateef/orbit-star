<?php
session_start();
error_reporting(0);
include 'include/connection.php';

if(!isset($_SESSION['ADMIN_USERID']) && $_SESSION['ADMIN_USERID']=='')
{
header('location:index.php');
}

if(isset($_REQUEST['Search']))
{ 
	$tbl_name = "contact_us";
	$txtStartDate = isset($_REQUEST['txtStartDate']) ? $_REQUEST['txtStartDate'] : '';
	$txtEndDate   = isset($_REQUEST['txtEndDate']) ? $_REQUEST['txtEndDate'] : '';
	if(!empty($txtStartDate)){
		$searchStartDate = "AND CAST(date AS DATE) >= '".$txtStartDate."'";
	}else{
		$searchStartDate = '';
	}

	if(!empty($txtEndDate)){
		$searchEndDate = "AND CAST(date AS DATE) <= '".$txtEndDate."'";
	}else{
		$searchEndDate = '';
	}

}	
	$sql = $conn->prepare("SELECT * FROM $tbl_name WHERE CAST(date AS DATE) >= '$searchStartDate' AND CAST(date AS DATE) <= '$searchEndDate' ORDER BY `date`");
	$sql->execute();
	$count = $sql->rowCount();	
	


?>

<?php

if($count==0){

echo '<h2>No data found..!</h2>';

}else
{
	$output="";
	$i=1;
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		$output.="
					<tr>
					<td>".$i++."</td>
					<td>".$rows['name']."</td>
				
					<td>".$rows['email']."</td>
					<td>".$rows['subject']."</td>
					<td>".$rows['comment']."</td>
					<td>".$rows['date']."</td>
					<br>
					
					</tr>

			";
		
		//$output='<h2>'.$data.'</h2>';
		
	}
	echo $output;
}


?>

</form>
</body>

</center>


</html>