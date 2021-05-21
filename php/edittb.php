<?php
require_once "pdo.php";
session_start();

if(!isset($_SESSION['student_id'])){
  $_SESSION['error']="Please Log In";
  header('Location: login.php');
};

if ( ! isset($_GET['tb_id']) ) {
  $_SESSION['error'] = "Missing event";
  header('Location: timetable.php');
  return;
};

$stmt2 = $pdo->prepare("SELECT * FROM timetable where tb_id = :tbid");
$stmt2->execute(array(":tbid" => $_GET['tb_id']));
$row = $stmt2->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Event does not exist';
    header( 'Location: timetable.php' ) ;
    return;
};

$stmt3 = $pdo->prepare("SELECT * FROM timetable where tb_id = :tbid AND student_id= :std_id");
$stmt3->execute(array(
  ":tbid" => $_GET['tb_id'],
  ":std_id" => $_SESSION['student_id']
));
$row1 = $stmt3->fetch(PDO::FETCH_ASSOC);
if ( $row1 === false ) {
    unset($_SESSION['student_id']);
    die("Do not touch to other's information!!!");
};



$stmt1=$pdo->query("SELECT * FROM Students WHERE student_id='{$_SESSION['student_id']}'");
$student=$stmt1->fetch(PDO::FETCH_ASSOC);
$student_name=$student['FN']." ".$student['LN'];

if(isset($_POST['event'])&&isset($_POST['startdate'])&&isset($_POST['enddate'])&&isset($_POST['notes']) && isset($_POST['tb_id'])){
//   if(strpos($_POST['startdate'], '-') !== false) {
//   list($y, $m, $d) = explode('-', $_POST['startdate']);
//   if(checkdate($m, $d, $y)){
//     $start_date_format=true;
//     }
//     else{
//     $start_date_format=false;
//     }
//   } else {
//   $start_date_format=false;
//   }

// if(strpos($_POST['enddate'], '-') !== false) {
// list($y, $m, $d) = explode('-', $_POST['startdate']);
// if(checkdate($m, $d, $y)){
//   $end_date_format=true;
//   }
//   else{
//   $end_date_format=false;
//   }
// } else {
// $end_date_format=false;
// }

  if(strlen($_POST['event'])<1){
    $_SESSION['error']="Event is required";
    header('Location: edittb.php?tb_id='.$_POST['tb_id']);
    return;
  }
  // elseif($start_date_format!==true||$end_date_format!==true){
  //   $_SESSION['error']="Date must be in format YYYY-MM-DD";
  //   header('Location: edittb.php?tb_id='.$_POST['tb_id']);
  //   return;
  // }
  elseif($_POST['enddate']<$_POST['startdate']){
    $_SESSION['error']="EndDate must be after StartDate";
      header('Location: addtb.php');
      return;
  }
  else{
    $_SESSION['success']="Record inserted";
    $stmt4 = $pdo->prepare('UPDATE timetable SET Event = :ev, StartDate = :sd, EndDate = :ed, Notes = :no WHERE tb_id = :tb');
    $stmt4->execute(array(
    ':ev' => $_POST['event'],
    ':sd' => $_POST['startdate'],
    ':ed' => $_POST['enddate'],
    ':no' => $_POST['notes'],
    ':tb' => $_POST['tb_id']),
    );
    header('Location: timetable.php');
    return;
  }
};

$ev = htmlentities($row['Event']);
$sd = htmlentities($row['StartDate']);
$ed = htmlentities($row['EndDate']);
$no = htmlentities($row['Notes'])




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
    <div class="fixed">
    <div class="brandname"><?=htmlentities($student_name) ?>'s<br> Utilities</div>
    <div class="menu">
    <div class="navbox1"><a href="profile.php" class="none">Profile</a></div>
    <div class="navbox1">Academic Transcript</div>
    <div class="navbox2"><a href="timetable.php" class="none">Time Table</a></div>
    <div class="navbox1"><a href="teachercontact.php" class="none">Teachers' Contacts</a></div>
    <div class="navbox1"><a href="logout.php" class="none">Log out</a></div>
    </div>
  </div>
  </div>
 <div class="main">
   <div id="timetableform">
     <p>Editing <?= htmlentities($row['Event']) ?> </p>
<?php
if(isset($_SESSION['error'])){
 echo ('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
 unset($_SESSION['error']);
};
if(isset($_SESSION['success'])){
 echo ('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
 unset($_SESSION['success']);
}
?>
   <form method="POST">
    <label for="event">Event </label>
    <input type="text" name="event" id="event" maxlength="128" value="<?= $ev ?>" class="textinput"><br/>
    <label for="startdate">StartDate </label>
    <input type="date" name="startdate" id="startdate" value="2021-10-10" value="<?= $sd ?>" class="textinput"><br/>
    <label for="enddate">EndDate </label>
    <input type="date" name="enddate" id="enddate" value="2021-10-10" value="<?= $ed ?>" class="textinput"><br/>
    <label for="notes">Notes </label>
    <input type="text" name="notes" id="notes" class="textinput" maxlength="256" value="<?= $no ?>" class="textinput"><br/>
    <input type="hidden" name="tb_id" value="<?= $row['tb_id'] ?>">
    <input type="submit" value="Edit">
    <a href="timetable.php">Cancel</a>
   </form>
</div>
</div>
</div>
</body>
</html>
