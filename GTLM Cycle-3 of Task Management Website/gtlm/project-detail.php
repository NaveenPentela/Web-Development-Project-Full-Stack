<?php
	include 'auth_session.php';
	if($_SESSION["role"] == 3) {
		$_SESSION["warning"] = "Oops you had no rights";
		header("Location: index.php");
		exit();
	}
	require('config.php');

    $projectId = isset($_GET['id']) ? intval($_GET['id']) : null;
    if(!$projectId){
        header("Location: index.php");
        exit();
    }

	$pageTitle = "Project Detail";
	include 'includes/header.php';
?>

	<div class="wrapper-small">
		<div class="container wd100">
            <?php
                $query    = "SELECT * FROM `projects` WHERE id='$projectId' AND is_active = 1 AND is_deleted = 0";
                $result = mysqli_query($con, $query) or die();
                $rows = mysqli_num_rows($result);
                if ($rows == 1) {
                    $project = mysqli_fetch_assoc($result);
                    if($project["status"] == 1){
                        echo '<div class="project-head orange">in process</div>';
                    }
                    else{
                        echo '<div class="project-head green">Completed</div>';
                    }
                    echo '
                    <div class="view-task-name">
                        <span class="small">Project Name</span>
                        '.$project["name"].'
                    </div>
                    <div class="view-task-description">
                        <span class="small">Full Description</span>
                        '.$project["description"].'
                    </div>';
                    if (!empty($project["doc"])) {
                        echo '<div class="view-task-attached">
                            <span class="small">Attached</span>
                            <div class="files"><a href="'.$project["doc"].'" target="_blank">Attachment</a></div>
                        </div>';
                    }
                echo '<div class="view-task-due-date">
                        <span class="small">Due Date</span>
                        '.date('d-M-Y', strtotime($project["due_date"])).'
                    </div>
                    <div class="view-task-due-date">
                        <a class="btn btn-blue" href="javascript:history.back()">Back</a>
                    </div>';
                }
                else{
                    $_SESSION["warning"] = "Project Not Found!";
                    header("Location: project.php");
                    exit();
                }
            ?>
			
			
		</div>
	</div>
	
	<?php
	include 'includes/footer.php';
	?>

</body>

</html>