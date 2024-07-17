<?php
	include 'auth_session.php';
	// if($_SESSION["role"] != 1) {
	// 	$_SESSION["warning"] = "Oops you had no rights";
	// 	header("Location: index.php");
	// 	exit();
	// }
	require('config.php');

    $taskId = isset($_GET['id']) ? intval($_GET['id']) : null;
    if (!$taskId) {
        if($_SESSION["role"]==3){
            header("Location: employee-dashboard.php");
            exit();
        }
        header("Location: project.php");
        exit();
    }
    $projectId = isset($_GET['projectId']) ? intval($_GET['projectId']) : null;
    if (!$projectId) {
        if($_SESSION["role"]==3){
            header("Location: employee-dashboard.php");
            exit();
        }
        header("Location: project.php");
        exit();
    }

	$pageTitle = "Task Detail";
	include 'includes/header.php';
?>

	<div class="wrapper-small">
		<div class="container wd100">
            <?php
                $query    = "SELECT * FROM `tasks` WHERE project_id='$projectId' AND id='$taskId'";
                $result = mysqli_query($con, $query) or die();
                $rows = mysqli_num_rows($result);
                if ($rows == 1) {
                    $tasks = mysqli_fetch_assoc($result);
                    $assignTo = "Individual";
                    $getId = $tasks["assign_to"];
                    if($tasks["is_group"]){
                        $assignTo = "Group";
                        $query = "SELECT u.name As name FROM users u
                        JOIN user_group ug ON u.id = ug.user_id
                        WHERE ug.group_id = '$getId'";
                    }else{
                        $query = "SELECT name FROM `users` WHERE id='$getId'";
                    }
                    $result = mysqli_query($con, $query) or die();
                    $rows = mysqli_num_rows($result);
                    if($tasks["status"] == 1){
                        echo '<div class="project-head red">To-Do</div>';
                    }
                    elseif($tasks["status"] == 2){
                        echo '<div class="project-head orange">In-Process</div>';
                    }
                    else{
                        echo '<div class="project-head green">Done</div>';
                    }
                    echo '
                    <div class="view-task-name">
                        <span class="small">Task Name</span>
                        '.$tasks["name"].'
                    </div>
                    <div class="view-task-description">
                        <span class="small">Full Description</span>
                        '.$tasks["description"].'
                    </div>
                    <div class="view-task-name">
                        <span class="small">Assigned To ('.$assignTo.')</span>';
                    if($tasks["is_group"]){
                        if($rows){
                            while($userdata = mysqli_fetch_array($result)){
                            echo $userdata["name"].'<br>';
                            }
                        }
                    }else{
                        if($rows){
                            $userdata = mysqli_fetch_assoc($result);
                            echo $userdata["name"];
                        }
                    }
                        
                    echo '</div>';
                    if (!empty($tasks["doc"])) {
                        echo '<div class="view-task-attached">
                            <span class="small">Attached</span>
                            <div class="files"><a href="'.$tasks["doc"].'" target="_blank">Attachment</a></div>
                        </div>';
                    }
                echo '<div class="view-task-due-date">
                        <span class="small">Due Date</span>
                        '.date('d-M-Y', strtotime($tasks["due_date"])).'
                    </div>
                    <div class="view-task-due-date">
                        <a class="btn btn-blue" href="javascript:history.back()">Back</a>
                    </div>';
                }
                else{
                    $_SESSION["warning"] = "Task Not Found!";
                    if($_SESSION["role"]==3){
                        header("Location: employee-dashboard.php");
                        exit();
                    }
                    header("Location: project-task.php?id=".$projectId);
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