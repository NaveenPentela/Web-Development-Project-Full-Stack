<?php
include 'auth_session.php';
if ($_SESSION["role"] != 2) {
	$_SESSION["warning"] = "Oops you had no rights";
	header("Location: index.php");
	exit();
}
require('config.php');
$projectId = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$projectId) {
	header("Location: project.php");
	exit();
}
$mangId = $_SESSION["userId"];
$query    = "SELECT * FROM `projects` WHERE id='$projectId' AND is_active = 1 AND is_deleted = 0 And manager_id='$mangId'";
$result = mysqli_query($con, $query) or die();
$rows = mysqli_num_rows($result);
if ($rows == 1) {
	$project = mysqli_fetch_assoc($result);
} else {
	$_SESSION["warning"] = "Project Not Found!";
	header("Location: project.php");
	exit();
}
$pageTitle = "Create Task";
include 'includes/header.php';

if (isset($_POST['name'])) {
	$name = stripslashes($_REQUEST['name']);
	$description = stripslashes($_REQUEST['description']);
	$due_date = stripslashes($_REQUEST['due_date']);
	$isGroup = 0;
	$assignTo = 0;
	if($_REQUEST['emp_id']){
		$assignTo = stripslashes($_REQUEST['emp_id']);
	}
	else{
		if(!$_REQUEST['group_id']){
			$_SESSION["warning"] = "Assigned Project to Group/Individual !";
			header("Location: create-task.php?id=".$projectId);
			exit();
		}
		$assignTo = stripslashes($_REQUEST['group_id']);
		$isGroup = 1;
	}
	$docPath = "";
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
	$insertProjectQuery = "INSERT INTO tasks (project_id,name, description,is_group,doc,due_date,assign_to)
	 VALUES ('$projectId', '$name', '$description','$isGroup' , '$docPath', '$due_date', '$assignTo')";
	$result = mysqli_query($con, $insertProjectQuery);

	if ($result) {
		// Project creation successful
		if($isGroup){
			$groupQuery = "SELECT user_id FROM user_group WHERE group_id = '$assignTo'";
			$groupResult = mysqli_query($con, $groupQuery);

			if ($groupResult) {
				// Fetch all user IDs in the group
				$userIds = [];
				while ($row = mysqli_fetch_assoc($groupResult)) {
					$userIds[] = $row['user_id'];
				}

				// Insert notifications for each user in the group
				foreach ($userIds as $userId) {
					$message = $_SESSION["name"] . " Assigned you a task with name (" . $name . ") in group.";
					$query = "INSERT INTO notification (user_id, description) VALUES ('$userId', '$message')";
					mysqli_query($con, $query);
				}
			}
		}else{
			$message = $_SESSION["name"] ." Assigned you a task with name (".$name.").";
			$query = "INSERT INTO notification (user_id,description) VALUES ('$assignTo','$message')";
			$result = mysqli_query($con, $query);
		}
		$_SESSION['success'] = "Task created successfully!";
		header("Location: project-task.php?id=".$projectId);
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
			<h2>Create Task</h2>
			<input type="text" name="name" id="name" placeholder="Task Name">
			<textarea placeholder="Full Description" id="description" name="description"></textarea>
			<div>
				<p><strong>Please Upload your attachments</strong></p>
				<input class="btn btn-grey" type="file" id="doc" name="doc">
			</div>
			<input type="date" name="due_date" id="due_date" min="<?php echo date('Y-m-d'); ?>" placeholder="Due Date">
			<input class="btn btn-blue normal-btn next-step notInput" style="width: auto;" data-current-step="step-1" data-next-step="step-2" type="button" value="Assign to"></input>
		</div>
		<div class="container wd100 step-2 d-none">
			<div class="side-area-add wd100">
				<h2>Assign to</h2>
				<div class="side-title-menu ind-grou"><span><a class="select-users-btn active" data-name="individual">Individual</a></span><span><a class="select-users-btn" id="get-groups-btn" data-name="group">Group</a></span></div>
			</div>
			<div class="assign-to-page">
				<input type="text" id="searchInput" class="notInput" placeholder="Search by Name">
				<div class="side-title-menu ind-grou" style="float: right;"><span><a class="btn create-group-btn d-none" style="color: white;" data-name="group">Create New Group</a></span></div>
				<?php
				$query    = "SELECT * FROM `users` WHERE role_id=3 AND is_active = 1 AND is_deleted=0";
				$result = mysqli_query($con, $query) or die();
				$rows = mysqli_num_rows($result);
				if ($rows > 0) {
					echo '<div class="users-here">';
					while ($row = mysqli_fetch_array($result)) {
						echo '<label class="contain user-label" data-name="' . $row["name"] . '">
						<input type="checkbox" class="select-one notInput" name="emp_id" data-id="' . $row["id"] . '"" value="' . $row["id"] . '"">
						<span class="checkmark"></span>' . $row["name"] . '
						</label>';
					}
					echo '</div>';
				}
				?>
			</div>
			<input type="button" style="width: auto;background: #9ca3e1" data-prev-step="step-1" data-current-step="step-2" class="btn btn-blue normal-btn prev-step notInput" value="back"></input>
			<input type="submit" class="btn btn-blue normal-btn notInput" value="Submit"></input>
		</div>
	</form>
	<div class="container wd100 create-group-div d-none">
		<div class="side-area-add wd100">
			<h2>Create Group</h2>
		</div>
		<div class="assign-to-page">
			<input type="text" id="searchInput-group" class="notInput" placeholder="Search by Name">
			<input type="text" class="notInput group-name" placeholder="Create Group Name">
			<div class="group-select-users">

			</div>

		</div>
		<input type="button" style="width: auto;background: #9ca3e1" class="btn btn-blue normal-btn cancel-create-group notInput" value="Cancel"></input>
		<a class="btn btn-blue normal-btn create-group-submit">Create Group</a>
	</div>
</div>

<?php
include 'includes/footer.php';
?>

<script>
	document.addEventListener('click', function(event) {
		if (event.target.classList.contains('cancel-create-group')) {
			document.querySelectorAll('.validate-form').forEach(function(element) {
				element.classList.remove('d-none');
			});
			document.querySelectorAll('.create-group-div').forEach(function(element) {
				element.classList.add('d-none');
			});
		}

		if (event.target.classList.contains('create-group-submit')) {
			var group_name = document.querySelector('.group-name').value;
			var selectedCheckboxes = document.querySelectorAll('input[name="group-users"]:checked');

			if (group_name && group_name !== "") {
				var isValid = selectedCheckboxes.length > 1;

				if (isValid) {
					var mangId = <?php echo $mangId ?>;
					var empIds = Array.from(selectedCheckboxes).map(function(checkbox) {
						return checkbox.value;
					});

					var formData = new FormData();
					formData.append('manager_id', mangId);
					formData.append('group_name', group_name);

					empIds.forEach(function(empId) {
						formData.append('emp_ids[]', empId);
					});

					fetch('create_group_api.php', {
							method: 'POST',
							body: formData
						})
						.then(function(response) {
							return response.json();
						})
						.then(function(data) {
							console.log(data);
							if (data.status === 'success') {
								showPopup("success", "Group Created Successfully!");
								document.querySelector('.group-select-users').innerHTML = "";
								document.querySelectorAll('.validate-form').forEach(function(element) {
									element.classList.remove('d-none');
								});
								document.querySelectorAll('.create-group-div').forEach(function(element) {
									element.classList.add('d-none');
								});
								document.querySelector('#get-groups-btn').click();
							} else {
								console.error('Failed to create group');
								showPopup("error", "Failed to create group");
							}
						})
						.catch(function(error) {
							console.error('Error:', error);
							showPopup("error", "An error occurred while creating the group");
						});
				} else {
					showPopup("error", "Select minimum Two Users");
				}
			} else {
				showPopup("error", "Enter Group Name");
			}
		}

		if (event.target.classList.contains('create-group-btn')) {
			var apiUrl = 'get_users_and_groups.php?type=individual&manager_id=' + <?php echo $mangId ?>;
			fetch(apiUrl)
				.then(function(response) {
					return response.json();
				})
				.then(function(data) {
					console.log(data);
					var body = '';

					data.forEach(function(user) {
						body += '<label class="contain user-label" data-name="' + user.name + '">' +
							'<input type="checkbox" name="group-users" class="group-users notInput" data-id="' + user.id + '" value="' + user.id + '">' +
							'<span class="checkmark"></span>' + user.name +
							'</label>';
					});

					document.querySelector('.group-select-users').innerHTML = body;
				})
				.catch(function(error) {
					console.error('Error:', error);
				});

			document.querySelectorAll('.validate-form').forEach(function(element) {
				element.classList.add('d-none');
			});

			document.querySelectorAll('.create-group-div').forEach(function(element) {
				element.classList.remove('d-none');
			});
		}

		if (event.target.classList.contains('select-users-btn')) {
			document.querySelectorAll('.select-users-btn').forEach(function(button) {
				button.classList.remove('active');
			});
			event.target.classList.add('active');

			var type = event.target.dataset.name;
			var apiUrl = 'get_users_and_groups.php?type=' + type + '&manager_id=' + <?php echo $mangId ?>;

			if (type == "group") {
				document.querySelector('.create-group-btn').classList.remove("d-none");
			} else {
				document.querySelector('.create-group-btn').classList.add("d-none");
			}

			fetch(apiUrl)
				.then(function(response) {
					return response.json();
				})
				.then(function(data) {
					console.log(data);
					var body = '';

					if (type === 'individual') {
						data.forEach(function(user) {
							body += '<label class="contain user-label" data-name="' + user.name + '">' +
								'<input type="checkbox" class="select-one notInput" name="emp_id" data-id="' + user.id + '" value="' + user.id + '">' +
								'<span class="checkmark"></span>' + user.name +
								'</label>';
						});
					} else if (type === 'group') {
						data.forEach(function(group) {
							body += '<label class="contain user-label" data-name="' + group.name + '">' +
								'<input type="checkbox" class="select-one notInput" name="group_id" data-id="' + group.id + '" value="' + group.id + '">' +
								'<span class="checkmark"></span>' + group.name +
								'</label>';
						});
					}

					document.querySelector('.users-here').innerHTML = body;
				})
				.catch(function(error) {
					console.error('Error:', error);
				});
		}
	});

	document.addEventListener('DOMContentLoaded', function() {
		var nextStepButtons = document.querySelectorAll('.next-step');
		var prevStepButtons = document.querySelectorAll('.prev-step');
		var searchInput = document.getElementById('searchInput');
		var searchInputg = document.getElementById('searchInput-group');

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

		searchInputg.addEventListener('input', function() {
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