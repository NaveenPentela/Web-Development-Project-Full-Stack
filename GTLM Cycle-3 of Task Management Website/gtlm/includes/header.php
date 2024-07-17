<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | Task Manager' : 'Index | Task Manager'; ?></title>
    <link rel="stylesheet" href="style/style.css">
	<link rel="stylesheet" href="style/fonts.css">
    <link rel="stylesheet" href="style/custom.css">
	<script src="js/script.js"></script>
</head>

<body>

	<div class="header-bc">
		<div class="wrapper">
			<div class="header">
				<div class="head-left">
					<a href="index.php"><img src="images/logo.png"></a>
				</div>
				<div class="head-right">
					<ul>
						<li><a href="about.php"><img src="images/info-icon.png">About</a></li>
						<li><a href="contact-us.php"><img src="images/contact-icon.png"> Contact Us</a></li>
                        <?php
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            if (isset($_SESSION['email'])) {
                                echo '<li><a href="profile.php"><img src="images/user-icon.png"> Profile</a>
                                            <ul>
                                                <li><a href="notification.php">Notification</a></li>';
                                            if($_SESSION['role'] == 1){
                                                echo '<li><a href="contact-inquiry.php">Contact Inquiry</a></li>';
                                            }
                                            echo '<li><a href="profile-password.php">Password</a></li>
                                                <li><a href="logout.php">Logout</a></li>
                                            </ul>
                                        </li>';
                                $currentURL = $_SERVER['REQUEST_URI'];
                                if($_SESSION['role'] != 3 && strpos($currentURL, 'admin-dashboard') === false && strpos($currentURL, 'create-view') === false){
                                    echo '<li><a class="btn" href="project.php">Project</a></li>';
                                }
                                elseif($_SESSION['role'] == 3 && strpos($currentURL, 'employee-dashboard') === false){
                                    echo '<li><a class="btn" href="employee-dashboard.php">Dashboard</a></li>';
                                }if($_SESSION['role'] == 1 && strpos($currentURL, 'admin-dashboard') === false){
                                    echo '<li><a class="btn btn-white" href="user-management.php">User Management</a></li>';
                                }
                            } else {
                                echo '<li><a href="login.php"><img src="images/user-icon.png"> Login</a></li>';
                            }
                        ?>
					</ul>
				</div>
			</div>
		</div>
	</div>