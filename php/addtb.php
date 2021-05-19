<?php
session_start();
require_once "pdo.php";
if(!isset($_SESSION['student_id'])){
  die("Not logged in");
};
$stmt1=$pdo->query("SELECT * FROM Students WHERE student_id='{$_SESSION['student_id']}'");
$student=$stmt1->fetch(PDO::FETCH_ASSOC);
$student_name=$student['FN']." ".$student['LN'];


if(isset($_POST['event'])&&isset($_POST['startdate'])&&isset($_POST['enddate'])&&isset($_POST['notes'])){
  if(strpos($_POST['startdate'], '-') !== false) {
  list($y, $m, $d) = explode('-', $_POST['startdate']);
  if(checkdate($m, $d, $y)){
    $start_date_format=true;
    }
    else{
    $start_date_format=false;
    }
  } else {
  $start_date_format=false;
  }

if(strpos($_POST['enddate'], '-') !== false) {
list($y, $m, $d) = explode('-', $_POST['startdate']);
if(checkdate($m, $d, $y)){
  $end_date_format=true;
  }
  else{
  $end_date_format=false;
  }
} else {
$end_date_format=false;
}

  if(strlen($_POST['event'])<1){
    $_SESSION['fail']="Event is required";
    header('Location: addtb.php');
    return;
  }
  elseif($start_date_format!==true||$end_date_format!==true){
    $_SESSION['fail']="Date must be in format YYYY-MM-DD";
    header('Location: addtb.php');
    return;
  }
  else{
    $_SESSION['success']="Record inserted";
    $stmt3 = $pdo->prepare('INSERT INTO timetable(Event, StartDate, EndDate, Notes, student_id) VALUES ( :ev, :sd, :ed, :no, :std)');
    $stmt3->execute(array(
    ':ev' => $_POST['event'],
    ':sd' => $_POST['startdate'],
    ':ed' => $_POST['enddate'],
    ':no' => $_POST['notes'],
    ':std' => $_SESSION['student_id']),
    );
    header('Location: timetable.php');
    return;
  }
};
?>





<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Hungng Utilities</title>
   <?php require_once "css.php" ?>
</head>
<body>




<div class="screencomponent">
 <div class="navbar">
   <div class="brandname"><?=htmlentities($student_name) ?>'s Utilities</div>
   <div class="menu">
   <div class="navbox">Profile</div>
   <div class="navbox">Academic Transcript</div>
   <div class="navbox">Time Table</div>
   <div class="navbox">Teachers' Contacts</div>
   <div class="navbox"><a href="logout.php" class="none">Log out</a></div>
   </div>
 </div>
 <div class="main">
   <div id="timetableform">
<?php
if(isset($_SESSION['fail'])){
 echo ('<p style="color: red;">'.htmlentities($_SESSION['fail'])."</p>\n");
 unset($_SESSION['fail']);
};
if(isset($_SESSION['success'])){
 echo ('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
 unset($_SESSION['success']);
}
?>
<form method="POST">
 <label for="event">Event </label>
 <input type="text" name="event" id="event" class="textinput"><br/>
 <label for="startdate">StartDate </label>
 <input type="text" name="startdate" id="startdate" class="textinput"><br/>
 <label for="enddate">EndDate </label>
 <input type="text" name="enddate" id="enddate" class="textinput"><br/>
 <label for="notes">Notes </label>
 <input type="text" name="notes" id="notes" class="textinput"><br/>
 <input type="submit" value="Add">
</form>
</div>
</div>
</div>
</body>
</html>
