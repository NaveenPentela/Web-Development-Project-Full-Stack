<?php
include 'auth_session.php';
include 'includes/header.php';
?>

	<div class="wrapper-small">
		<div class="container wd100">
			<h2>Edit Task</h2>
			
				<input type="text" placeholder="Lorem Ipsum is dummy Task">
				<textarea placeholder="Lorem Ipsum is dummy Task">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing.</textarea>
				<div>
					<p><strong>Please Upload your attachments</strong></p>
					<input class="btn btn-grey" type="submit" value="Choose File"> 
				</div>
				<input type="date" placeholder="Due Date">
				<a class="btn normal-btn" href="assign-to.php">Assign to</a>
			
			<button class="btn btn-red sh-btn" href="#" onclick="show('popup')">Delete</button>
		</div>
	</div>
	
	<?php
	include 'includes/footer.php';
	?>
	

</body>

</html>