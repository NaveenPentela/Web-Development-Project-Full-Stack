<?php
	include 'auth_session.php';
	$pageTitle = "Edit Profile";
	include 'includes/header.php';

	require('config.php');
	if (!isset($_SESSION)) {
        session_start();
    }
	$userId = $_SESSION['userId'];
	$query    = "SELECT * FROM `users` WHERE id='$userId'";
	$result = mysqli_query($con, $query) or die();
	$rows = mysqli_num_rows($result);
	if ($rows == 1) {
		$userData = mysqli_fetch_assoc($result);
		$_SESSION['name'] = $userData['name'];
	}

	if (isset($_POST['phone'])) {
		$phone = stripslashes($_REQUEST['phone']);
		$name = stripslashes($_REQUEST['name']);

		if(!$phone || !$name){
			$_SESSION["error"] = "Fill All Details!";
			header("Location: edit-profile.php");
			exit();
		}

		$image=null;
		if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
			// File was uploaded successfully
			$originalFileName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME); // Extracting the filename without extension
			$extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); // Extracting the file extension

			$timestamp = time();
			$newFileName = $originalFileName . '_' . $timestamp . '.' . $extension;
			$image = 'uploads/profile/' . $newFileName;

			// Yo
			move_uploaded_file($_FILES['image']['tmp_name'], $image);
		}
		if($image){
			$update="UPDATE users SET name='$name', phone='$phone',profile='$image' WHERE id='$userId'";
		}
		else{
			$update="UPDATE users SET name='$name', phone='$phone' WHERE id='$userId'";
		}
			
		$result = mysqli_query($con, $update) or die();
		if ($result) {
			$_SESSION['name'] = $name;
			// Redirect to user dashboard page
			$_SESSION["success"] = "Profile Updated Successfully!";
			header("Location: profile.php");
			exit();
		} else {
			$_SESSION["error"] = "Something went wrong!";
			header("Location: profile.php");
			exit();
			
		}
	}
?>

	<div class="wrapper-small">
		<div class="container wd100">
			<div class="side-area-add wd100">
				<h2>Edit Profile</h2> 
				<!-- <div class="side-title-menu pr"><span>Status</span><span><a href="profile-password.php">Password</a></span><span>Logout</span></div> -->
			</div>
			<div class="lf-profile-image">
				<?php
					if($userData["profile"]){
						echo '<img src="'.$userData["profile"].'" style="min-width: 290px;max-width: 290px;">';
					}else{
						echo '<img src="images/pngwing.png" style="min-width: 290px;max-width: 290px;">';
					}
				?>
				
				<!-- <span><img src="images/camera-icon-image.png" for="user-profile"></span> -->
			</div>
			<form class="rg-profile-image" method="post" enctype="multipart/form-data">
				<input class="" type="file" name="image" accept="image/*" id="user-profile">
				<input type="text" name="name" value="<?php echo $userData['name']; ?>" required>
				<input type="text" name="phone" value="<?php echo $userData['phone']; ?>" placeholder="+61 123 456 7890" required>
				<input type="email" disabled value="<?php echo $userData['email']; ?>">
				<br><br>
				<input type="submit" class="btn normal-btn" value="Save Changes"></input>
			</form>
		</div>
	</div>
	
	<?php
	include 'includes/footer.php';
	?>
	
</body>

</html>