<?php
if (!isset($_SESSION)) {
	session_start();
}
if(isset($_SESSION["email"])){
	$_SESSION["error"] = "Page Not Found!";
	header("Location: index.php");
	exit();
}
require('config.php');
$email = isset($_GET['email']) ? $_GET['email'] : null;
$token = isset($_GET['token']) ? $_GET['token'] : null;
if (!$email || !$token) {
    header("Location: index.php");
    exit();
}
$query = "SELECT * FROM users WHERE email = '$email' AND token = '$token'";
$result = mysqli_query($con, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    // Invalid email or token, redirect to index page
    $_SESSION["Error"] = "Link Expired";
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize the input
    $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    $password = md5($newPassword);
    // Check if passwords match
    if($confirmPassword != ''){
        if ($newPassword !== $confirmPassword) {
            $error = "Passwords do not match. Please try again.";
        } else {
            $update="UPDATE users SET password='$password', token=null WHERE email='$email'";
            $result = mysqli_query($con, $update) or die();
            $_SESSION["success"] = "Password Updated Successfully!";
            header("Location: index.php");
            exit();
        }
    }
    else{
        $error = "Enter Password.";
    }
}

$pageTitle = "Reset Password";
include 'includes/header.php';
?>

<div class="cr-vr-task-bc wd100">
    <div class="wrapper">
        <div class="cr-vr-task wd100">
            <div class="left-area">
                <img src="images/login-image.png">
            </div>
            <div class="right-area l-space">
                <h1>Reset<br><span class="black">Password</span></h1>
                <div class="left-side manager-side active">
                    <form method="post">
                        <input type="password" name="new_password" id="new_password" placeholder="New Password" required>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                        <?php if (isset($error)) : ?>
                            <p style="color: red;"><?php echo $error; ?></p>
                        <?php endif; ?>
                        <button type="submit" style="width: auto;" class="btn normal-btn mt-2 form-submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
</body>

</html>