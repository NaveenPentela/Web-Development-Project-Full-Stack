<?php
include 'auth_session.php';
require('config.php');

$pageTitle = "Notifications";
include 'includes/header.php';
?>

	<div class="wrapper-small">
		<div class="container wd100">
			<h2>Notification</h2>
			<div class="v-task-table">
				<div class="v-task-table-head">
					<div class="table-block">
						<div class="table-alltask">All Notification</div>
						
						
					</div>
				</div>
				<div class="v-task-table-body notification">
				<?php
					$userId = $_SESSION["userId"];
					$query = "SELECT * from notification where user_id ='$userId' ORDER BY id DESC";
					
					$result = mysqli_query($con, $query) or die();
					$rows = mysqli_num_rows($result);
					if ($rows > 0) {
						while($row = mysqli_fetch_array($result)){
							echo '<div class="table-block">
							<div class="table-name">'.$row["description"].'
							</div>
							<div class="table-button">'.$row["created_at"].'</div>
						</div>';
						}
					}
				?>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	include 'includes/footer.php';
	?>
</body>

</html>