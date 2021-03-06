<?php
require_once 'assets/functions.php';
require_once 'php-files/account.php';
printHead("Manage Account | Buggy - Let's Code Together");
$firstName = getUserInfo($_SESSION['userID'], "firstName");
$lastName = getUserInfo($_SESSION['userID'], "lastName");
$email = getUserInfo($_SESSION['userID'], "email");
$continuePW = true;
$response = "";
if(isset($_POST['submit']) && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['firstName']) && !empty($_POST['firstName']) && isset($_POST['lastName']) && !empty($_POST['lastName'])) {

    if(isset($_POST['password']) && !empty($_POST['password'])) {
        $loginInfo = checkLogin($email, $_POST['password']);
        if(!$loginInfo) {
            $response = "You have entered in the wrong password for your current password.";
            $continuePW = false;
        }
    }
    if($continuePW == true) {
        if(updateAccount($_POST['newpassword'], $_POST['email'], $_POST['firstName'], $_POST['lastName'], $_SESSION['userID'])) {
            $firstName = getUserInfo($_SESSION['userID'], "firstName");
            $lastName = getUserInfo($_SESSION['userID'], "lastName");
            $email = getUserInfo($_SESSION['userID'], "email");
            $response = "You have successfully changed your user information.";
        }
        else $response = "Error changing your information.";
    }
}
?>

<body>
    <?php printSidebar(getAccountType($_SESSION['userID']), "account"); ?>

    <div class="main">
        <?php printHeader($_SESSION['userID']);
        echo "<p>" . $response . "</p>";
        ?>
        <h2 class='subhead'>Account Management</h2 class='subhead'>
            <div class="forms" id="acct-form">
                <h1>Edit Account Info</h1>
                <form action="" method="post" autocomplete="off">

                    <div class="field-row">
                        <div class="field-wrap">
                            <label>
                                First Name<span class="req">*</span>
                            </label>
                            <input type="text" required name="firstName" value="<?php echo $firstName; ?>" id="firstName"/>
                        </div>

                        <div class="field-wrap">
                            <label>
                                Last Name<span class="req">*</span>
                            </label>
                            <input type="text"required name="lastName" value="<?php echo $lastName; ?>" id="lastName"/>
                        </div>
                    </div>
                    <div class="field-wrap">
                        <label>
                            Email Address<span class="req">*</span>
                        </label>
                        <input type="email"required name="email" value="<?php echo $email; ?>" id="email"/>
                    </div>
                    <h3>Change Password</h3>
                    <div class="field-wrap">
                        <label>
                            Current Password<span class="req">*</span>
                        </label>
                        <input type="password" name="password" id="old-pw"/>
                    </div>
                    <div class="field-row">
                        <div class="field-wrap">
                            <label>
                                New Password<span class="req">*</span>
                            </label>
                            <input type="password" name="newpassword" id="new-pw1"/>
                        </div>
                        <div class="field-wrap">
                            <label>
                                Repeat New Password<span class="req">*</span>
                            </label>
                            <input type="password" name="newPassword2" id="new-pw2"/>
                        </div>
                    </div>
                    <input type="submit" class="button button-block" value="Save" name="submit"/>
                </form>
            </div>

    </div>
    <script src="/scripts/forms.js"></script>
    <script>
    $(document).ready(function(){
        $('#firstName').prev('label').addClass('active highlight');
        $('#lastName').prev('label').addClass('active highlight');
        $('#email').prev('label').addClass('active highlight');
    });
    $('#acct-form').submit(function(e){

        var pass1 = document.getElementById('new-pw1').value;
        var pass2 = document.getElementById('new-pw2').value;
        var oldpw = document.getElementById('old-pw').value;

        if(oldpw == "" && (pass1 != "" || pass2 != "")) {
            e.preventDefault();
            alert("You must enter your old password to change your password.");
        }
        else if(pass1 != "" || pass2 != "") {
            if(pass1 != pass2) {
                e.preventDefault();
                alert("New passwords do not match.");
            } else {
                var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/;
                if(!pass1.match(passw)) {
                    e.preventDefault();
                    alert("Your new password must be at least 8 characters long and contain at least one number, one uppercase, and one lowercase letter.");
                }
            }
        }

    });

    </script>
    <?php printFooter("basic"); ?>
</body>

</html>
