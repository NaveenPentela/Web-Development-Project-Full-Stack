<?php
include 'auth_session.php';
if ($_SESSION["role"] != 1) {
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
$query    = "SELECT * FROM `projects` WHERE id='$projectId' AND is_active = 1 AND is_deleted = 0 AND status=1";
$result = mysqli_query($con, $query) or die();
$rows = mysqli_num_rows($result);
if ($rows == 1) {
    $update="UPDATE projects SET is_active=0, is_deleted=1 WHERE id='$projectId'";
    $result = mysqli_query($con, $update);

    if ($result) {
        // Project creation successful
        $_SESSION['success'] = "Project Deleted successfully!";
    } else {
        // Project creation failed
        $_SESSION["error"] = "Failed to Delete Project!";
    }
}
else{
    $_SESSION["warning"] = "Project Not Found!";
}

header("Location: project.php");
exit();

?>