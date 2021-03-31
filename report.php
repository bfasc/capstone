<?php
//TODO: ADD JS VALIDATION
require_once 'assets/functions.php';
require_once 'php-files/report.php';
// Databse connection
require_once 'assets/dbconnect.php';


// GET COMPANY INFO FROM GET VAR
$response = NULL;
$responseDetails = NULL;
$projectName = "";



if(isset($_GET['project']) && !empty($_GET['project'])) {
    $projectInfo = getProjectFromReport($_GET['project']);
    $projectName = $projectInfo['projectName'];
} else {
    $response = "Broken Link! Please contact the developer directly.";
}


//PROCESS FORM DATA

if (isset($_POST['email']) && !empty($_POST['email'])) {
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $projectID = $projectInfo['id'];

        if (addBugReport($_POST['firstName'], $_POST['lastName'], $projectID, $_POST['email'], $_POST['details'])) {
            //send email to reporter
            $subject = "Your recent bug submission for $projectName";
            $content = "Thank you for your bug submission. You reported: " . $_POST['details'] . ". We have sent it
                to the developers, and you will be notified when there is an update.";
            sendEmail($subject, $_POST['email'], "noreply@projectbuggy.tk", $content);

            $response = "Thank you for submitting your bug.";
            $responseDetails = "A confirmation email has been sent to the email you
                provided. We have also forwarded this information to the lead developer at
                $projectName. A representative from their company was given your
                email address to inform you of the ticket progress.";
        } else {
            $response = "There was an error submitting your bug report into our database.";
        }
    } else $response = "Please check the box that says \"I am not a robot\" before continuing.";

}

printHead("Report a bug for $projectName | Buggy - Let's Code Together");
?>

<body>
    <?php printSidebar("report", NULL); ?>
    <div class="main">
        <section id="report">
            <?php
            //IF ERROR FETCHING PROJECT INFO
            if ($response != NULL) {
                print("<h2>$response</h2>");
                if ($responseDetails != NULL)
                    print("<p>$responseDetails</p>");
            } else {
            ?>
                <div class="forms">
                    <h1>Report A Bug</h1>
                    <p>Report a bug for <?php echo $projectName; ?> </p>
                    <div id="signin">
                        <!-- RE-CAPTCHA SCRIPT -->
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                        <form id="signup" method="post" action="" autocomplete="off">
                            <div class="tab-content">
                                <div class="field-row">
                                    <div class="field-wrap">
                                        <label>
                                            First Name<span class="req">*</span>
                                        </label>
                                        <input type="text" name="firstName" required>
                                    </div>
                                    <div class="field-wrap">
                                        <label>
                                            Last Name<span class="req">*</span>
                                        </label>
                                        <input type="text" name="lastName" required>
                                    </div>
                                </div>
                                <div class="field-wrap">
                                    <label>
                                        Email<span class="req">*</span>
                                    </label>
                                    <input type="email" name="email" required>
                                </div>
                                <div class="field-wrap">
                                    <label>
                                        Description of Bug<span class="req">*</span>
                                    </label>
                                    <textarea name="details" required></textarea>
                                </div>
                                <div class="g-recaptcha" data-sitekey="6Ldf55YaAAAAAEjTyFkESkJszbQJcf09Yik0Je6-"></div>
                                <input type="submit" class="button button-block" value="Submit Bug">
                            </div>
                        </form>
                    </div>
                </div>
                </form>
        </section>
    <?php } //END ELSE STMT FOR ERROR FETCHING COMPANY INFO
    ?>

    </div>
    <script src="scripts/forms.js"></script>
    <?php printFooter("report"); ?>
</body>

</html>
