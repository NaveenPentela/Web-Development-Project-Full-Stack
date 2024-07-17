<?php
// Assuming you have included necessary configuration and database connection files
include 'auth_session.php';
if ($_SESSION["role"] != 3) {
	$_SESSION["warning"] = "Oops you had no rights";
	header("Location: index.php");
	exit();
}
require('config.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $employee_id = $_SESSION["userId"];
    if(isset($_GET['employee_id'])){
        $employee_id = $_GET['employee_id'];
    }

    // Assuming your tasks table has columns: id, name, description, assign_to, is_group, group_id, and other necessary fields
    $query = "SELECT t.*, u.name AS user_name, g.name AS group_name
            FROM tasks t
            LEFT JOIN users u ON t.assign_to = u.id AND t.is_group = 0
            LEFT JOIN user_group ug ON t.assign_to = ug.group_id AND t.is_group = 1
            LEFT JOIN groups g ON ug.group_id = g.id AND t.is_group = 1
            WHERE (t.is_group = 0 AND t.assign_to = '$employee_id')
        OR (t.is_group = 1 AND ug.user_id = '$employee_id')";

    $result = mysqli_query($con, $query);

    if ($result) {
        $tasks = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $tasks[] = $row;
        }

        // Return tasks in JSON format
        header('Content-Type: application/json');
        echo json_encode($tasks);
        exit();
    } else {
        // Handle the case where the query fails
        $response = ['status' => 'error', 'message' => 'Failed to fetch tasks'];
        echo json_encode($response);
        exit();
    }
} else {
    // Return error for invalid request
    $response = ['status' => 'error', 'message' => 'Invalid request'];
    echo json_encode($response);
    exit();
}
?>
