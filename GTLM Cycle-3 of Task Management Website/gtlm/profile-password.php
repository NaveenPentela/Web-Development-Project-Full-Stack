<?php
include 'auth_session.php';
require('config.php');
if (!isset($_SESSION)) {
	session_start();
}
// Check if the form is submitted
if (isset($_POST['current_password'])) {
    // Get user ID from the session
    $userId = $_SESSION['userId'];

    // Validate and sanitize the input
    $currentPassword = stripslashes($_REQUEST['current_password']);
    $newPassword = stripslashes($_REQUEST['new_password']);
    $confirmPassword = stripslashes($_REQUEST['confirm_password']);

    // Check if new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = 'New password and confirm password do not match.';
    } else {
        // Check if the current password matches the one in the database
        $query = "SELECT * FROM `users` WHERE id='$userId' AND password='" . md5($currentPassword) . "'";
        $result = mysqli_query($con, $query);
		$rows = mysqli_num_rows($result);
        if ($rows == 1) {
            // Update the password
            $updateQuery = "UPDATE users SET password='" . md5($newPassword) . "' WHERE id='$userId'";
            $updateResult = mysqli_query($con, $updateQuery);

            if ($updateResult) {
                $_SESSION['success'] = 'Password updated successfully.';
            } else {
                $_SESSION['error'] = 'Error updating password.';
            }
        } else {
            $_SESSION['error'] = 'Incorrect current password.';
        }
    }

    // Redirect to the current page to avoid form resubmission on page refresh
    header("Location: profile-password.php");
    exit();
}
$pageTitle = "Update Password";
include 'includes/header.php';
?>

	<div class="wrapper-small">
		<div class="container wd100">
			<div class="side-area-add wd100">
				<h2>Change Password</h2> 
				<!-- <div class="side-title-menu pr"><span>Status</span><span><a href="profile-password.php">Password</a></span><span>Logout</span></div> -->
			</div>
			<form class="full-change-pass" method="post">
                <input type="password" name="current_password" placeholder="Current Password" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
				<br><br>
				<input type="submit" class="btn normal-btn" value="Update Password"></input>
			</form>
			
		</div>
	</div>
	
	<?php
	include 'includes/footer.php';
	?>

</body>

</html>