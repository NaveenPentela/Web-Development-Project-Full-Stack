<?php
if (!isset($_SESSION)) {
	session_start();
}
if(isset($_SESSION["email"])){
	$_SESSION["error"] = "Page Not Found!";
	header("Location: index.php");
	exit();
}
$pageTitle = "Forget Password";
include 'includes/header.php';
?>

<div class="cr-vr-task-bc wd100">
	<div class="wrapper">
		<div class="cr-vr-task wd100">
			<div class="left-area">
				<img src="images/login-image.png">
			</div>
			<div class="right-area l-space">
				<h1>Forget<br><span class="black">Password</span></h1>
				<div class="left-side manager-side active">
					<input type="Email" name="email" id="email" placeholder="Email address" required>
					<button type="button" style="width: auto;" class="btn normal-btn mt-2 form-submit">Submit</button>
				</div>

			</div>

		</div>
	</div>
</div>

<?php
include 'includes/footer.php';
?>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		var formSubmitButton = document.querySelector('.form-submit');

		formSubmitButton.addEventListener('click', function() {
			var ele = this;
			var email = document.getElementById('email').value;

			if (email && email !== '') {
				ele.disabled = true;
				ele.innerHTML = 'Loading...';

				// Using the Fetch API
				fetch('forgot-pass-email.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: new URLSearchParams({
						'email': email,
					}),
				})
				.then(response => response.json())
				.then(data => {
					if (data.status === 1) {
                		showPopup('success', data.message);
						ele.innerHTML = 'Submit';
						setTimeout(function() {
							window.location.replace('index.php');
						}, 2000);
					} else {
                		showPopup('error', data.message);
						ele.disabled = false;
						ele.innerHTML = 'Submit';
					}
				})
				.catch(error => {
                	showPopup('error', 'Error:', error);
					ele.disabled = false;
					ele.innerHTML = 'Submit';
				});
			} else {
                showPopup('error', "Enter email!");
			}
		});
	});

</script>
</body>

</html>