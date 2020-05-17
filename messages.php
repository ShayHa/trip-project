<?php
include "functions/functions.php";

// אם המשתמש לא מחובר והגיע לכאן בטעות, מפנים אותו לדף התחברות
if( !isLogin()) {
    header('location:signin.php');
    exit;
}

include "header.php";
$all_messages = getMessages( $_SESSION['user_id'], false );

?>
    <div class="messages_box">
        <?php
        if (sizeof($all_messages) == 0){
            echo "<span class='check'>"."It seems that you do not have any messages, maybe you should share more!"."</span>";
        }
        else {
        ?>
        <table style="width: 100%;text-align: center;" border="1">
            <tr>
                <th>#</th>
                <th>From</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
            <?php
            $counter = 1;
            foreach ( $all_messages as $message ) {
                $from_user = getUserById( $message['from_user_id'] );
                ?>
                <tr class="<?php if( $message['is_opened'] == 0 ) { echo "message_row not_opened";} else { echo "message_row opened"; } ?>">
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo $from_user['last_name'].' '.$from_user['first_name']; ?></td>
                    <td><a href="mailto:<?php echo $from_user['email']; ?>"><?php echo $from_user['email']; ?></a></td>
                    <td><?php echo $message['message']; ?></td>
                    <td><?php echo $message['date_sent']; ?></td>
                </tr>
            <?php
            }
            ?>
        </table>
<!--    This is to close the else for the no messages statement-->
            <?php }; ?>
    </div>
<?php
#This if is to check if he has message, if yes it will color them.
if (sizeof($all_messages) !=0){
    setMessagesStatus($from_user['id'], $_SESSION['user_id'] );
}

include "footer.php";