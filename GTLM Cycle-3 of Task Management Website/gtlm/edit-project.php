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
    header("Location: index.php");
    exit();
}
$manager_id = 0;
$pageTitle = "Edit Project";
include 'includes/header.php';

if (isset($_POST['name'])) {
    $name = stripslashes($_REQUEST['name']);
    $description = stripslashes($_REQUEST['description']);
    $manager_id = stripslashes($_REQUEST['manager_id']);
    $due_date = stripslashes($_REQUEST['due_date']);
    $docPath = "";
    if (isset($_FILES['doc']) && $_FILES['doc']['error'] === UPLOAD_ERR_OK) {
        // File was uploaded successfully
        $originalFileName = pathinfo($_FILES['doc']['name'], PATHINFO_FILENAME); // Extracting the filename without extension
        $extension = pathinfo($_FILES['doc']['name'], PATHINFO_EXTENSION); // Extracting the file extension

        // Adding a timestamp to the filename
        $timestamp = time();
        $newFileName = $originalFileName . '_' . $timestamp . '.' . $extension;

        // Constructing the full file path
        $docPath = 'uploads/files/' . $newFileName;

        // Yo
        move_uploaded_file($_FILES['doc']['tmp_name'], $docPath);
    }

    if ($docPath) {
        $update = "UPDATE projects SET name='$name', description='$description',manager_id='$manager_id', due_date='$due_date', doc='$docPath' WHERE id='$projectId'";
    } else {
        $update = "UPDATE projects SET name='$name', description='$description',manager_id='$manager_id', due_date='$due_date' WHERE id='$projectId'";
    }
    $result = mysqli_query($con, $update);

    if ($result) {
        // Project creation successful
        $message = "Project (".$name.") Details has been updated.";
		$query = "INSERT INTO notification (user_id,description) VALUES ('$manager_id','$message')";
		$result = mysqli_query($con, $query);
        $_SESSION['success'] = "Project Updated successfully!";
    } else {
        // Project creation failed
        $_SESSION["error"] = "Failed to Update the project. Please try again.";
    }
    header("Location: project.php");
    exit();
}

?>

<div class="wrapper-small">
    <form class="validate-form" method="post" enctype="multipart/form-data">
        <div class="container wd100 step-1">
            <h2>Update Project</h2>
            <?php
            $query    = "SELECT * FROM `projects` WHERE id='$projectId' AND is_active = 1 AND is_deleted = 0 AND status=1";
            $result = mysqli_query($con, $query) or die();
            $rows = mysqli_num_rows($result);
            if ($rows == 1) {
                $project = mysqli_fetch_assoc($result);
                $manager_id = $project["manager_id"];
                echo '<input type="text" name="name" value="' . $project["name"] . '" placeholder="Task Name">
                <textarea placeholder="Full Description" name="description">' . $project["description"] . '</textarea>
                <div>
                    <p><strong>Please Upload your attachments</strong></p>
                    <input class="btn btn-grey notInput" type="file" name="doc" > 
                </div>
                <input type="date" name="due_date" min="' . date("Y-m-d") . '" value="' . $project["due_date"] . '" placeholder="Due Date">';
            } else {
                $_SESSION["warning"] = "Project Not Found!";
                header("Location: project.php");
                exit();
            }
            ?>
            <input class="btn btn-blue normal-btn next-step notInput" style="width: auto;" data-current-step="step-1" data-next-step="step-2" type="button" value="Assign to"></input>
        </div>
        <div class="container wd100 step-2 d-none">
            <div class="side-area-add wd100">
                <h2>Assign to</h2>
                <!-- <div class="side-title-menu ind-grou"><span><a class="active" href="project-assign-to.php">Individual</a></span><span><a href="project-assign-to-group.php">Group</a></span></div> -->
            </div>
            <div class="assign-to-page">
                <input type="text" id="searchInput" class="notInput" placeholder="Search by Name">
                <?php
                $query    = "SELECT * FROM `users` WHERE role_id=2";
                $result = mysqli_query($con, $query) or die();
                $rows = mysqli_num_rows($result);
                if ($rows > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo '<label class="contain user-label" data-name="' . $row["name"] . '">
						<input type="checkbox" class="select-one notInput" name="manager_id" data-id="' . $row["id"] . '"" value="' . $row["id"] . '"">
						<span class="checkmark"></span>' . $row["name"] . '
						</label>';
                    }
                }
                ?>
            </div>
            <input type="button" style="width: auto;background: #9ca3e1" data-prev-step="step-1" data-current-step="step-2" class="btn btn-blue normal-btn prev-step notInput" value="back"></input>
            <input type="submit" class="btn btn-blue normal-btn notInput" value="Submit"></input>
        </div>
    </form>
</div>

<?php
include 'includes/footer.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var mng_id = <?php echo json_encode($manager_id); ?>;

        document.querySelectorAll('.select-one').forEach(function(element) {
            if (mng_id == element.dataset.id) {
                element.click();
            }
        });

        document.querySelectorAll('.next-step').forEach(function(element) {
            element.addEventListener('click', function() {
                hideCurrentAndShowNext(element);
            });
        });

        document.querySelectorAll('.prev-step').forEach(function(element) {
            element.addEventListener('click', function() {
                hideCurrentAndShowPrev(element);
            });
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            var searchValue = this.value.toLowerCase();

            document.querySelectorAll('.user-label').forEach(function(label) {
                var userName = label.dataset.name.toLowerCase();

                if (userName.includes(searchValue)) {
                    label.style.display = 'block';
                } else {
                    label.style.display = 'none';
                }
            });
        });

        function hideCurrentAndShowNext(element) {
            var currentStep = element.dataset.currentStep;
            var nextStep = element.dataset.nextStep;

            document.querySelector('.' + currentStep).classList.add('d-none');
            document.querySelector('.' + nextStep).classList.remove('d-none');
        }

        function hideCurrentAndShowPrev(element) {
            var currentStep = element.dataset.currentStep;
            var prevStep = element.dataset.prevStep;

            document.querySelector('.' + currentStep).classList.add('d-none');
            document.querySelector('.' + prevStep).classList.remove('d-none');
        }
    });
</script>

</body>

</html>