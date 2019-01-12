<?php
$username=$_POST['username'];
$email=$_POST['email'];
$mobile=$_POST['mobile'];
$password=$_POST['password'];
$role=$_POST['role'];
$supervisor=$_POST['supervisor'];

$flag=0;


$con=mysqli_connect('localhost','root','abc');
            if(!$con)
               echo "connection failed";
            else
               echo "connection done to mysql";
   mysqli_select_db($con,'phq');
  
   $q="INSERT INTO user(user_name, email, mobile, password, supervisor, role) VALUES ('$username', '$email', '$mobile', '$password', '$supervisor', '$role')";

$result=mysqli_query($con,$q);
if($result==1)
{
   echo "<br>Register Successfully ";
   $flag=1;
   header('location: http://localhost/FMS_PHQ/html/index.html');
}
else
{
      echo "<br>";
   echo mysqli_error($con);
   echo "<br>Registration failed";
   }
   
   mysqli_close($con);
?>
