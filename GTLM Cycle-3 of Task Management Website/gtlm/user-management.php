<?php
	include 'auth_session.php';
	if($_SESSION["role"] != 1) {
		$_SESSION["warning"] = "Oops you had no rights";
		header("Location: index.php");
		exit();
	}
	require('config.php');
	$pageTitle = "User Management";
	include 'includes/header.php';
?>

	<div class="wrapper-small">
		<div class="container wd100">
			<div class="side-area-add side-area-add-b wd100">
				<h2>User Management</h2> 
				<div class="side-title-menu"><a class="btn" href="create-user.php">Create User</a></div>
			</div>
			<div class="v-task-table">
				<div class="v-task-table-head">
					<div class="table-block">
						<div class="table-alltask">All Users</div>
					</div>
				</div>
				<table class="table table-striped" style="width:100%;">
					<thead>
						<tr>
							<th>No#</th>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Type</th>
							<th>Status</th>
							<th>Join Date</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$userId = $_SESSION['userId'];
						$role = $_SESSION['role'];
						$query    = "SELECT * FROM users WHERE is_deleted = 0 AND id!='$userId'";
						if($role == 2){
							$query    = "SELECT * FROM users WHERE is_deleted = 0 AND role_id=3";
						}
						$result = mysqli_query($con, $query) or die();
						$rows = mysqli_num_rows($result);
						if ($rows > 0) {
							$count = 1;
							while($row = mysqli_fetch_array($result)){
								echo '<tr>
									<td>'.$count++.'</td>
									<td>'.$row["name"].'</td>
									<td>'.$row["email"].'</td>
									<td>'.$row["phone"].'</td>';
									if($row["role_id"] == 2){
										echo '<td>Manager</td>';
									}else{
										echo '<td>Employee</td>';
									}
									if($row["is_active"] == 1){
										echo '<td style="text-align: center;"><div class="project-head green status-datatable">Active</div></td>';
									}else{
										echo '<td style="text-align: center;"><div class="project-head red status-datatable">Deactivate</div></td>';
									}
								echo '<td>'.date('d-M-Y', strtotime($row["created_at"])).'</td>
									<td><div class="table-button menu-dot">
											<ul>
												<li><img src="images/dots.png">
													<ul>
														<li><a href="edit-user.php?id='.$row["id"].'">Edit Detail</a></li>
														<li><a href="deactive-user.php?id='.$row["id"].'">Activate/Deactivate User</a></li>
														<li><a href="delete-user.php?id='.$row["id"].'">Delete User Details</a></li>
													</ul>
												</li>
											</ul>
										</div>
									</td>
								</tr>';
							}
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th>No#</th>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Type</th>
							<th>Status</th>
							<th>Join Date</th>
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
</body>

</html>