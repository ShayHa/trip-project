<?php
include "functions/functions.php";
$is_signup = false;

if (isset($_REQUEST['signup-btn'])) {
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $first_name = $_REQUEST['first_name'];
    $last_name = $_REQUEST['last_name'];
    signup($email, $password, $first_name, $last_name);
    $is_signup = true;
}
include "header.php";
?>
    <form action="signup.php" method="post">

        <table>
            <?php
            if ($is_signup == true) { ?>
                <tr>
                    <td colspan="2">Sign up successfully. Please
                        <a href="signin.php">sign in.</a>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td>Email</td>
                <td><input type="email" name="email" placeholder="Enter your email address" required></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password" placeholder="Enter your password" required></td>
            </tr>
            <tr>
                <td>First name</td>
                <td><input type="text" name="first_name" placeholder="Enter your first name" required></td>
            </tr>
            <tr>
                <td>Last name</td>
                <td><input type="text" name="last_name" placeholder="Enter your last name" required></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" name="signup-btn" value="SIGN UP">
                </td>
            </tr>
        </table>
    </form>
<?php
include "footer.php";