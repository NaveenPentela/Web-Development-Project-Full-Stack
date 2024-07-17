<?php
	include 'auth_session.php';
	if($_SESSION["role"] == 3) {
		$_SESSION["warning"] = "Oops you had no rights";
		header("Location: index.php");
		exit();
	}
	require('config.php');
	$query    = "SELECT
			projects.*,
			users.name AS manager_name,
			users.email AS manager_email,
			users.phone AS manager_phone
		FROM
			projects
		JOIN
			users ON projects.manager_id = users.id
		WHERE
			projects.is_active = 1
			AND projects.is_deleted = 0
		ORDER BY
			projects.due_date DESC";
	if($_SESSION["role"] == 2){
		$mId = $_SESSION["userId"];
		$query    = "SELECT
			projects.*,
			users.name AS manager_name,
			users.email AS manager_email,
			users.phone AS manager_phone
		FROM
			projects
		JOIN
			users ON projects.manager_id = users.id
		WHERE
			projects.is_active = 1
			AND projects.is_deleted = 0 And manager_id ='$mId' 
		ORDER BY
			projects.due_date DESC";
	}
	$result = mysqli_query($con, $query) or die();
	$rows = mysqli_num_rows($result);
	$pageTitle = "Projects";
	include 'includes/header.php';
?>

	<div class="wrapper-small">
		<div class="container wd100">
			<div class="side-area-add side-area-add-b wd100">
				<h2>Project</h2> 
				<?php 
				if($_SESSION["role"] == 1){
				echo '<div class="side-title-menu"><a class="btn" href="create-project.php">Create project</a></div>';
				}
				?>
			</div>
			<div class="v-task-table">
				<div class="v-task-table-head">
					<div class="table-block">
						<div class="table-alltask">All Projects</div>
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
							<?php
							if($_SESSION["role"] == 1){
								echo '<th>Manager</th>';
							}
							?>
							<th width="18%">Status</th>
							<th>Created Date</th>
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
									<td>'.$row["name"].'</td>
									<td>'.$row["description"].'</td>';
									if($_SESSION["role"] == 1){
									echo '<td>'.$row["manager_name"].'</td>';
									}
									if($row["status"] == 1){
										echo '<td><div class="project-head orange status-datatable">in process</div></td>';
									}else{
										echo '<td><div class="project-head green status-datatable">Completed</div></td>';
									}
								echo '<td>'.date('d-M-Y', strtotime($row["created_at"])).'</td>
									<td>'.date('d-M-Y', strtotime($row["due_date"])).'</td>
									<td><div class="table-button menu-dot">
											<ul>
												<li><img src="images/dots.png">
													<ul>
														<li><a href="project-detail.php?id='.$row['id'].'">View Project</a></li>
														<li><a href="project-task.php?id='.$row['id'].'">Create/View Tasks</a></li>';
														if($_SESSION["role"] == 1 && $row["status"] == 1){
															echo '
															<li><a href="edit-project.php?id='.$row['id'].'">Edit Project</a></li>
															<li><a href="delete-project.php?id='.$row['id'].'">Delete Project</a></li>';
														}
														if($_SESSION["role"] == 2 && $row["status"] == 1){
															echo '<li><a href="complete-project.php?id='.$row['id'].'">Complete Project</a></li>';
														}
												echo '</ul>
												</li>
											</ul>
										</div>
									</td>
								</tr>';
							}
						}else{
							echo '<td style="text-align: center;" colspan="7">No Tasks Avilable on this project!</td>';
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th>No#</th>
							<th>Name</th>
							<th>Description</th>
							<?php
							if($_SESSION["role"] == 1){
								echo '<th>Manager</th>';
							}
							?>
							<th>Status</th>
							<th>Created Date</th>
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
			formData.append('order_by', "due_date");
			fetch('sort_project_api.php', {
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
			formData.append('order_by', "created_at");
			fetch('sort_project_api.php', {
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