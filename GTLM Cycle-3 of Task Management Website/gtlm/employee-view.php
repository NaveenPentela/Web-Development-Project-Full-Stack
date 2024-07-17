<?php
include 'auth_session.php';

if($_SESSION["role"] != 3) {
	$_SESSION["warning"] = "Oops you had no rights";
	header("Location: index.php");
	exit();
}
include 'includes/header.php';
?>

	<div class="wrapper-small">
		<div class="container wd100">
			<div class="view-task-name">
				<span class="small">Task Name</span>
				Full Task Name
			</div>
			<div class="view-task-description">
				<span class="small">Full Description</span>
				Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
			</div>
			<div class="view-task-attached">
				<span class="small">Attached</span>
				<div class="files"><a href="#">file-name.pdf</a></div>
			</div>
			<div class="view-task-due-date">
				<span class="small">Due Date</span>
				30 - 08 - 23
			</div>
			<div class="button-three">
				<div class="project-head not-started">Not Started</div>
				<div class="project-head orange">In Process</div>
				<div class="project-head done">Done</div>
			</div>
			<br>
			<div class="view-task-due-date">
				<a class="btn btn-blue" href="javascript:history.back()">Back</a>
			</div>
		</div>
	</div>
	
	<?php
	include 'includes/footer.php';
	?>

</body>

</html>