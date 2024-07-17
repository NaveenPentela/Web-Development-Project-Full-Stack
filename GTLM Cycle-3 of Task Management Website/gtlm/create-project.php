<?php
include 'auth_session.php';
if ($_SESSION["role"] != 1) {
	$_SESSION["warning"] = "Oops you had no rights";
	header("Location: index.php");
	exit();
}
require('config.php');

$pageTitle = "Create Project";
include 'includes/header.php';

if (isset($_POST['name'])) {
	$name = stripslashes($_REQUEST['name']);
	$description = stripslashes($_REQUEST['description']);
	$manager_id = stripslashes($_REQUEST['manager_id']);
	$due_date = stripslashes($_REQUEST['due_date']);
	$docPath="";
	if (isset($_FILES['doc']) && $_FILES['doc']['error'] === UPLOAD_ERR_OK) {
		// File was uploaded successfully
		$originalFileName = pathinfo($_FILES['doc']['name'], PATHINFO_FILENAME); // Extracting the filename without extension
		$extension = pathinfo($_FILES['doc']['name'], PATHINFO_EXTENSION); // Extracting the file extension

		// Adding a timestamp to the filename
		$timestamp = time();
		$newFileName = $originalFileName . '_' . $timestamp . '.' . $extension;

		// Constructing the full file path
		$docPath = 'uploads/files/' . $newFileName;

		// Yo
		move_uploaded_file($_FILES['doc']['tmp_name'], $docPath);
	}

    // Insert project data into your projects table (modify the query according to your database schema)
    $insertProjectQuery = "INSERT INTO projects (manager_id,name, description,doc,due_date) VALUES ('$manager_id', '$name', '$description', '$docPath', '$due_date')";
    $result = mysqli_query($con, $insertProjectQuery);

    if ($result) {
		$message = "Admin Assigned you a project with name (".$name.").";
		$query = "INSERT INTO notification (user_id,description) VALUES ('$manager_id','$message')";
		$result = mysqli_query($con, $query);
        // Project creation successful
        $_SESSION['success'] = "Project created successfully!";
        header("Location: project.php");
        exit();
    } else {
        // Project creation failed
        $_SESSION["error"] = "Failed to create the project. Please try again.";
        header("Location: create-project.php");
        exit();
    }
}

?>

<div class="wrapper-small">
	<form class="validate-form" method="post" enctype="multipart/form-data">
		<div class="container wd100 step-1">
			<h2>Create Project</h2>
			<input type="text" name="name" id="name" placeholder="Task Name" >
			<textarea placeholder="Full Description" id="description" name="description" ></textarea>
			<div>
				<p><strong>Please Upload your attachments</strong></p>
				<input class="btn btn-grey" id="doc" type="file" name="doc" >
			</div>
			<input type="date" name="due_date" id="due_date" min="<?php echo date('Y-m-d'); ?>" placeholder="Due Date" >
			<input class="btn btn-blue normal-btn next-step notInput" id="" style="width: auto;" data-current-step="step-1" data-next-step="step-2" type="button" value="Assign to"></input>
		</div>
		<div class="container wd100 step-2 d-none">
			<div class="side-area-add wd100">
				<h2>Assign to </h2><h3 style="margin-top:10px;"> (Select Manager)</h3>
				<!-- <div class="side-title-menu ind-grou"><span><a class="active" href="project-assign-to.php">Individual</a></span><span><a href="project-assign-to-group.php">Group</a></span></div> -->
			</div>
			<div class="assign-to-page">
				<input type="text" id="searchInput" class="notInput" placeholder="Search by Name">
				<?php
				$query    = "SELECT * FROM `users` WHERE role_id=2 AND is_active = 1 AND is_deleted=0";
				$result = mysqli_query($con, $query) or die();
				$rows = mysqli_num_rows($result);
				if ($rows > 0) {
					while($row = mysqli_fetch_array($result)){
						echo '<label class="contain user-label" data-name="'.$row["name"].'">
						<input type="checkbox" class="select-one notInput" name="manager_id" data-id="'.$row["id"].'"" value="'.$row["id"].'"">
						<span class="checkmark"></span>'.$row["name"].'
						</label>';
					}
				}
				?>
			</div>
			<input type="button" style="width: auto;background: #9ca3e1" data-prev-step="step-1" data-current-step="step-2"
			 class="btn btn-blue normal-btn prev-step notInput" value="back"></input>
			<input type="submit" class="btn btn-blue normal-btn notInput" value="Submit"></input>
		</div>
	</form>
</div>

<?php
include 'includes/footer.php';
?>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		var nextStepButtons = document.querySelectorAll('.next-step');
		var prevStepButtons = document.querySelectorAll('.prev-step');
		var searchInput = document.getElementById('searchInput');

		nextStepButtons.forEach(function(button) {
			
			button.addEventListener('click', function() {
				var hasError = 0;
				var name = document.getElementById('name');
				var description = document.getElementById('description');
				var due_date = document.getElementById('due_date');
				var doc = document.getElementById('doc');
				if (name.value.trim() === '') {
					name.classList.add("alert-border");
					hasError = 1;
				}else{
					name.classList.remove("alert-border");
				}
				if (description.value.trim() === '') {
					description.classList.add("alert-border");
					hasError = 1;
				}else{
					description.classList.remove("alert-border");
				}
				if (due_date.value.trim() === '') {
					due_date.classList.add("alert-border");
					hasError = 1;
				}else{
					due_date.classList.remove("alert-border");
				}
				if (doc.value.trim() === '') {
					doc.classList.add("alert-border");
					hasError = 1;
				}else{
					doc.classList.remove("alert-border");
				}
				if(hasError){
					showPopup('error', "Fill All required fields.");
				}else{
					hideCurrentAndShowNext(button);
				}
			});
		});

		prevStepButtons.forEach(function(button) {
			button.addEventListener('click', function() {
				hideCurrentAndShowPrev(button);
			});
		});

		searchInput.addEventListener('input', function() {
			var searchValue = this.value.toLowerCase();
			
			document.querySelectorAll('.user-label').forEach(function(label) {
				var userName = label.dataset.name.toLowerCase();
				
				if (userName.includes(searchValue)) {
					label.style.display = 'block';
				} else {
					label.style.display = 'none';
				}
			});
		});

		function hideCurrentAndShowNext(button) {
			var currentStep = button.dataset.currentStep;
			var nextStep = button.dataset.nextStep;

			document.querySelector('.' + currentStep).classList.add('d-none');
			document.querySelector('.' + nextStep).classList.remove('d-none');
		}

		function hideCurrentAndShowPrev(button) {
			var currentStep = button.dataset.currentStep;
			var prevStep = button.dataset.prevStep;

			document.querySelector('.' + currentStep).classList.add('d-none');
			document.querySelector('.' + prevStep).classList.remove('d-none');
		}
	});
</script>

</body>

</html>