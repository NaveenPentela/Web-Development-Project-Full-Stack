<?php
include 'auth_session.php';
if ($_SESSION["role"] == 3) {
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
    $project = mysqli_fetch_assoc($result);
    $update="UPDATE projects SET status=2 WHERE id='$projectId'";
    $result = mysqli_query($con, $update);

    if ($result) {
        // Project creation successful
        $message = "Project ".$project["name"]." has been completed.";
        $query = "INSERT INTO notification (user_id,description) VALUES (1,'$message')";
        $result = mysqli_query($con, $query);
        $_SESSION['success'] = "Project Completed successfully!";
    } else {
        // Project creation failed
        $_SESSION["error"] = "Something went wrong!";
    }
}
else{
    $_SESSION["warning"] = "Project Not Found!";
}

header("Location: project.php");
exit();

?>