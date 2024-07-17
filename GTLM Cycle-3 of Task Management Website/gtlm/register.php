<?php
include 'includes/header.php';
if (!isset($_SESSION)) {
	session_start();
}
if (isset($_SESSION["email"])) {
	$_SESSION["warning"] = "Oops! Already logIn";
	header("Location: index.php");
	exit();
}
if (isset($_POST['email'])) {
	require('config.php');
    $type = stripslashes($_REQUEST['type']);
    $name = stripslashes($_REQUEST['name']);
    $email = stripslashes($_REQUEST['email']);
    $phone = stripslashes($_REQUEST['phone']);
    $password = stripslashes($_REQUEST['password']);
    $password = md5($password);

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

    // Check if the email already exists
    $emailCheckQuery = "SELECT * FROM users WHERE email = '$email'";
    $emailCheckResult = mysqli_query($con, $emailCheckQuery);

    if (mysqli_num_rows($emailCheckResult) > 0) {
        // Email already exists
        $_SESSION["error"] = "Email already exists. Please use a different email address.";
        header("Location: register.php"); // Redirect to the registration page or handle the error as needed
        exit();
    }
	$role_id = 2;
	if($type === "employee"){
		$role_id = 3;
	}
    // Email does not exist, proceed with registration
    $query = "INSERT INTO users (name, email, phone, password, role_id) VALUES ('$name', '$email', '$phone', '$password', '$role_id')";
    $result = mysqli_query($con, $query);

    if ($result) {
        // Registration successful
        $_SESSION["success"] = "Registration successful!";
		if($role_id == 2){
	        header("Location: login-employee.php");
			exit();
		}
		else{
	        header("Location: login-manager.php");
	        header("Location: login-manager.php");
        	exit();
		}
    } else {
        // Registration failed
        $_SESSION["error"] = "Registration failed. Please try again.";
		header("Location: register.php");
        exit();
		echo "Error: " . mysqli_error($con);
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
				<h1>Register <span class="black">Here</span></h1>
				<div class="m-e-toggle">
					<span class="manager-t active user-type">Manager</span>
					<span class="employee-t user-type">Employee</span>
				</div>
				<form class="left-side manager-side active" method="post">
					<input type="hidden" name="type" class="user-type-save" value="manager" required>
					<input type="text" name="name" placeholder="Name" required>
					<input type="email" name="email" placeholder="Email Address" required>
					<input type="text" name="phone" placeholder="Phone Number" required>
					<input type="password" name="password" placeholder="Password" required>
					<input type="password" name="confirm_password" placeholder="Confirm Password" required>
					<label class="contain">
						<input type="checkbox">
						<span class="checkmark"></span> By clicking the "Sign Up" button, you confirm that you have read, understood, and agree to be bound by the following terms and conditions
					</label>
					<input class="btn" type="submit" value="Submit">
					<a href="login.php" class="btn btn-transparent">Login</a>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
include 'includes/footer.php';
?>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		var userTypeButtons = document.querySelectorAll('.user-type');
		var userTypeSave = document.querySelector('.user-type-save');

		userTypeButtons.forEach(function (button) {
			button.addEventListener('click', function () {
				userTypeButtons.forEach(function (btn) {
					btn.classList.remove('active');
				});
				userTypeSave.value = "employee";
				button.classList.add('active');
				if (button.classList.contains('manager-t')) {
					userTypeSave.value = "manager";
				}
			});
		});

		var typeFromPHP = <?php echo isset($_GET['type']) ? json_encode($_GET['type']) : null; ?>;

		if (typeFromPHP !== null && typeFromPHP === "employee") {
			userTypeSave.value = "employee";
			document.querySelector('.manager-t').classList.remove('active');
			document.querySelector('.employee-t').classList.add('active');
		}
	});
	document.addEventListener('DOMContentLoaded', function () {
		document.querySelector('form').addEventListener('submit', function (event) {
			console.log("here");
			var password = document.querySelector('input[name="password"]').value;
			var confirmPassword = document.querySelector('input[name="confirm_password"]').value;
			var signUpCheckbox = document.querySelector('input[type="checkbox"]');

			// Check if password meets length requirement
			if (password.length < 6) {
				showPopup('error', "Password must be at least 6 characters long");
				event.preventDefault();
				return false;
			}

			// Check if password and confirm password match
			if (password !== confirmPassword) {
				showPopup('error', "Password and Confirm Password do not match");
				event.preventDefault();
				return false;
			}
			if (!signUpCheckbox.checked) {
				showPopup('error', "Please agree to the terms and conditions before submitting.");
				event.preventDefault();
				return false;
			}
			return true; // Allow form submission
		});
	});
</script>

</body>

</html>