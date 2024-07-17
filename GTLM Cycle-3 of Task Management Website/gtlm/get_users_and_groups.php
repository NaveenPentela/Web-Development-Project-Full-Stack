<?php
// Include your authentication and database connection files
include 'auth_session.php';
require('config.php');

// Check if the request has the 'type' parameter
if (isset($_GET['type'])) {
    $type = $_GET['type'];
    $manager_id = $_GET['manager_id'];

    if ($type === 'individual') {
        // Get a list of individual users
        $query = "SELECT id,name,email FROM `users` WHERE role_id = 3 AND is_active = 1 AND is_deleted = 0";
        $result = mysqli_query($con, $query) or die();
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Return the result as JSON
        header('Content-Type: application/json');
        echo json_encode($users);
        exit();
    } elseif ($type === 'group') {
        // Get a list of groups
        $query = "SELECT * FROM `groups` WHERE manager_id='$manager_id' AND is_active = 1 AND is_deleted = 0";
        $result = mysqli_query($con, $query) or die();
        $groups = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Return the result as JSON
        header('Content-Type: application/json');
        echo json_encode($groups);
        exit();
    }
}

// If 'type' parameter is not provided or invalid, return an error
echo json_encode(['error' => 'Invalid request.']);
?>
