<?php
	include 'auth_session.php';
	$pageTitle = "Profile";
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
?>

	<div class="wrapper-small">
		<div class="container wd100">
			<div class="side-area-add wd100">
				<h2>Profile</h2> 
			</div>
			<div class="lf-profile-image">
				<?php
					if($userData["profile"]){
						echo '<img src="'.$userData["profile"].'" style="min-width: 290px;max-width: 290px;">';
					}else{
						echo '<img src="images/pngwing.png" style="min-width: 290px;max-width: 290px;">';
					}
				?>
			</div>
			<div class="rg-profile-image">
				<div class="view-task-name">
					<span class="small">Name</span>
					<?php echo $userData['name']; ?>
				</div>
				<div class="view-task-name">
					<span class="small">Phone</span>
					<?php echo $userData['phone']; ?>
				</div>
				<div class="view-task-name">
					<span class="small">Email</span>
					<?php echo $userData['email']; ?>
				</div>
				<br>
				<a href="edit-profile.php" class="btn normal-btn">Edit Profile</a>
			</div>
		</div>
	</div>
	
	<?php
	include 'includes/footer.php';
	?>

</body>

</html>