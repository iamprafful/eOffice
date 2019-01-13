<?php
  session_start();
  if ($_SESSION["logged_in"]=="true" && $_SESSION["privilage"]=="0") {
    include("config.php");
    $user= $_SESSION['user_id'];
  }
  else {
    header('location: ../index.php');
  }
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error)
  {
    die("Connection failed: " . $conn->connect_error);
  }
  $get_id="select id+1 from files order by id desc limit 1";
  $result = $conn->query($get_id);

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while($row = $result->fetch_assoc()) {
                            $id =$row["id+1"];
                        }
                    } else {
                        $id="1";
                    }

  if($_POST["type"]=="other")
  {
    $type="'".$_POST["other_value_type"]."'";
  }
  else {
    $type="'".$_POST["type"]."'";
  }
  $file_no="'".$_POST["file_no"]."'";
  if($_POST["sender"]=="other")
  {
    $sender="'".$_POST["other_value_sender"]."'";
  }
  else {
    $sender="'".$_POST["sender"]."'";
  }
  $letter_no="'".$_POST["letter_no"]."'";
  $dgp_no="'".$_POST["dgp_no"]."'";
  $date="'".date("Y-m-d", strtotime($_POST["date"]))."'";
  if($_POST["subject"]=="other")
  {
    $subject="'".$_POST["other_value_subject"]."'";
  }
  else {
    $subject="'".$_POST["subject"]."'";
  }
  $description="'".$_POST["description"]."'";
  $remark="'".$_POST["remark"]."'";
  $temp = explode(".", $_FILES["file"]["name"]);
  $status=$_GET['status'];
  $newfilename = round(microtime(true)) . '.' . end($temp);
  $upload_url = '../file/'. $newfilename;
  $ok=1;
  $file_type=$_FILES['file']['type'];
  if ($file_type=="application/pdf" || $file_type=="image/gif" || $file_type=="image/jpeg") {
    if(move_uploaded_file($_FILES['file']['tmp_name'], $upload_url))
    {
      $register_file_sql = "INSERT INTO files (id, file_no, sender, letter_no, dgp_office_no, letter_date, time_limit, subject, description, creator_id, file_type, file_loc)
                               VALUES ($id, $file_no, $sender, $letter_no, $dgp_no, $date, '0', $subject, $description, '".$user."', $type, '".$upload_url."')";

      if ($conn->query($register_file_sql) === TRUE)
      {
        foreach ($_POST["notesheet"] as $key => $notesheet_id) {
          $link_notesheet="UPDATE notesheet set file_id='".$id."' where id=".$notesheet_id;
          if ($conn->query($link_notesheet) === FALSE) {
            echo "Error: " .$link_notesheet. "<br>" . $conn->error;
          }
        }
        foreach ($_POST["send_action"] as $key => $receiver_id) {
          $new_transaction_action = "INSERT into transactions (type, file_id, sender_id, receiver_id, status, remark) VALUES ('1', '".$id."', '".$user."', '".$receiver_id."', '".$status."', $remark)";
          if ($conn->query($new_transaction_action) === FALSE) {
            echo "Error: " .$new_transaction_action. "<br>" . $conn->error;
          }
        }
        foreach ($_POST["send_information"] as $key => $receiver_id) {
          $new_transaction_information = "INSERT into transactions (type, file_id, sender_id, receiver_id, status, remark) VALUES ('0', '".$id."', '".$user."', '".$receiver_id."', '".$status."', $remark)";
          if ($conn->query($new_transaction_information) === FALSE) {
            echo "Error: " .$new_transaction_information. "<br>" . $conn->error;
          }
        }
        header("Location: ../outbox.php");
      }
      else
      {
        echo "Error: " . $register_file_sql . "<br>" . $conn->error;
      }
      $conn->close();
    }
    else {
      echo "Problem uploading file";
    }
  }
  else {
     echo "You may only upload PDFs, JPEGs or GIF files.<br>";
   }
?>
