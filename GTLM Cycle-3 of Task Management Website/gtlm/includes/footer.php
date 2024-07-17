<div class="footer-bc wd100">
    <div class="wrapper">
        <div class="footer wd100">
            <p>Copyright © 2023 TM. All rights reserved. Terms & Condition | Privacy Policy</p>
        </div>
    </div>
</div>

<div class="popup" id="popup">
    <div>
        <div class="head-popup">
            <span id="popupIcon"></span>
            <h2 id="popupTitle"></h2>
        </div>
        <div class="content-popup">
            <p id="popupMessage"></p>
            <a class="btn" onclick="hidePopup()" id="popupButton">OK</a>
        </div>
    </div>
</div>


<script>
    // Function to show the custom popup
    function hidePopup() {
        var popup = document.getElementById('popup');
        popup.style.display = 'none';
    }

    function showPopup(type, message) {
        var popup = document.getElementById('popup');
        var popupIcon = document.getElementById('popupIcon');
        var popupTitle = document.getElementById('popupTitle');
        var popupMessage = document.getElementById('popupMessage');
        var popupButton = document.getElementById('popupButton');

        // Set popup class based on type (success, error, warning, info)
        popup.className = 'popup ' + type;

        // Set popup icon based on type
        if (type === 'success') {
            popupIcon.innerHTML = '<img src="images/tick-icon.png">';
        } else if (type === 'error') {
            popupIcon.innerHTML = '<img src="images/cross.png">';
        } else if (type === 'warning') {
            popupIcon.innerHTML = '<img src="images/warning-icon.png">';
        } else {
            // Default icon for other types
            popupIcon.innerHTML = '';
        }

        // Set popup title based on type
        popupTitle.textContent = type.charAt(0).toUpperCase() + type.slice(1);

        // Set popup message
        popupMessage.textContent = message;

        // Set button click event
        popupButton.addEventListener('click', function() {
            popup.style.display = 'none';
        });

        // Display the popup
        popup.style.display = 'block';
    }
    document.addEventListener('DOMContentLoaded', function() {


        // Check if session variables are set and show the corresponding popup
        <?php if (isset($_SESSION['success'])) : ?>
            showPopup('success', "<?php echo $_SESSION['success'] ?>");
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])) : ?>
            showPopup('error', "<?php echo $_SESSION['error'] ?>");
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['warning'])) : ?>
            showPopup('warning', "<?php echo $_SESSION['warning'] ?>");
            <?php unset($_SESSION['warning']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['info'])) : ?>
            showPopup('info', "<?php echo $_SESSION['info'] ?>");
            <?php unset($_SESSION['info']); ?>
        <?php endif; ?>
    });


    document.addEventListener('DOMContentLoaded', function() {
        function validateForm() {
            var isValid = true;

            if (document.querySelectorAll('input[type="checkbox"]').length) {
                var currentUrl = window.location.href;
                var selectedCheckboxes = document.querySelectorAll('input[name="manager_id"]:checked');
                if (currentUrl.includes("task")) {
                    if(document.querySelectorAll('input[name="emp_id"]').length){
                        selectedCheckboxes = document.querySelectorAll('input[name="emp_id"]:checked');
                    }else{
                        selectedCheckboxes = document.querySelectorAll('input[name="group_id"]:checked');
                    }
                }
                console.log("length",selectedCheckboxes.length,document.querySelectorAll('input[type="emp_id"]').length);
                if (!selectedCheckboxes.length) {
                    if(currentUrl.includes("task")){
                        showPopup('error', "Select Group/Individual User.");
                    }
                    else{
                        showPopup('error', "Select Manager.");
                    }
                    return false;
                }
            }

            document.querySelectorAll('.validate-form input, .validate-form textarea').forEach(function(input) {
                if (input.value.trim() === '' && !input.classList.contains("notInput")) {
                    isValid = false;
                    return;
                }
            });

            if (!isValid) {
                showPopup('error', "Please fill all required fields.");
            }

            return isValid;
        }

        var formSubmitButton = document.querySelector('.validate-form');
        if(formSubmitButton){
            document.querySelector('.validate-form').addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                }
            });
        }

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('select-one')) {
                var ele = event.target;
                var assignToPage = ele.closest('.assign-to-page');

                assignToPage.querySelectorAll('.select-one').forEach(function(checkbox) {
                    if (!checkbox.isSameNode(ele)) {
                        checkbox.checked = false;
                    }
                });
            }
        });
    });
</script>