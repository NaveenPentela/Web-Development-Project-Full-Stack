<?php
	$pageTitle = "Admin Login";
	include 'includes/header.php';

	require('config.php');
	if (!isset($_SESSION)) {
        session_start();
    }
	if(isset($_SESSION["email"])) {
		$_SESSION["warning"] = "Oops! Already logIn";
        header("Location: index.php");
        exit();
    }
	// When form submitted, check and create user session.
	if (isset($_POST['email'])) {
		$email = stripslashes($_REQUEST['email']);
		$role_id = 1;
		$password = stripslashes($_REQUEST['password']);
		if(filter_var($email, FILTER_VALIDATE_EMAIL) !== false){
			$query    = "SELECT * FROM `users` WHERE email='$email' AND is_active = 1
			AND is_deleted = 0 AND role_id='$role_id' AND password='" . md5($password) . "'";
		}
		else{
			if(!is_numeric($_REQUEST['email'])){
				$email = 0;
			}
			$query    = "SELECT * FROM `users` WHERE id='$email'AND is_active = 1
			AND is_deleted = 0 AND role_id='$role_id' AND password='" . md5($password) . "'";
		}
		// Check user is exist in the database
		$result = mysqli_query($con, $query) or die();
		$rows = mysqli_num_rows($result);
		if ($rows == 1) {
			$userData = mysqli_fetch_assoc($result);
			
			$_SESSION['userId'] = $userData['id'];
			$_SESSION['role'] = $userData['role_id'];
			$_SESSION['email'] = $userData['email'];
			$_SESSION['name'] = $userData['name'];
			$_SESSION['success'] = "Login Successfully!";
			// Redirect to user dashboard page
			header("Location: admin-dashboard.php");
			exit();
		} else {
			$_SESSION["error"] = "Login Failed! Invalid Email/Password";
			header("Location: login.php");
			exit();
			
		}
	}
?>

	<div class="cr-vr-task-bc wd100">
		<div class="wrapper">
			<div class="cr-vr-task wd100">
				<div class="left-area">
					<img src="images/login-image.png">
				</div>
				<div class="right-area l-space">
					<h1>Login <span class="black">In</span></h1>
					<div class="m-e-toggle">
						<span class="admin-t active"><a href="login.php">Admin</a></span>
						<span class="manager-t "><a href="login-manager.php">Manager</a></span>
						<span class="employee-t"><a href="login-employee.php">Employee</a></span>
					</div>
					<form class="left-side admin-side active" method="post">
						<input type="text" name="email" placeholder="Email / Admin Id" required>
						<input type="password" name="password" placeholder="Password" required>
						<a class="white-color forgot-pass" style="float: right;" href="forget-password.php">Forget Password</a>
						<input type="submit" value="Login" class="btn normal-btn"></input>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	include 'includes/footer.php';
	?>

</body>

</html>