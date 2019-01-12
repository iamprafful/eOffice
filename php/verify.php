<?php
$userid=$_POST['userid'];
$pwd=$_POST['password'];
if ($userid==-1) {
	header('location: ../index.php');
	
}
session_start();
include('config.php');
$con= mysqli_connect($servername,$username,$password,$dbname);	

				if(!$con)
				//	echo "connection failed";
				else
				//	echo "connection done to mysql";
//mysqli_select_db($con,'phq');
	$q="select privilage from user where user_id='$userid' && password='$pwd' limit 1";
$result=mysqli_query($con,$q);
$num=mysqli_num_rows($result);

$row=mysqli_fetch_array($result);


if($row['privilage']==1)
{
//	echo "Welcome Admin";
	
		}

else if($row['privilage']==0)
{
//	echo "Welcome User";
	
	}


else
	{
	
	header('location: ../index.php');
	
	}
	
	mysqli_close($con);
?>