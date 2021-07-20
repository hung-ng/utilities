<?php
require_once "pdo.php";
session_start();
if (!isset($_SESSION['student_id'])) {
  $_SESSION['error'] = "Please Log In";
  header('Location: login.php');
};
if (!isset($_GET['std_id'])) {
  $_SESSION['error'] = "Missing student";
  header('Location: profile.php');
  return;
};
$stmt1 = $pdo->query("SELECT * FROM Students WHERE student_id='{$_SESSION['student_id']}'");
$student = $stmt1->fetch(PDO::FETCH_ASSOC);
$student_name = $student['FN'] . " " . $student['LN'];
if ($_SESSION['student_id'] !== $_GET['std_id']) {
  unset($_SESSION['student_id']);
  die("Do not touch to other's information!!!");
};
if (isset($_POST['class']) && isset($_POST['school']) && isset($_POST['gender']) && isset($_POST['dob']) && isset($_POST['phonenum'])) {
  if (strlen($_POST['class']) < 1 || strlen($_POST['school']) < 1 || strlen($_POST['phonenum']) < 1) {
    $_SESSION['error'] = "All field must be filled!";
    header('Location: editprofile.php?std_id=' . $_SESSION['student_id']);
    return;
  } elseif (is_numeric($_POST['phonenum']) !== true) {
    $_SESSION['error'] = "Phone Number must be numeric!";
    header('Location: editprofile.php?std_id=' . $_SESSION['student_id']);
    return;
  } elseif (strlen($_POST['phonenum']) < 10) {
    $_SESSION['error'] = "Phone Number must have 10 digits!";
    header('Location: editprofile.php?std_id=' . $_SESSION['student_id']);
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
$cl = htmlspecialchars($student['Class']);
$sc = htmlspecialchars($student['School']);
$gd = htmlspecialchars($student['Gender']);
$dob = htmlspecialchars($student['DoB']);
$pn = htmlspecialchars($student['PhoneNum'])
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
        <div class="brandname"><?= htmlspecialchars($student_name) ?>'s<br> Box</div>
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
      <div id="timetableform">
        <p>Editing <?= htmlspecialchars($student_name) ?>'s Profile </p>
        <?php
        if (isset($_SESSION['error'])) {
          echo ('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
          unset($_SESSION['error']);
        };
        if (isset($_SESSION['success'])) {
          echo ('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n");
          unset($_SESSION['success']);
        }
        ?>
        <form method="POST" id="profile">
          <label for="class">Class </label>
          <input type="text" name="class" id="class" maxlength="128" value="<?= $cl ?>" class="textinput"><br />
          <label for="school">School </label>
          <input type="text" name="school" id="school" maxlength="256" value="<?= $sc ?>" class="textinput"><br />
          <label for="gender">Gender </label>
          <select id="gender" name="gender" form="profile" value="<?= $gd ?>" class="textinput">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
          <br>
          <label for="dob">DoB </label>
          <input type="date" name="dob" id="dob" value="2004-01-31" min="1922-01-01" max="2015-01-01" value="<?= $dob ?>" class="textinput"><br />
          <label for="phonenum">Phone Number </label>
          <input type="text" name="phonenum" id="phonenum" maxlength="10" value="<?= $pn ?>" class="textinput"><br />
          <input type="submit" value="Edit">
          <a href="profile.php">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</body>

</html>