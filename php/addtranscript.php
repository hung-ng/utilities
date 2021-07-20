<?php
session_start();
require_once "pdo.php";
if (!isset($_SESSION['teacher'])) {
  $_SESSION['error'] = "Please Log In";
  header('Location: login.php');
};
if (isset($_POST['std_id']) && isset($_POST['science_score']) && isset($_POST['math_score']) && isset($_POST['lit_score'])) {
  if (strlen($_POST['std_id']) < 1) {
    $_SESSION['error'] = "Student ID is required";
    header('Location: addtranscript.php');
    return;
  } else {
    $stmt = $pdo->query("SELECT * FROM Students WHERE student_id='{$_POST['std_id']}'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $test = $row["email"];
    if (!isset($test)) {
      $_SESSION['error'] = "Student ID does not exist";
      header('Location: addtranscript.php');
      return;
    } else {
      $_SESSION['success'] = "Record inserted";
      $stmt3 = $pdo->prepare("UPDATE students SET math_score = :ma, science_score = :sc, lit_score = :lit WHERE student_id='{$_POST['std_id']}'");
      $stmt3->execute(
        array(
          ':ma' => $_POST['math_score'],
          ':sc' => $_POST['science_score'],
          ':lit' => $_POST['lit_score']
        )
      );
      header('Location: addtranscript.php');
      return;
    }
  }
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
      <div class="fixed">
        <div class="brandname">Teacher's<br> Utilities</div>
        <div class="menu">
          <div class="navbox1"><a href="logout.php" class="none">Log out</a></div>
        </div>
      </div>
    </div>
    <div class="main">
      <div id="timetableform">
        <div class="article">Update student's score</div>
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
        <form method="POST">
          <label for="std_id">Student ID </label>
          <input type="text" name="std_id" id="std_id" maxlength="10" class="textinput"><br />
          <label for="math_score">Mathematics </label>
          <input type="number" step="1" min="0" max="100" name="math_score" id="math_score" class="textinput"><br />
          <label for="science_score">Science </label>
          <input type="number" step="1" min="0" max="100" name="science_score" id="science_score" class="textinput"><br />
          <label for="lit_score">Literature </label>
          <input type="number" step="1" min="0" max="100" name="lit_score" id="lit_score" class="textinput"><br />
          <input type="submit" value="Insert">
        </form>
      </div>
    </div>
</body>

</html>