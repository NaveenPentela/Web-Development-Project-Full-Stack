<?php
include 'auth_session.php';
if ($_SESSION["role"] != 3) {
	$_SESSION["warning"] = "Oops you had no rights";
	header("Location: index.php");
	exit();
}
require('config.php');
// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $task_id = isset($_POST['task_id']) ? $_POST['task_id'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;

    // Validate inputs (add more validation as needed)
    if ($task_id !== null && is_numeric($task_id) && $status !== null && is_numeric($status)) {
        // Perform the task update in the database (replace this with your database logic)
        $update = "UPDATE tasks SET status = $status WHERE id = $task_id";
        $result = mysqli_query($con, $update) or die();

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Task updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update task']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input parameters']);
    }
} else {
    // Send a JSON response for non-POST requests
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
exit();
?>
