<?php
include "functions/functions.php";
include "header.php";

$weights = getTableData( 'weights' );
if( isset( $_REQUEST['search_button']) ) {
    $trips = getTrips();
    $theme_id = $_REQUEST['theme_id'];

    $validation_error = false;
    if( $_REQUEST['type_id'] == 0 || $_REQUEST['theme_id'] == 0 || $_REQUEST['season_id'] == 0 || $_REQUEST['price_id'] == 0 || $_REQUEST['age_range_id'] == 0) {
        $validation_error = true;
        ?>
        <script>
            alert( 'נא לבחור את כל הפרמטרים של החיפוש' );
        </script>
    <?php
    } else {
        for ($i = 0; $i < sizeof($trips); $i++) {
            $relation = getRelation('Type', $_REQUEST['type_id'], $trips[$i]['type_id']);
            $trips[$i]['score'] = $relation['value'] * $_REQUEST['type_weight'];

            $relation = getRelation('Theme', $_REQUEST['theme_id'], $trips[$i]['theme_id']);
            $trips[$i]['score'] += $relation['value'] * $_REQUEST['theme_weight'];;

            $relation = getRelation('Season', $_REQUEST['season_id'], $trips[$i]['season_id']);
            $trips[$i]['score'] += $relation['value'] * $_REQUEST['season_weight'];;


            $relation = getRelation('Price', $_REQUEST['price_id'], $trips[$i]['price_id']);
            $trips[$i]['score'] += $relation['value'] * $_REQUEST['price_weight'];;


            $relation = getRelation('Age', $_REQUEST['age_range_id'], $trips[$i]['age_range_id']);
            $trips[$i]['score'] += $relation['value'] * $_REQUEST['age_weight'];
        }

        // sort the array of trips by score value from high to low
        usort($trips, function ($a, $b) {
            return $b['score'] - $a['score'];
        });


        // שמירת פרמטרים החיפוש בטבלה search_history
        $search_id = insertSearchHistory($_REQUEST['type_id'], $_REQUEST['theme_id'], $_REQUEST['season_id'], $_REQUEST['price_id'], $_REQUEST['age_range_id']);
        if (isset($trips) && sizeof($trips) > 0) {
            // עבור כל טיול שהתקבל אנו שומרים בטבלה search_history_trips
            $counter = 1;
            foreach ($trips as $trip) {
                insertSearchTrip($search_id, $trip['id'], $trip['score']);
                //  save only 5 trips
                $counter++;
                if ($counter == MAX_SEARCH_RESULTS) {
                    break;
                }
            }
        }
    }
}
?>
<div style="padding: 400px 50px 30px 50px; background-image: url('images/index.jpg'); background-repeat: no-repeat">
<div class="sigi">
    <form action="home.php" method="post" onsubmit="return checkWeights()">
        <h5 style="color: rgb(165, 165,165)">Choose your next t(r)ip</h5>
        <div id="search_form">
            <div class="search_form_field">
                <select name="type_id">
                    <option value="0">Type</option>
                    <?php
                    $types = getTableData('trip_types');
                    foreach ($types as $type) {
                        if( isset( $_REQUEST['type_id'] ) && $_REQUEST['type_id'] == $type['id'] ) { ?>
                            <option value="<?php echo $type['id']; ?>" selected="selected"><?php echo $type['type_name']; ?></option>
                        <?php
                        } else { ?>
                            <option value="<?php echo $type['id']; ?>"><?php echo $type['type_name']; ?></option>
                        <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="search_form_field">
                <select name="theme_id">
                    <option value="0">Theme</option>
                    <?php
                    $themes = getTableData('themes');
                    foreach ($themes as $theme) {
                        if( isset( $_REQUEST['theme_id'] ) && $_REQUEST['theme_id'] == $theme['id'] ) { ?>
                            <option value="<?php echo $theme['id']; ?>" selected="selected"><?php echo $theme['theme_name']; ?></option>
                        <?php
                        }
                        else { ?>
                            <option value="<?php echo $theme['id']; ?>"><?php echo $theme['theme_name']; ?></option>
                        <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="search_form_field">
                <select name="season_id">
                    <option value="0">Season</option>
                    <?php
                    $seasons = getTableData('seasons');
                    foreach ($seasons as $season) {
                        if( isset( $_REQUEST['season_id'] ) && $_REQUEST['season_id'] == $season['id'] ) { ?>
                            <option value="<?php echo $season['id']; ?>" selected="selected"><?php echo $season['season_name']; ?></option>
                            <?php
                        } else { ?>
                            <option value="<?php echo $season['id']; ?>"><?php echo $season['season_name']; ?></option>
                        <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="search_form_field">
                <select name="price_id">
                    <option value="0">Price</option>
                    <?php
                    $prices = getTableData('prices');
                    foreach ($prices as $price) {
                        if( isset( $_REQUEST['price_id'] ) && $_REQUEST['price_id'] == $price['id'] ) { ?>
                            <option value="<?php echo $price['id']; ?>" selected="selected"><?php echo $price['price_name']; ?></option>
                            <?php
                        } else { ?>
                            <option value="<?php echo $price['id']; ?>"><?php echo $price['price_name']; ?></option>
                        <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="search_form_field">
                <select name="age_range_id">
                    <option value="0">Age</option>
                    <?php
                    $age_ranges = getTableData('age_range');
                    foreach ($age_ranges as $range) {
                        if( isset( $_REQUEST['age_range_id'] ) && $_REQUEST['age_range_id'] == $range['id'] ) { ?>
                            <option value="<?php echo $range['id']; ?>" selected="selected"><?php echo $range['range_str']; ?></option>
                            <?php
                        } else { ?>
                            <option value="<?php echo $range['id']; ?>"><?php echo $range['range_str']; ?></option>
                        <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="search_form_field">
                <input type="submit" name="search_button" value="search" onclick="">
            </div>
        </div>
        <div id="weights_section">
            <div id ='wrapper'style="display: none">
                <div id='left'><a href="#" id="change_weights" onclick="changeWeight()">Change Weights</a></div>
                <div id="right" > Don't like the results? You can change the weights</div>
            </div>

            <div id="weights">
                <div class="weight_col">
                    <input type="number" name="type_weight" onchange="updateTotalWeight()" id="type_weight" value="<?php echo $weights[0]['weight_value'];?>" min="0" max="100">
                </div>
                <div class="weight_col">
                    <input type="number" name="theme_weight" onchange="updateTotalWeight()" id="theme_weight" value="<?php echo $weights[1]['weight_value'];?>" min="0" max="100">
                </div>
                <div class="weight_col">
                    <input type="number" name="season_weight" onchange="updateTotalWeight()" id="season_weight" value="<?php echo $weights[2]['weight_value'];?>" min="0" max="100">
                </div>
                <div class="weight_col">
                    <input type="number" name="price_weight" onchange="updateTotalWeight()" id="price_weight" value="<?php echo $weights[3]['weight_value'];?>" min="0" max="100">
                </div>
                <div class="weight_col">
                    <input type="number" name="age_weight" onchange="updateTotalWeight()" id="age_weight" value="<?php echo $weights[4]['weight_value'];?>" min="0" max="100">
                </div>

                <div class="weight_col">
                    Total:
                    <span id="total_weight">100%</span>
                </div>
            </div>
        </div>
    </form>


</div>
</div>

    <?php
    if( isset( $trips ) && sizeof( $trips ) > 0 && !$validation_error ) { ?>
        <table style="width: 100%;" id="search_results">

            <tr style="height: 50px; text-align: center">
                <th style="width: 15%">User</th>
                <th style="width: 70%;">Data</th>
                <th style="width: 15%;">Matching Score</th>
            </tr>
            <?php
            //$counter = 1;
            foreach ( $trips as $trip ) {
                $name = getUserName($trip['id']);
                if ( $counter != 0 ) {
                    ?>
                    <tr>
                        <td>
                            <img src="images/<?php echo $trip['user_image'];?> " alt=""><?php echo $name[0]['name']; ?>
                        </td>
                        <td style="font-size: 18px;">
                            <p>
                            <?php
                            echo substr($trip['trip_story'], 0, 300 );
                            ?>
                            </p>
                            <p>
                                <a href="trip.php?trip_id=<?php echo $trip['id'];?>">Read more...</a>
                            </p>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <strong><?php echo $trip['score']; ?> %</strong>
                        </td>
                    </tr>
                    <?php
                } else {
                    break;
                }
                $counter--;
            }
            ?>
        </table>

    <?php
    }
    ?>


<?php
include "footer.php";
?>
