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

if ( isset($_POST['delete']) && isset($_POST['tb_id']) ) {
    $stmt = $pdo->prepare("DELETE FROM timetable WHERE tb_id = :tb_id");
    $stmt->execute(array(':tb_id' => $_POST['tb_id']));
    $_SESSION['success'] = 'Event deleted';
    header( 'Location: timetable.php' ) ;
    return;
}




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
   <p>Confirm: Deleting <?= htmlentities($row['Event']) ?> ?</p>
   <form method="post">
   <input type="hidden" name="tb_id" value="<?= $row['tb_id'] ?>">
   <input type="submit" value="Delete" name="delete">
   <a href="timetable.php">Cancel</a>
   </form>
</div>
</div>
</body>
</html>