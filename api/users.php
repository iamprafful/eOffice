<?php
session_start();
if ($_SESSION["logged_in"]!="true" && $_SESSION["privilage"]!="1") {
  header('location: index.php');
}
include("../php/config.php");
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
          $response['error']=true;
          $response['message']="Connection Failed";
          die(json_encode($response));
        }
        $get_city='Select user_id, user_name, mobile, supervisor, role from user where privilage="0" order by user_id';
        $result = $conn->query($get_city);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
              $response[]=$row;
            }
        } else {
          $response['error']=true;
          $response['message']="No Range Found";
        }
        $conn->close();
        header('Content-Type: application/json');
        echo '{"aaData":'.json_encode($response).'}';
?>
