<?php
	include 'auth_session.php';
	if($_SESSION["role"] != 1) {
		$_SESSION["warning"] = "Oops you had no rights";
		header("Location: index.php");
		exit();
	}
	require('config.php');
	$pageTitle = "Contact Inquiry";
	include 'includes/header.php';
?>

	<div class="wrapper-small">
		<div class="container wd100">
			<div class="side-area-add side-area-add-b wd100">
				<h2>Contact Inquiry</h2> 
			</div>
			<div class="v-task-table">
				<div class="v-task-table-head">
					<div class="table-block">
						<div class="table-alltask">All Inquirys</div>
						
					</div>
				</div>
				<table class="table table-striped" style="width:100%;">
					<thead>
						<tr>
							<th>No#</th>
							<th>Name</th>
							<th>Email</th>
							<th>Subject</th>
							<th>Message</th>
							<th>Created Date</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$query    = "SELECT *FROM contact_inquiry ORDER BY id DESC";
						$result = mysqli_query($con, $query) or die();
						$rows = mysqli_num_rows($result);
						if ($rows > 0) {
							$count = 1;
							while($row = mysqli_fetch_array($result)){
								echo '<tr>
									<td>'.$count++.'</td>
									<td>'.$row["name"].'</td>
									<td>'.$row["email"].'</td>
                                    <td>'.$row["subject"].'</td>
                                    <td>'.$row["message"].'</td>
                                    <td>'.date('d-M-Y', strtotime($row["created_at"])).'</td>
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
							<th>Subject</th>
							<th>Message</th>
							<th>Created Date</th>
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