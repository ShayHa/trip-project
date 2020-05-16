<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

        <title>BestT(r)ip</title>
        <?php
        if( isset($chart_data) ) { ?>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart', 'bar']});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Counter', 'Search type'],
                    <?php
                    foreach ( $chart_data as $chart_row_data) { ?>
                    [ "<?php echo $chart_row_data[0];?>", <?php echo $chart_row_data[1];?> ],
                    <?php
                    }
                    ?>
                    /*['Copper', 8.94, '#b87333'],            // RGB value
                    ['Silver', 10.49, 'silver'],            // English color name
                    ['Gold', 19.30, 'gold'],
                    ['Platinum', 21.45, 'color: #e5e4e2'], // CSS-style declaration*/
                ]);

                var options = {
                    width: 600, height: 400,
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
                chart.draw(data, options);
            }
        </script>
        <?php
        }
        ?>
        <link rel="stylesheet" href="style/app.css">
    </head>
    <body>
        <header>
            <!-- Table Header -->
<!--            <table style="width: 1200px; margin: 0 auto;">-->
<!--                <tr>-->
<!--                    <td style="width: 50%;">-->
<!--                        <a href="home.php">BestT(r)ip</a>-->
<!--                        <a href="share_trip.php">Share your trip</a>-->
<!--                    </td>-->
<!--                    <td style="width: 50%; text-align: right;">-->
<!--                        <a href="signin.php">Sign in</a>-->
<!---->
<!--                        <a href="signup.php">Sign up</a>-->
<!--                    </td>-->
<!---->
<!--                </tr>-->
<!--            </table>-->

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
                        <li><a href="search_reports.php"> Search reports </a></li>
                        <li><a href="users_reports.php"> Users reports </a></li>
                    <?php
                    }
                    ?>
                    <?php
                    if( isLogin() ) { ?>
                        <li id="signin">
                            <a href="user_trips.php">Hello <?php echo $_SESSION['first_name']; ?></a>
                            <?php
                            $class = "no_messages";
                            if( $num_of_messages > 0 ) {
                                $class = "have_messages";
                            }
                            ?>
                            <a href="messages.php" class="<?php echo $class;?>">
                                <span style="position:relative;bottom:9px"><?php echo $num_of_messages; ?></span></a>
                        </li>
                        <li id="signup"><a href="logout.php">Logout</a></li>
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