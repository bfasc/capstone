<?php
    REQUIRE_ONCE 'assets/functions.php';
    REQUIRE_ONCE 'php-files/verify.php';
    printHead("Verify Your Account | Buggy - Let's Code Together");

    $response = "";
    if(isset($_GET['code']) && !empty($_GET['code']) && isset($_GET['email']) && !empty($_GET['email'])) {
        if(checkCode($_GET['code'], $_GET['email'])) {
            if(!checkVerified($_GET['email'])) {

                verifyAccount($_GET['email']);

                //check if management account needs purchase
                $userID = getUserID($_GET['email']);
                if(getAccountType($userID) == "management") { //management account
                    if(checkPurchased($userID)) {
                        $response = "You're all set! <a href='signin'>Sign In here</a>!";
                    } else {
                        $response = "Thank you for signing up! The next step is to purchase the Buggy plan for your company. You can do that <a href='purchase'>here</a>!";
                    }
                } else { //developer account
                    $response = "Thank you for verifying your email! <a href='signin'>Sign In here</a>!";
                }
            } else {
                $response = "You have already verified your email. <a href='signin'>Sign In here</a>!";
            }
        } else {
            $response = "Broken link! Please click the link in your email.";
        }
    } else {
        $response = "Broken link! Please click the link in your email.";
    }
?>
<body>
    <?php printSidebar("notloggedin", ""); ?>
    <div class="main">
        <h1>Verify your Buggy Account</h1>
        <section>
            <h2> <?php print($response); ?> </h2>
            <h3>Don't have an account?</h3>
            <a href="purchase" class="button">Get Buggy for your team</a>
            <h3>Does your team already have buggy?</h3>
            <a href="signup#developer" class="button">Sign Up with a Developer account</a>
        </section>
    </div>
    <?php printFooter("basic"); ?>

</body>
</html>
