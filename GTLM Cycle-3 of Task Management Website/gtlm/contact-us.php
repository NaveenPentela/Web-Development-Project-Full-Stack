<?php

require('config.php');
if (!isset($_SESSION)) {
	session_start();
}
// Check if the form is submitted
if (isset($_POST['email'])) {
	
    $email = stripslashes($_REQUEST['email']);
    $subject = stripslashes($_REQUEST['subject']);
    $name = stripslashes($_REQUEST['name']);
    $message = stripslashes($_REQUEST['message']);

	$query    = "INSERT into `contact_inquiry` (name, email, subject, message)
			VALUES ('$name', '$email', '$subject', '$message')";
	$result   = mysqli_query($con, $query);
	if ($result) {
		$_SESSION['success'] = 'Thanks For your Feedback.';
	}
	else{
		$_SESSION['error'] = 'Error: Something went wrong.';
	}

    // Redirect to the current page to avoid form resubmission on page refresh
    header("Location: contact-us.php");
    exit();
}


$pageTitle = "Contact Us";
include 'includes/header.php';
?>
	
	<div class="banner-inner-bc">
		<div class="wrapper">
			<div class="banner-inner wd100">
				<h1>Contact <span class="black">Us</span></h1>
				<p>"Reach Out, We're Just a Click Away!"</p>
			</div>
		</div>
	</div>

	<div class="wrapper">
		<div class="contact-us wd100">
			<div class="left-contact">
				<h2>Send us a message</h2>
				<p>Our committed team is eager to help with all your requirements. Fill out the contact form below, and we'll respond promptly. </p>
				<form method="post">
					<input type="text" name="name" placeholder="Name" required>
					<input type="email" name="email" placeholder="Your Email" required>
					<input type="text" name="subject" placeholder="Subject" required>
					<textarea name="message" placeholder="Your Message" required></textarea>
					<input class="btn" type="submit" value="Submit">
				</form>
			</div>
			<div class="right-contact">
				<h2>Get in touch</h2>
				<p><strong>Address:</strong><br/>123 Kangaroo Lane<br/>Park Plaza <br/>NSW 2000, Australia</p>
				<p><strong>Email:</strong> admin@admin.com</p>
				<p><strong>Phone:</strong> 04 1234 5678</p>
			</div>
			
		</div>
	</div>
	
	<div class="cta-bc wd100">
		<div class="wrapper">
			<div class="cta wd100">
				<h2>Deliver your best work with us.</h2>
				<p><a class="btn" href="login.php">Get Started</a></p>
			</div>
		</div>
	</div>
	
	<?php
	include 'includes/footer.php';
	?>

</body>

</html>