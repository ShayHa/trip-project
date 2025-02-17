<?php
include "functions/functions.php";
$flag = false;
if( isset( $_REQUEST['signin-btn']) ) {
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    if( signin( $email, $password ) ) {
        header('location:home.php');
        exit;
    } else {
        $flag = true;
    }
}
include "header.php";
?>

    <link rel="stylesheet" href="style/sign_in_style.css">

    <div class="login-clean">
        <form action="signin.php" method="post">
            <h2 class="sr-only">Login Form</h2>
<!--             <div class="illustration"><i class="icon ion-ios-navigate"></i></div>-->
            <div class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit" name="signin-btn" value="SIGN IN">Sign In</button>
            </div>
            <div style="padding-left: 15%;">
                <?php
                if ($flag) { ?>
                    <strong style="text-align: center; color: #dd2222; font-size: 15px; padding-right: 10%;">
                        Sign in failed.
                        <br><span style="">Please use valid Email and Password</span>
                    </strong>
                    <?php
                } else { ?>

                <?php }
                ?>
            </div>
<!--            <a href="#" class="forgot">Forgot your email or password?</a>-->
        </form>
    </div>

<?php
include "footer.php";