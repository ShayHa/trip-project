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
    <form class="center_div" id="form-div" action="share_trip.php" method="post" onsubmit="alert('Thank you for the share! click ok to share')">
        <div style="padding: 35px; margin-left: 25px; color: #fbfbfb">
        <div>
        <label for=""><span class="required"> * </span> Destination
            <select id="" name="destination_id">
                <option value="1">Tel Aviv </option>
                </select>
        </label>
        </div>
        <!-- Type-->
        <div>
            <label for="SelectOEM"><span class="required"> * </span> Type
                <select id="" name="type_id" required>
                    <option value="">Type</option>
                    <?php
                    $types = getTableData('trip_types');
                    foreach ($types as $type) { ?>
                        <option value="<?php echo $type['id']; ?>"><?php echo $type['type_name']; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </label>
        </div>
        <!-- Theme-->
        <div>
            <label for=""><span class="required"> * </span> Theme
                <select id="" name="theme_id" required>
                    <option value="">Theme</option>
                    <?php
                    $types = getTableData('themes');
                    foreach ($types as $type) { ?>
                        <option value="<?php echo $type['id']; ?>"><?php echo $type['theme_name']; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </label>
        </div>
        <!-- Season -->
        <div>
            <label for=""><span class="required"> * </span> Season
                <select id="" name="season_id" required>
                    <option value="">Season</option>
                    <?php
                    $types = getTableData('seasons');
                    foreach ($types as $type) { ?>
                        <option value="<?php echo $type['id']; ?>"><?php echo $type['season_name']; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </label>
        </div>
        <!-- Price -->
        <div>
            <label for=""><span class="required"> * </span> Price
                <select id="" name="price_id" required>
                    <option value="">Price</option>
                    <?php
                    $types = getTableData('prices');
                    foreach ($types as $type) { ?>
                        <option value="<?php echo $type['id']; ?>"><?php echo $type['price_name']; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </label>
        </div>
        <!-- Age -->
        <div>
            <label for=""><span class="required"> * </span> Age
                <select id="" name="age_range_id" required>
                    <option value="">Age</option>
                    <?php
                    $types = getTableData('age_range');
                    foreach ($types as $type) { ?>
                        <option value="<?php echo $type['id']; ?>"><?php echo $type['range_str']; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </label>
        </div>
        </div>
        <!-- Story -->
        <div style="padding: 30px; margin-left: 25px">
        <div>

            <textarea class="feedback-input comment" name="trip_story" cols="40" rows="10"
                      placeholder="Tell us about your trip story"></textarea>
        </div>
        <!-- Attractions -->
        <div>

            <textarea class="feedback-input comment" name="recommended_attractions" cols="40" rows="10"
                      placeholder="Tell us about your trip recommended attractions"></textarea>
        </div>
        <!-- Eat -->
        <div>

            <textarea class="feedback-input comment" name="places_to_eat" cols="40" rows="10"
                      placeholder="Tell us about your trip most delicious food"></textarea>
        </div>
        <!-- Hotels -->
        <div>

            <textarea class="feedback-input comment" name="hotels" cols="40" rows="10"
                      placeholder="Tell us about your hotels"></textarea>
        </div>
        <!-- Good to know -->
        <div>

            <textarea class="feedback-input comment"  name="good_to_know" cols="40" rows="10"
                      placeholder="Tell us about your good to know stuff"></textarea>
        </div>
        <!-- Give up -->
        <div>

            <textarea class="feedback-input comment" name="thing_to_give_up" cols="40" rows="10"
                      placeholder="Tell us about your give up stuff"></textarea>
        </div>
        </div>
        <div class="">
        <input class="btn btn-block btn-color" type="submit" name="share_trip_btn" value="SHARE">
        </div>
    </form>
<?php
include "footer.php";
?>
