<?php
include 'auth_session.php';
if ($_SESSION["role"] == 3) {
    $_SESSION["warning"] = "Oops you had no rights";
    header("Location: index.php");
    exit();
}
require('config.php');
$userId = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$userId) {
    header("Location: user-management.php");
    exit();
}
$query    = "SELECT * FROM `users` WHERE id='$userId' AND is_deleted = 0";
$result = mysqli_query($con, $query) or die();
$rows = mysqli_num_rows($result);
if ($rows == 1) {
    
    $update="UPDATE users SET is_active=0,is_deleted=1 WHERE id='$userId'";
    $result = mysqli_query($con, $update);

    if ($result) {
        // Project creation successful
        $_SESSION['success'] = "User Deleted successfully!";
    } else {
        // Project creation failed
        $_SESSION["error"] = "Failed to Delete User!";
    }
}
else{
    $_SESSION["warning"] = "User Not Found!";
}

header("Location: user-management.php");
exit();

?>