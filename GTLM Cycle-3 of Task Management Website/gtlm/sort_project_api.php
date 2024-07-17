<?php
// Assuming you have included necessary configuration and database connection files
include 'auth_session.php';
require('config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_by = $_POST['order_by'];

    $query    = "SELECT
			projects.*,
			users.name AS manager_name,
			users.email AS manager_email,
			users.phone AS manager_phone
		FROM
			projects
		JOIN
			users ON projects.manager_id = users.id
		WHERE
			projects.is_active = 1
			AND projects.is_deleted = 0
		ORDER BY
			projects.due_date";
    if($order_by == "created_at"){
        $query    = "SELECT
			projects.*,
			users.name AS manager_name,
			users.email AS manager_email,
			users.phone AS manager_phone
		FROM
			projects
		JOIN
			users ON projects.manager_id = users.id
		WHERE
			projects.is_active = 1
			AND projects.is_deleted = 0
		ORDER BY
			projects.created_at";
    }
	if($_SESSION["role"] == 2){
		$mId = $_SESSION["userId"];
		$query    = "SELECT
			projects.*,
			users.name AS manager_name,
			users.email AS manager_email,
			users.phone AS manager_phone
		FROM
			projects
		JOIN
			users ON projects.manager_id = users.id
		WHERE
			projects.is_active = 1
			AND projects.is_deleted = 0 And manager_id ='$mId' 
		ORDER BY
			projects.due_date";
        if($order_by == "created_at"){
            $query    = "SELECT
			projects.*,
			users.name AS manager_name,
			users.email AS manager_email,
			users.phone AS manager_phone
		FROM
			projects
		JOIN
			users ON projects.manager_id = users.id
		WHERE
			projects.is_active = 1
			AND projects.is_deleted = 0 And manager_id ='$mId' 
		ORDER BY
			projects.created_at";
        }
	}
	$result = mysqli_query($con, $query) or die();
	$rows = mysqli_num_rows($result);
    $message = 'Some thing went wrong!';
    if ($rows > 0) {
        $message = '';
        $count = 1;
        if ($rows > 0) {
            $count = 1;
            while($row = mysqli_fetch_array($result)){
                $message.= '<tr>
                    <td>'.$count++.'</td>
                    <td>'.$row["name"].'</td>
                    <td>'.$row["description"].'</td>';
                    if($_SESSION["role"] == 1){
                    $message.= '<td>'.$row["manager_name"].'</td>';
                    }
                    if($row["status"] == 1){
                        $message.= '<td><div class="project-head orange status-datatable">in process</div></td>';
                    }else{
                        $message.= '<td><div class="project-head green status-datatable">Completed</div></td>';
                    }
                $message.= '<td>'.date('d-M-Y', strtotime($row["created_at"])).'</td>
                    <td>'.date('d-M-Y', strtotime($row["due_date"])).'</td>
                    <td><div class="table-button menu-dot">
                            <ul>
                                <li><img src="images/dots.png">
                                    <ul>
                                        <li><a href="project-detail.php?id='.$row['id'].'">View Project</a></li>
                                        <li><a href="project-task.php?id='.$row['id'].'">Create/View Tasks</a></li>';
                                        if($_SESSION["role"] == 1 && $row["status"] == 1){
                                            $message.= '
                                            <li><a href="edit-project.php?id='.$row['id'].'">Edit Project</a></li>
                                            <li><a href="delete-project.php?id='.$row['id'].'">Delete Project</a></li>';
                                        }
                                        if($_SESSION["role"] == 2 && $row["status"] == 1){
                                            $message.= '<li><a href="complete-project.php?id='.$row['id'].'">Complete Project</a></li>';
                                        }
                                $message.= '</ul>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>';
            }
        }else{
            $message.= '<td style="text-align: center;" colspan="7">No Tasks Avilable on this project!</td>';
        }
        $response = ['status' => 'success', 'message' => $message];
    }else{
        $response = ['status' => 'error', 'message' => $message];
    }

    echo json_encode($response);
    exit();
}
?>