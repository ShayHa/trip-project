<?php
include "functions/functions.php";

// אם המשתמש לא מחובר והגיע לכאן בטעות, מפנים אותו לדף התחברות
if( !isLogin()) {
    header('location:signin.php');
    exit;
}

include "header.php";
$all_trips = getUserTrips( $_SESSION['user_id'] );
#echo sizeof($all_messages);
?>
    <div class="trip_box">
        <?php
        ?>
        <table class="table table-hover" style="width: 100%;">
            <tr>
                <th scope="col">Trip Number</th>
                <th scope="col">Trip ID</th>
                <th scope="col">Trip Story</th>
                <th scope="col">Date Added</th>
            </tr>
            <?php
            $counter = 1;
            foreach ( $all_trips as $trip ) {

                ?>
                <tr class="">
                    <td><?php echo $counter++; ?></td>
                    <td><a href="trip.php?trip_id=<?php echo $trip['id'];?>"> <?php echo $trip['id']?></a></td>
                    <td><?php echo $trip['trip_story']; ?></td>
                    <td><?php echo $trip['date_added']; ?></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
<?php
#This if is to check if he has message, if yes it will color them.
//if (sizeof($all_messages) !=0){
//    setMessagesStatus($from_user['id'], $_SESSION['user_id'] );
//}

include "footer.php";