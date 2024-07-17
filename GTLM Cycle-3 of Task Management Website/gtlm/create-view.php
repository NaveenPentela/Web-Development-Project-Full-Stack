<?php
include 'auth_session.php';

if($_SESSION["role"] != 2) {
	$_SESSION["warning"] = "Oops you had no rights";
	header("Location: index.php");
	exit();
}
include 'includes/header.php';
?>

	<div class="cr-vr-task-bc wd100">
		<div class="wrapper">
			<div class="cr-vr-task wd100">
				<div class="left-area">
					<img src="images/login-image.png">
				</div>
				<div class="right-area l-space">
					<h1>Be Organised <span class="black">Start Now.</span></h1>
					<p><a class="btn" href="project.php">Project</a></p><br>
				</div>
				
			</div>
		</div>
	</div>
	
	<?php
	include 'includes/footer.php';
	?>

</body>

</html>