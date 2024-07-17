<?php
include 'auth_session.php';
if ($_SESSION["role"] == 3) {
	$_SESSION["warning"] = "Oops you had no rights";
	header("Location: index.php");
	exit();
}
require('config.php');
$userId = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$userId) {
    header("Location: user-management.php");
    exit();
}

$pageTitle = "Create User";
include 'includes/header.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = stripslashes($_POST['name']);
    $phone = stripslashes($_POST['phone']);

    
    $insertUserQuery = "UPDATE users SET name='$name', phone='$phone' WHERE id='$userId'";
    $result = mysqli_query($con, $insertUserQuery);

    if ($result) {
        $_SESSION['success'] = "User Updated successfully!";
        header("Location: user-management.php");
        exit();
    } else {
        // User creation failed
        $_SESSION["error"] = "Failed to Update the user. Please try again.";
        header("Location: user-management.php");
        exit();
    }
}


?>

<div class="wrapper-small">
	<form class="active" method="post" enctype="multipart/form-data">
		<div class="container wd100 step-1">
			<h2>Update User</h2>
            <?php
            $query    = "SELECT * FROM `users` WHERE id='$userId' AND is_deleted = 0";
            $result = mysqli_query($con, $query) or die();
            $rows = mysqli_num_rows($result);
            if ($rows == 1) {
                $userDetail = mysqli_fetch_assoc($result);
                echo '<input type="text" name="name" value="'.$userDetail["name"].'" placeholder="Name" required>
                <input type="email" disabled value="'.$userDetail["email"].'" placeholder="Email Address">
                <input type="text" name="phone" value="'.$userDetail["phone"].'" placeholder="Phone" required>';
            }
            else{
                $_SESSION["warning"] = "User Not Found!";
                header("Location: user-management.php");
                exit();
            }
            ?>
			
			<input class="btn btn-blue normal-btn" type="submit" value="Submit"></input>
		</div>
	</form>
</div>

<?php
include 'includes/footer.php';
?>

</body>

</html>