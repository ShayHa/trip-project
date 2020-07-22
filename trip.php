<?php
include "functions/functions.php";

$sent_msg = null;
/* Initialize default trip id then take the id from the previous page (get method) and find
    the data. After that I get the trip data then check if the user is logged in
    then check if is is the same user before the send message
*/
$trip_id = 1;
if(isset( $_REQUEST['trip_id'])) {
    $trip_id = $_REQUEST['trip_id'];
}
$trip = getTripById($trip_id);

if(isLogin())  {
    // checking if its a user and not a guest then he can sent a message
    // and insert the text to DB
    if (isset($_REQUEST['send_message'])) {
        insertMessage($_REQUEST['message'], $_SESSION['user_id'], $_REQUEST['to_user_id'] );
        $sent_msg = "a";
    }
}

// get the points of the user based on trip_id
$points = getPoints($trip_id);
include "header.php";
?>
<div>
    <!-- Building table to show the trip -->
    <table style="width: 100%;" class="trip_info">
        <tr>
            <td style="width: 20%;"></td>
            <td style="width: 70%;"></td>
        </tr>
        <tr>
            <td>
                <img src="images/<?php echo $trip['user_image'];?>" alt="" height="120px">
                <p>
                    <strong>
                    <?php echo $trip['last_name'] . " " . $trip['first_name'];?>
                    </strong>
                </p>
            </td>
            <td>
                <br><br><br><br>
                User Points: <?php echo $points['points'] ?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                Destination
                </strong>
            </td>
            <td>
                Tel Aviv
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                Type
                </strong>
            </td>
            <td>
                <?php
                $types = getTableData('trip_types' );
                // -1 because values in PHP starts from 0
                // we need to get tabledata since we save the ID in the trip and not the type itself
                echo $types[ $trip['type_id'] - 1]['type_name'];
                //print_r($types[0]);
                ?>
                

            </td>
        </tr>
        <tr>
            <td>
                <strong>
                Theme
                </strong>
            </td>
            <td>
                <?php
                $themes = getTableData('themes' );
                echo $themes[$trip['theme_id'] - 1 ]['theme_name'];
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                Season
                </strong>
            </td>
            <td>
                <?php
                $seasons = getTableData('seasons' );
                echo $seasons[$trip['season_id'] - 1 ]['season_name'];
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                T(r)ip story
                </strong>
            </td>
            <td>
                <?php echo $trip['trip_story'];?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                Recommended Attractions
                </strong>
            </td>
            <td>
                <?php echo $trip['recommended_attractions'];?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                Something to eat
                </strong>
            </td>
            <td>
                <?php echo $trip['places_to_eat'];?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                Hotels
                </strong>
            </td>
            <td>
                <?php echo $trip['hotels'];?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                Good to know
                </strong>
            </td>
            <td>
                <?php echo $trip['good_to_know'];?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                Things to give up
                </strong>
            </td>
            <td>
                <?php echo $trip['thing_to_give_up'];?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                Price Range
                </strong>
            </td>
            <td>
                <?php
                $prices = getTableData('prices' );
                echo $prices[$trip['price_id'] - 1 ]['price_name'];
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                Age Range
                </strong>
            </td>
            <td>
                <?php
                $age_range = getTableData('age_range' );
                echo $age_range[$trip['age_range_id'] - 1 ]['range_str'];
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                Contact
                </strong>
            </td>
            <td>
                <a href="mailto:<?php echo $trip['email'];?>"><?php echo $trip['email'];?></a>
            </td>
        </tr>
        <?php
        // checking if its not a guest then if its not the same user
        // then he can send a message to other user
        if( isLogin() and ($trip['user_id'] != $_SESSION['user_id'] )) {
            ?>
            <tr>
                <td><strong> Send message</strong> </td>
                <td>
                    <form action="trip.php?trip_id=<?php echo $trip_id;?>" method="post">
                        <table>
                            <tr>
                                <td><strong> Your message</strong></td>
                                <td>
                                    <textarea class="feedback-input comment"  name="message"
                                              cols="40" rows="3" maxlength="70" placeholder="Type your question here"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="position:relative;bottom:9px; left:200px">
                                    <input class="btn btn-primary" style="background-color:rgb(198,243,240); color: #3c3c3c" type="submit" name="send_message" value="SEND">
                                    <input type="hidden" name="to_user_id" value="<?php echo $trip['user_id']?>">
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
</div>
<?php if( isset( $sent_msg ) ) { ?>
    <script>
        alert('Your message was sent successfully!');
    </script>
    <?php
}
?>
<?php
include "footer.php";