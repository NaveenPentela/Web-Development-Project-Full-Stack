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
		<div class="container wd100 employee-new">
			<h2>Hi <?php echo $_SESSION["name"];?></h2>
			<div class="v-task-table to-do">
				<div class="v-task-table-head">
					<div class="table-block">
						<div class="table-alltask">TO DO</div>
					</div>
				</div>
				
				<div class="v-task-table-body" id="1">
					
				</div>
			</div>
			<div class="v-task-table working-on-it">
				<div class="v-task-table-head">
					<div class="table-block">
						<div class="table-alltask">Working On It</div>
					</div>
				</div>
				
				<div class="v-task-table-body"  id="2">
				</div>
			</div>
			<div class="v-task-table done-pro">
				<div class="v-task-table-head">
					<div class="table-block">
						<div class="table-alltask">Done</div>
					</div>
				</div>
				
				<div class="v-task-table-body"  id="3">
				</div>
			</div>
			
		</div>
	</div>
	
	<?php
	include 'includes/footer.php';
	?>
	
<script src="js/ajax-demo.js"></script>
<script>
	var currTask=0;
	function updateTaskStatus(taskId, newStatus) {
		// Perform a fetch request to update the task status
		fetch('update_task_status.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded', // Adjust content type as needed
			},
			body: 'task_id=' + encodeURIComponent(taskId) + '&status=' + encodeURIComponent(newStatus),
		})
		.then(response => response.json())
		.then(data => {
			if (data.status === 'success') {
				showPopup("success", "Status Changed successfully!");
			} else {
				showPopup("error", "Failed to update task");
				window.setTimeout(function() {
					location.reload();
				}, 1000);
			}
		})
		.catch(error => {
			showPopup("error", 'Error updating task status');
			window.setTimeout(function() {
				location.reload();
			}, 1000);
		});
	}
	dragula([
		document.getElementById('1'),
		document.getElementById('2'),
		document.getElementById('3')
	])

	.on('drag', function(el) {
		el.classList.add('is-moving');
		currTask=el.closest('.v-task-table-body').getAttribute("id");
	})
	.on('dragend', function(el) {
		
		// remove 'is-moving' class from element after dragging has stopped
		el.classList.remove('is-moving');
		
		// add the 'is-moved' class for 600ms then remove it
		window.setTimeout(function() {
			el.classList.add('is-moved');
			window.setTimeout(function() {
				el.classList.remove('is-moved');
			}, 600);
		}, 100);
		if(currTask != el.closest('.v-task-table-body').getAttribute("id")){
			updateTaskStatus(el.getAttribute("data-id"),el.closest('.v-task-table-body').getAttribute("id"));
		}
	});
	
	fetch('get_tasks_by_employee.php')
    .then(response => response.json())
    .then(data => {
		
		data.forEach(function(task) {
			
			var body = '<div class="table-block drag-item" data-id='+task.id+'>\
				<a href="task-details.php?id='+task.id+'&projectId='+task.project_id+'" class="table-name">'+task.name;
				if(task.is_group == '1'){
					body += ' (Grouped)';
				}
				body += '</a></div>';

			if(task.status == 1){
				document.getElementById('1').innerHTML += body;
			}else if(task.status == 2){
				document.getElementById('2').innerHTML += body;
			}else{
				document.getElementById('3').innerHTML += body;
			}
		})
    })
    .catch(error => {
        console.error('Error:', error);
    });
</script>
</body>

</html>