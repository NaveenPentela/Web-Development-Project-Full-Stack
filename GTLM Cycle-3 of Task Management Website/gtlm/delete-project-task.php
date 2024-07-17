<?php
include 'auth_session.php';
if ($_SESSION["role"] != 2) {
    $_SESSION["warning"] = "Oops you had no rights";
    header("Location: index.php");
    exit();
}
require('config.php');
$taskId = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$taskId) {
    header("Location: project.php");
    exit();
}
$projectId = isset($_GET['projectId']) ? intval($_GET['projectId']) : null;
if (!$projectId) {
    header("Location: project.php");
    exit();
}
$userId = $_SESSION["userId"];
$query    = "SELECT * FROM `projects` WHERE id='$projectId' AND is_active = 1 AND is_deleted = 0 AND manager_id='$userId'";
$result = mysqli_query($con, $query) or die();
$rows = mysqli_num_rows($result);

if ($rows == 1) {
    $update="DELETE FROM tasks WHERE id='$taskId' AND project_id='$projectId'";
    $result = mysqli_query($con, $update);
    
    $_SESSION['success'] = "Project Deleted successfully!";
}
else{
    $_SESSION["warning"] = "Task Not Found!";
}

header("Location: project-task.php?id=".$projectId);
exit();

?>