<?php
// Assuming you have included necessary configuration and database connection files
include 'auth_session.php';
require('config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST parameters
    $manager_id = intval($_POST['manager_id']);
    $group_name = $_POST['group_name'];
    $emp_ids = $_POST['emp_ids'];

    // Perform validation and other necessary checks

    // Insert group data into the database
    $insertGroupQuery = "INSERT INTO groups (manager_id, name) VALUES ('$manager_id', '$group_name')";
    $result = mysqli_query($con, $insertGroupQuery);

    if ($result) {
        // Get the ID of the newly inserted group
        $group_id = mysqli_insert_id($con);

        // Insert group members into the user_group table
        foreach ($emp_ids as $emp_id) {
            $emp_id = intval($emp_id);
            $insertUserGroupQuery = "INSERT INTO user_group (user_id, group_id) VALUES ('$emp_id', '$group_id')";
            mysqli_query($con, $insertUserGroupQuery);
        }

        // Return success response
        $response = ['status' => 'success', 'message' => 'Group created successfully'];
        echo json_encode($response);
    } else {
        // Return error response
        $response = ['status' => 'error', 'message' => 'Failed to create group'];
        echo json_encode($response);
    }
} else {
    // Return error for invalid request method
    $response = ['status' => 'error', 'message' => 'Invalid request method'];
    echo json_encode($response);
}
?>
