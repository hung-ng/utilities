<?php
$salt = "oklaoklaokla";
session_start();
require_once "pdo.php";
if (isset($_SESSION['student_id'])) {
    header('Location: timetable.php');
};
if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmPassword']) && isset($_POST['firstName']) && isset($_POST['lastName'])) {
    $stmt = $pdo->query("SELECT PW FROM Students WHERE email='{$_POST['email']}'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $testing_pass = $row['PW'];
    if (strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1 || strlen($_POST['confirmPassword']) < 1 || strlen($_POST['firstName']) < 1 || strlen($_POST['lastName']) < 1) {
        $_SESSION['error'] = "All fields must be filled";
        header("Location: register.php");
        return;
    } else if (strpos($_POST['email'], '@') == false) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: register.php");
        return;
    } else if ($_POST['password'] !== $_POST['confirmPassword']) {
        $_SESSION['error'] = "Passwords must be matched";
        header("Location: register.php");
        return;
    } else if (isset($testing_pass)) {
        $_SESSION['error'] = "This account already exist";
        header("Location: register.php");
        return;
    } else {
        $stmt = $pdo->prepare('INSERT INTO Students(email, FN, LN, PW) VALUES ( :em, :fn, :ln, :pw)');
        $stmt->execute(
            array(
                ':em' => $_POST['email'],
                ':fn' => $_POST['firstName'],
                ':ln' => $_POST['lastName'],
                ':pw' => $_POST['password'],
            )
        );
        header("Location: login.php");
        return;
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
    <div class="register-container">
        <div class="aside-right">
            <div class="header">
                <h3>Student Box</h3>
            </div>
            <div class="error">
                <?php
                if (isset($_SESSION['error'])) {
                    echo ('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
                    unset($_SESSION['error']);
                }
                ?>
            </div>
            <form method="POST" id="register-form">
                <div class="input-name-wrapper">
                    <div class="input-wrapper">
                        <input type="text" name="firstName" placeholder="First name">
                    </div>

                    <div class="input-wrapper">
                        <input type="text" name="lastName" placeholder="Last name">
                    </div>
                </div>
                <div class="input-wrapper">
                    <input type="email" name="email" placeholder="Email">
                </div>
                <div class="input-wrapper">
                    <input type="password" name="password" placeholder="Password">
                </div>
                <div class="input-wrapper">
                    <input type="password" name="confirmPassword" placeholder="Confirm your password">
                </div>
                <div class="form-action">
                    <span>Already have an account? <a href="login.php" class="none">Login</a></span>
                </div>
                <button class="btn" type="submit">
                    Register
                </button>
            </form>
        </div>
    </div>
</body>

</html>