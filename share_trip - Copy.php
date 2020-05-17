<?php
include "functions/functions.php";

if( !isLogin() ) {
    header('location:signin.php');
    exit;
}

if (isset($_REQUEST['share_trip_btn'])) {
    $destination_id = $_REQUEST['destination_id'];
    $type_id = $_REQUEST['type_id'];
    $theme_id = $_REQUEST['theme_id'];
    $season_id = $_REQUEST['season_id'];
    $age_range_id = $_REQUEST['age_range_id'];
    $price_id = $_REQUEST['price_id'];
    $trip_story = $_REQUEST['trip_story'];
    $recommended_attractions = $_REQUEST['recommended_attractions'];
    $places_to_eat = $_REQUEST['places_to_eat'];
    $hotels = $_REQUEST['hotels'];
    $good_to_know  = $_REQUEST['good_to_know'];
    $thing_to_give_up  = $_REQUEST['thing_to_give_up'];
    addNewTrip(
        $_SESSION['user_id'],
        $destination_id,
        $type_id,
        $theme_id,
        $season_id,
        $age_range_id,
        $price_id,
        $trip_story,
        $recommended_attractions,
        $places_to_eat,
        $hotels,
        $good_to_know,
        $thing_to_give_up
    );

    #Update the points for the user once he adds a new trip
    updatePoints($_SESSION['user_id']);
}

include "header.php";
?>
    <form class="center_div" id="form-div" action="share_trip.php" method="post" onsubmit="confirm('Thank you for the share! click ok to share')">
        <table>
            <!-- Destination-->
            <tr>
                <td><span class="required"> * </span> Destination</td>
                <td>
                    <select name="destination_id" required>
                        <option value="1">Tel Aviv</option>
                    </select>
                </td>
            </tr>
            <!-- Type-->
            <tr>
                <td><span class="required"> * </span> Type</td>
                <td>
                    <select name="type_id" required>
                        <option value="">Type</option>
                        <?php
                        $types = getTableData('trip_types');
                        foreach ($types as $type) { ?>
                            <option value="<?php echo $type['id']; ?>"><?php echo $type['type_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <!-- Theme-->
            <tr>
                <td><span class="required"> * </span> Theme</td>
                <td>
                    <select class="" name="theme_id" required>
                        <option value="">Theme</option>
                        <?php
                        $themes = getTableData('themes');
                        foreach ($themes as $theme) { ?>
                            <option value="<?php echo $theme['id']; ?>"><?php echo $theme['theme_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <!-- Season -->
            <tr>
                <td><span class="required"> * </span> Season</td>
                <td>
                    <select name="season_id" required>
                        <option value="">Season</option>
                        <?php
                        $seasons = getTableData('seasons');
                        foreach ($seasons as $season) { ?>
                            <option value="<?php echo $season['id']; ?>"><?php echo $season['season_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <!-- Price -->
            <tr>
                <td><span class="required"> * </span> Price</td>
                <td>
                    <select name="price_id" required>
                        <option value="">Price</option>
                        <?php
                        $prices = getTableData('prices');
                        foreach ($prices as $price) { ?>
                            <option value="<?php echo $price['id']; ?>"><?php echo $price['price_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <!-- Age Need to fix this-->
            <tr>
                <td><span class="required"> * </span> Age</td>
                <td>
                    <select name="age_range_id" required>
                        <option value="">Age</option>
                        <?php
                        $age_ranges = getTableData('age_range');
                        foreach ($age_ranges as $range) { ?>
                            <option value="<?php echo $range['id']; ?>"><?php echo $range['range_str']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <!-- Story-->
            <tr>
                <td>Story</td>
                <td>
                    <textarea name="trip_story" cols="30" rows="10"></textarea>
                </td>
            </tr>
            <!-- Attractions-->
            <tr>
                <td>Recommended <br>
                    Attractions
                </td>
                <td>
                    <textarea name="recommended_attractions" cols="30" rows="10"></textarea>
                </td>
            </tr>
            <!-- Eat-->
            <tr>
                <td>Something <br>
                    To <br>
                    Eat
                </td>
                <td>
                    <textarea name="places_to_eat" cols="30" rows="10"></textarea>
                </td>
            </tr>
            <!-- Hotels-->
            <tr>
                <td>
                    Hotels
                </td>
                <td>
                    <textarea name="hotels" cols="30" rows="10"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    Good to know
                </td>
                <td>
                    <textarea name="good_to_know" cols="30" rows="10"></textarea>
                </td>
            </tr>
            <tr>
                <td>

                </td>
                <td>
                    <textarea class="feedback-input" id="comment" name="thing_to_give_up" cols="30" rows="10" placeholder="Thing to give up"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" name="share_trip_btn" value="SHARE">
                </td>
            </tr>
        </table>
    </form>
<?php
include "footer.php";