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
    $userData = mysqli_fetch_assoc($result);
    $is_active = 1;
    if($userData["is_active"] == 1){
        $is_active = 0;
    }
    $update="UPDATE users SET is_active='.$is_active.' WHERE id='$userId'";
    $result = mysqli_query($con, $update);

    if ($result) {
        // Project creation successful
        $_SESSION['success'] = "User Active/Deactivate successfully!";
    } else {
        // Project creation failed
        $_SESSION["error"] = "Failed to Active/Deactivate User!";
    }
}
else{
    $_SESSION["warning"] = "User Not Found!";
}

header("Location: user-management.php");
exit();

?>