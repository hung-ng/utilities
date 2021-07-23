<?php
$salt = "oklaoklaokla";
session_start();
require_once "pdo.php";
if (isset($_SESSION['student_id'])) {
    header('Location: timetable.php');
    return;
};
if (isset($_SESSION["otp"])) {
    if (time() - $_SESSION["otp_stamp"] > 600) {
        session_unset();
        session_destroy();
        header("Location:index.php");
        return;
    }
} else {
    header("Location: index.php");
    return;
}
if (isset($_POST['otp'])) {
    if ($_POST['otp'] == $_SESSION['otp']) {
        $stmt = $pdo->prepare('INSERT INTO users(email, FN, LN, PW) VALUES ( :em, :fn, :ln, :pw)');
        $stmt->execute(
            array(
                ':em' => $_SESSION['email-pending'],
                ':fn' => $_SESSION['fn-pending'],
                ':ln' => $_SESSION['ln-pending'],
                ':pw' => $_SESSION['pw-pending'],
            )
        );
        session_unset();
        $_SESSION['success'] = "Your password has been hashed";
        header("Location: index.php");
        return;
    } else {
        $_SESSION['error'] = "Wrong OTP";
        header("Location: verifyemail.php");
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
    <title>Student Box</title>
    <?php require_once "css.php" ?>
</head>

<body>
    <div class="verifyemail-container">
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
            <form method="POST" id="otp-form">
                <div class="input-wrapper">
                    <input type="text" maxlength="10" name="otp" placeholder="OTP">
                </div>
                <div class="form-action">
                    <span>Don't see our email? <a href="register.php" class="none">Resend</a></span>
                </div>
                <button class="btn" type="submit">
                    Validate
                </button>
            </form>
        </div>
    </div>
</body>

</html>