<?php
// Assuming you have included necessary configuration and database connection files
include 'auth_session.php';
require('config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = intval($_POST['projectId']);
    $order_by = $_POST['order_by'];

    $mangId = $_SESSION["userId"];
    $query    = "SELECT * FROM `projects` WHERE id='$projectId' AND is_active = 1 AND is_deleted = 0 And manager_id='$mangId' ";
    if($_SESSION["role"] == 1) {
        $query    = "SELECT * FROM `projects` WHERE id='$projectId' AND is_active = 1 AND is_deleted = 0";
    }
    $result = mysqli_query($con, $query) or die();
    $rows = mysqli_num_rows($result);
    if ($rows == 1) {
        $project = mysqli_fetch_assoc($result);
        $query = "SELECT
            t.id AS task_id,
            t.name AS task_name,
            t.description AS task_description,
            t.is_group AS is_task_group,
            t.assign_to AS task_assign_to,
            t.status AS status,
            u.id AS user_id,
            u.name AS user_name,
            u.email AS user_email,
            g.id AS group_id,
            g.name AS group_name,
            t.due_date As created_at
        FROM
            tasks t
        LEFT JOIN
            users u ON t.assign_to = u.id AND t.is_group = 0
        LEFT JOIN
            groups g ON t.assign_to = g.id AND t.is_group = 1
        WHERE
            t.project_id = '$projectId' AND t.is_active=1 AND t.is_deleted=0
        ORDER BY
            t.due_date";

        if($order_by == "created_at"){
            $query = "SELECT
            t.id AS task_id,
            t.name AS task_name,
            t.description AS task_description,
            t.is_group AS is_task_group,
            t.assign_to AS task_assign_to,
            t.status AS status,
            u.id AS user_id,
            u.name AS user_name,
            u.email AS user_email,
            g.id AS group_id,
            g.name AS group_name,
            t.due_date As created_at
        FROM
            tasks t
        LEFT JOIN
            users u ON t.assign_to = u.id AND t.is_group = 0
        LEFT JOIN
            groups g ON t.assign_to = g.id AND t.is_group = 1
        WHERE
            t.project_id = '$projectId' AND t.is_active=1 AND t.is_deleted=0
        ORDER BY
            t.created_at";
        }

        $result = mysqli_query($con, $query) or die(mysqli_error($con));
        $message = 'Some thing went wrong!';
        $rows = mysqli_num_rows($result);
        if ($rows > 0) {
            $message = '';
            $count = 1;
            while($row = mysqli_fetch_array($result)){
                $message .= '<tr>
                    <td>'.$count++.'</td><td>'.$row["task_name"].'</td><td>'.$row["task_description"].'</td>';
                    if($row["is_task_group"] == 1){
                        $message .= '<td>Group</td><td>'.$row["group_name"].'</td>';
                    }else{
                        $message .= '<td>Individual</td><td>'.$row["user_name"].'</td>';
                    }
                    if($row["status"] == 1){
                        $message .= '<td>To-Do</td>';
                    }elseif($row["status"] == 2){
                        $message .= '<td>In-Process</td>';
                    }else{
                        $message .= '<td>Done</td>';
                    }
                $message .= '<td>'.date('d-M-Y', strtotime($row["created_at"])).'</td><td><div class="table-button menu-dot"><ul><li><img src="images/dots.png"><ul><li><a href="task-details.php?id='.$row["task_id"].'&projectId='.$projectId.'">View Task</a></li>';
                                        if($_SESSION["role"] == 2){
                                            $message .= '<li><a href="delete-project-task.php?id='.$row["task_id"].'&projectId='.$projectId.'">Delete Tasks</a></li>';
                                        }
                                    $message .= '</ul></li></ul></div></td></tr>';
            }
        }else{
            $message = '<td style="text-align: center;" colspan="8">No Tasks Avilable on this project!</td>';
        }
        $response = ['status' => 'success', 'message' => $message];
    }else{
        $response = ['status' => 'error', 'message' => $message];
    }
    echo json_encode($response);
    exit();
}
?>