<?php
include 'auth_session.php';

if($_SESSION["role"] == 3) {
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
if($_SESSION["role"] == 1) {
	$query    = "SELECT * FROM `projects` WHERE id='$projectId' AND is_active = 1 AND is_deleted = 0";
}
$result = mysqli_query($con, $query) or die();
$rows = mysqli_num_rows($result);
if ($rows == 1) {
	$project = mysqli_fetch_assoc($result);
	$query = "SELECT
    t.id AS task_id,
    t.name AS task_name,
    t.description AS task_description,
    t.is_group AS is_task_group,
    t.assign_to AS task_assign_to,
    t.status AS status,
    u.id AS user_id,
    u.name AS user_name,
    u.email AS user_email,
    g.id AS group_id,
    g.name AS group_name,
    t.due_date As created_at
FROM
    tasks t
LEFT JOIN
    users u ON t.assign_to = u.id AND t.is_group = 0
LEFT JOIN
    groups g ON t.assign_to = g.id AND t.is_group = 1
WHERE
    t.project_id = '$projectId' AND t.is_active=1 AND t.is_deleted=0" ;

	$result = mysqli_query($con, $query) or die(mysqli_error($con));
	$rows = mysqli_num_rows($result);
}else{
	$_SESSION["warning"] = "Project Not Found!";
	header("Location: project.php");
	exit();
}

$pageTitle = "Project Tasks";
include 'includes/header.php';
?>

	<div class="wrapper-small">
		<div class="container wd100">
			<div class="side-area-add side-area-add-b wd100">
				<h2><?php echo $project["name"]; ?> Tasks</h2> 
				<?php
				if($_SESSION["role"] == 2) {
				echo '<div class="side-title-menu"><a class="btn" href="create-task.php?id='.$project["id"].'">Create Task</a></div>';
				}
				?>
			</div>
			<div class="v-task-table">
				<div class="v-task-table-head">
					<div class="table-block">
						<div class="table-alltask">All Tasks</div>
						<?php
						if ($rows > 1) {
							echo '<div class="sorting">
								<div class="sort-area" onclick="myFunction()">Sort <img src="images/sort-icon.png"></div>
								<div id="sortpopup" class="sorting-menu">
									<ul>
										<li id="getduedata" class="getduedata">Order By Due Date</li>
										<li id="getcreatedat">Order By Created Date</li>
									</ul>
								</div>
							</div>';
						}
						?>
					</div>
				</div>
				<table class="table table-striped" style="width:100%;">
					<thead>
						<tr>
							<th>No#</th>
							<th>Name</th>
							<th>Description</th>
							<th>Assign To</th>
							<th>Group/User</th>
							<th>Status</th>
							<th>Due Date</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody class="data-here">
						<?php
						if ($rows > 0) {
							$count = 1;
							while($row = mysqli_fetch_array($result)){
								echo '<tr>
									<td>'.$count++.'</td>
									<td>'.$row["task_name"].'</td>
									<td>'.$row["task_description"].'</td>';
									if($row["is_task_group"] == 1){
										echo '<td>Group</td>
											<td>'.$row["group_name"].'</td>';
									}else{
										echo '<td>Individual</td>
										<td>'.$row["user_name"].'</td>';
									}
									if($row["status"] == 1){
										echo '<td>To-Do</td>';
									}elseif($row["status"] == 2){
										echo '<td>In-Process</td>';
									}else{
										echo '<td>Done</td>';
									}
								echo '<td>'.date('d-M-Y', strtotime($row["created_at"])).'</td>
									<td><div class="table-button menu-dot">
											<ul>
												<li><img src="images/dots.png">
													<ul>
														<li><a href="task-details.php?id='.$row["task_id"].'&projectId='.$projectId.'">View Task</a></li>';
														if($_SESSION["role"] == 2){
															echo '<li><a href="delete-project-task.php?id='.$row["task_id"].'&projectId='.$projectId.'">Delete Tasks</a></li>';
														}
													echo '
													</ul>
												</li>
											</ul>
										</div>
									</td>
								</tr>';
							}
						}else{
							echo '<td style="text-align: center;" colspan="8">No Tasks Avilable on this project!</td>';
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th>No#</th>
							<th>Name</th>
							<th>Description</th>
							<th>Assign To</th>
							<th>Group/User</th>
							<th>Status</th>
							<th>Due Date</th>
							<th>Action</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
	
	<?php
	include 'includes/footer.php';
	?>

<script>
		var mybtn = document.getElementById('getduedata');
		var mybtn1 = document.getElementById('getcreatedat');
		mybtn.addEventListener('click',function(){
			var formData = new FormData();
			var proId = <?php echo $projectId; ?>;
			formData.append('projectId', proId);
			formData.append('order_by', "due_date");
			fetch('sort_task_api.php', {
				method: 'POST',
				body: formData
			})
			.then(function(response) {
				return response.json();
			})
			.then(function(data) {
				console.log(data);
				if (data.status === 'success') {
					document.querySelector('.data-here').innerHTML = data.message;
				} else {
					showPopup("error", data.message);
				}
			})
			.catch(function(error) {
				console.error('Error:', error);
				showPopup("error", "Some thing went wrong!");
			});
		})
		mybtn1.addEventListener('click',function(){
			var formData = new FormData();
			var proId = <?php echo $projectId; ?>;
			formData.append('projectId', proId);
			formData.append('order_by', "created_at");
			fetch('sort_task_api.php', {
				method: 'POST',
				body: formData
			})
			.then(function(response) {
				return response.json();
			})
			.then(function(data) {
				console.log(data);
				if (data.status === 'success') {
					document.querySelector('.data-here').innerHTML = data.message;
				} else {
					showPopup("error", data.message);
				}
			})
			.catch(function(error) {
				console.error('Error:', error);
				showPopup("error", "Some thing went wrong!");
			});
		})
	</script>
</body>

</html>