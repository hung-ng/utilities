<?php
session_start();
require_once "pdo.php";
if (!isset($_SESSION['student_id'])) {
  $_SESSION['error'] = "Please Log In";
  header('Location: login.php');
};
$stmt1 = $pdo->query("SELECT * FROM Students WHERE student_id='{$_SESSION['student_id']}'");
$student = $stmt1->fetch(PDO::FETCH_ASSOC);
$student_name = $student['FN'] . " " . $student['LN'];

if (isset($_POST['class']) && isset($_POST['school']) && isset($_POST['gender']) && isset($_POST['dob']) && isset($_POST['phonenum'])) {
  if (strlen($_POST['class']) < 1 || strlen($_POST['school']) < 1 || strlen($_POST['phonenum']) < 1) {
    $_SESSION['error'] = "All field must be filled!";
    header('Location: profile.php');
    return;
  } elseif (is_numeric($_POST['phonenum']) !== true) {
    $_SESSION['error'] = "Phone Number must be numeric!";
    header('Location: profile.php');
    return;
  } elseif (strlen($_POST['phonenum']) < 10) {
    $_SESSION['error'] = "Phone Number must have 10 digits!";
    header('Location: profile.php');
    return;
  } else {
    $_SESSION['success'] = "Updated!";
    $stmt3 = $pdo->prepare("UPDATE students SET Class = :cl, School = :sc, Gender = :gd, PhoneNum = :pn, DoB = :dob WHERE student_id='{$_SESSION['student_id']}'");
    $stmt3->execute(
      array(
        ':cl' => $_POST['class'],
        ':sc' => $_POST['school'],
        ':gd' => $_POST['gender'],
        ':pn' => $_POST['phonenum'],
        ':dob' => $_POST['dob']
      )
    );
    header('Location: profile.php');
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
      <div class="fixed">
        <div class="brandname"><?= htmlspecialchars($student_name) ?>'s<br> Utilities</div>
        <div class="menu">
          <div class="navbox2"><a href="profile.php" class="none">Profile</a></div>
          <div class="navbox1"><a href="academictranscript.php" class="none">Academic Transcript</a></div>
          <div class="navbox1"><a href="timetable.php" class="none">Time Table</a></div>
          <div class="navbox1"><a href="teachercontact.php" class="none">Teachers' Contacts</a></div>
          <div class="navbox1"><a href="logout.php" class="none">Log out</a></div>
        </div>
      </div>
    </div>
    <div class="main">
      <div class="article">Profile</div>
      <?php
      if (!isset($student['Class'])) {
        echo '<div class="main">';
        echo '<div>Welcome to Student Box</div>';
        echo '<br>';
        echo '<div>Please provide your information in the form below</div>';
        echo '<br>';
        if (isset($_SESSION['error'])) {
          echo ('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
          unset($_SESSION['error']);
        };
        if (isset($_SESSION['success'])) {
          echo ('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n");
          unset($_SESSION['success']);
        }
        echo '<form method="POST" id="profile">';
        echo '<label for="class">Class </label>';
        echo '<input type="text" name="class" id="class" maxlength="128" class="textinput"><br/>';
        echo '<label for="school">School </label>';
        echo '<input type="text" name="school" id="school" maxlength="256" class="textinput"><br/>';
        echo '<label for="gender">Gender </label>';
        echo '<select id="gender" name="gender" form="profile" class="textinput">';
        echo '<option value="Male">Male</option>';
        echo '<option value="Female">Female</option>';
        echo '</select>';
        echo '<br>';
        echo '<label for="dob">DoB </label>';
        echo '<input type="date" name="dob" id="dob" value="2004-01-31" min="1922-01-01" max="2015-01-01" class="textinput"><br/>';
        echo '<label for="phonenum">Phone Number </label>';
        echo '<input type="text" name="phonenum" id="phonenum" maxlength="10" class="textinput"><br/>';
        echo '<input type="submit" value="Add">';
        echo '</form>';
        echo '</div>';
      } else {
        echo '<div id="adddiv"><div>' . '<a class="none" href="editprofile.php?std_id=' . $student['student_id'] . '">Edit Profile</a></div></div>';
        echo '<div class="profile-body">';
        echo '<div class="profile-content">';
        echo '<div>Name: ' . $student_name . '</div>';
        echo '<div>Class: ' . htmlspecialchars($student["Class"]) . '</div>';
        echo '</div>';
        echo '<br>';
        echo '<div>School: ' . htmlspecialchars($student["School"]) . '</div>';
        echo '<br>';
        echo '<div class="profile-content">';
        echo '<div>Gender: ' . htmlspecialchars($student["Gender"]) . '</div>';
        echo '<div>D.o.B: ' . htmlspecialchars($student["DoB"]) . '</div>';
        echo '</div>';
        echo '<br>';
        echo '<div>Phone Number: ' . htmlspecialchars($student["PhoneNum"]) . '</div>';
        echo '</div>';
      }
      ?>
    </div>
  </div>
</body>

</html>