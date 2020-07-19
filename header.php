<!--<!DOCTYPE html>-->
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<!--        <script src="javascript/canvasjs.min.js"></script>-->
        <title>BestT(r)ip</title>
        <link rel="stylesheet" href="style/app.css">
    </head>
    <body>
        <header>
            <?php
            if( isLogin() ) {

                $messages = getMessages( $_SESSION['user_id'], true );
                $num_of_messages = sizeof($messages);
            }
            ?>
            <div class="nav">
                <ul>
                    <li><a href="home.php"> Best T(r)ips </a></li>
                    <li><a href="about.php"> About Us </a></li>
                    <li><a href="share_trip.php"> Share Your T(r)ip </a></li>
                    <?php
                    if( isLogin() && $_SESSION['is_admin'] == 1 ) { ?>
                        <li><a href="reports.php"> Reports </a></li>

                    <?php
                    }
                    ?>
                    <?php
                    if( isLogin() ) { ?>
                        <li><a href="user_trips.php"> My Tr(i)ps </a></li>
                        <li id="signin">
                            <a href="#">Hello <?php echo $_SESSION['first_name']; ?></a>
                            <?php
                            $class = "no_messages";
                            if( $num_of_messages > 0 ) {
                                $class = "have_messages";
                            }
                            ?>
                            <a href="messages.php" class="<?php echo $class;?>">
                                <span style="position:relative;bottom:9px"><?php echo $num_of_messages; ?></span></a>
                        </li>
                        <li id="logout"><a href="logout.php">Logout</a></li>
                    <?php
                    } else { ?>
                        <li id="signin"><a href="signin.php"> Sign in </a></li>
                        <li id="signup"><a href="signup.php"> Sign up </a></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </header>
        <main>
            <div class="content">