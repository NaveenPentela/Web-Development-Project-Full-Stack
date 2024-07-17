<?php
include 'auth_session.php';
if ($_SESSION["role"] != 1) {
	$_SESSION["warning"] = "Oops you had no rights";
	header("Location: index.php");
	exit();
}
require('config.php');

$pageTitle = "Create User";
include 'includes/header.php';

// Function to generate a random password
function generateRandomPassword($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = stripslashes($_POST['name']);
    $email = stripslashes($_POST['email']);
    $phone = stripslashes($_POST['phone']);
    $type = intval($_POST['type']);

    $emailCheckQuery = "SELECT * FROM users WHERE email = '$email'";
    $emailCheckResult = mysqli_query($con, $emailCheckQuery);

    if (mysqli_num_rows($emailCheckResult) > 0) {
        // Email already exists
        $_SESSION["error"] = "Account already exists.";
        header("Location: create-user.php"); // Redirect to the registration page or handle the error as needed
        exit();
    }

    $password = generateRandomPassword();
    $hashedPassword = md5($password);
    
    $insertUserQuery = "INSERT INTO users (name, email, phone, role_id, password) VALUES ('$name', '$email', '$phone', $type, '$hashedPassword')";
    $result = mysqli_query($con, $insertUserQuery);

    if ($result) {
        // User creation successful
        require 'vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['mail_username'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
        $mail->Port = $_ENV['MAIL_PORT'];
        
        $mail->setFrom($_ENV['mail_username'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($email);
        $mail->Subject = 'Account Created';
        $mail->Body = "Hello, $email you account created Successfully. User this password to login your account pass:$password.";
        if (!$mail->send()) {
            $successd = 1;
        }

        $_SESSION['success'] = "User created successfully!";
        header("Location: user-management.php");
        exit();
    } else {
        // User creation failed
        $_SESSION["error"] = "Failed to create the user. Please try again.";
        header("Location: create-user.php");
        exit();
    }
}


?>

<div class="wrapper-small">
	<form class="active" method="post" enctype="multipart/form-data">
		<div class="container wd100 step-1">
			<h2>Create User</h2>
			<input type="text" name="name" placeholder="Name" required>
			<input type="email" name="email" placeholder="Email Address" required>
			<input type="text" name="phone" placeholder="Phone" required>
            
                <?php
                if($_SESSION["role"] == 1){
                echo '<div class="table-manager-employee">
                    <select name="type" required>
                    <option value="" selected disabled>Select Employee Type</option>
                    <option value="2">Manager</option>
                    <option value="3">Employee</option>';
                }else{
                    echo '<div class="table-manager-employee d-none">
                    <select name="type" required><option value="3" selected>Employee</option>';
                }
                ?>
                </select>
            </div>
			<input class="btn btn-blue normal-btn" type="submit" value="Submit"></input>
		</div>
	</form>
</div>

<?php
include 'includes/footer.php';
?>

</body>

</html>