<?php
include "functions/functions.php";

if( isset( $_REQUEST['signin-btn']) ) {
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    if( signin( $email, $password ) ) {
        header('location:home.php');
        exit;
    } else {

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
<!--            <a href="#" class="forgot">Forgot your email or password?</a>-->
        </form>
    </div>
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>-->



<!--    <form action="signin.php" method="post">-->
<!--        <table>-->
<!--            <tr>-->
<!--                <td>Email</td>-->
<!--                <td><input type="email" name="email" placeholder="Enter your email address" required></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>Password</td>-->
<!--                <td><input type="password" name="password" placeholder="Enter your password" required></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>-->
<!--                    <input type="submit" name="signin-btn" value="SIGN IN">-->
<!--                </td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </form>-->


<?php
include "footer.php";