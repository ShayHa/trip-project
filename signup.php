<?php
include "functions/functions.php";
$is_signup = false;
$flag = false;

if (isset($_REQUEST['signup-btn'])) {

    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $first_name = $_REQUEST['first_name'];
    $last_name = $_REQUEST['last_name'];

    if (checkIfExsits($email)){
        $flag = true;
    }
    else{
        signup($email, $password, $first_name, $last_name);
        $is_signup = true;
    };

}
include "header.php";
?>
    <link rel="stylesheet" href="style/sign_in_style.css">
<div class="login-clean">
    <form action="signup.php" method="post">
        <h2 class="sr-only">Sign up Form</h2>
        <!--             <div class="illustration"><i class="icon ion-ios-navigate"></i></div>-->
        <strong style="padding-left: 70px;"> Register: </strong>
        <div class="form-group">
            <input class="form-control" type="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <input class="form-control" type="text" name="first_name" placeholder="First name" required>
        </div>
        <div class="form-group">
            <input class="form-control" type="text" name="last_name" placeholder="Last name" required>
        </div>
        <div class="form-group">
            <button class="btn btn-primary btn-block" type="submit" name="signup-btn" value="SIGN UP">Sign UP</button>
        </div>
        <div class="form-group" style="padding-left: 15%;">
            <?php
            if ($is_signup == true) { ?>
                <strong style="text-align: center">
                    Sign up successfully.
                    <br><span >Please <a  href="signin.php">sign in.</a></span>
                </strong>
                <?php
            } else {
                if($flag){?>
                <strong style="text-align: center; color: #dd2222; font-size: 15px">
                    This Email is already in use,<br>
                    Please use another one.
                </strong>
           <?php }}
            ?>
        </div>
        <!--            <a href="#" class="forgot">Forgot your email or password?</a>-->
    </form>

<!--    <form action="signup.php" method="post">-->
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
<!--                <td>First name</td>-->
<!--                <td><input type="text" name="first_name" placeholder="Enter your first name" required></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>Last name</td>-->
<!--                <td><input type="text" name="last_name" placeholder="Enter your last name" required></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td colspan="2">-->
<!--                    <input type="submit" name="signup-btn" value="SIGN UP">-->
<!--                </td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </form>-->
</div>
<?php
include "footer.php";