<?php
include "functions/functions.php";
?>

<?php

$data = getResultForSeason('trip_types.id', 'type_name', 'type_id','trip_types');
$field_name = 'type_name';
if( isset( $_REQUEST['search_type'] )) {
    if ($_REQUEST['search_type'] === 'type_id') {
        $field_name = 'type_name';
        $data = getResultForSeason('trip_types.id', 'type_name', 'type_id','trip_types');

    } else if ($_REQUEST['search_type'] === 'theme_id') {
        $field_name = 'theme_name';
        $data = getResultForSeason('themes.id', 'theme_name', 'theme_id', 'themes');

    } else if ($_REQUEST['search_type'] === 'season_id') {
        $field_name = 'season_name';
        $data = getResultForSeason('seasons.id','season_name','season_id', 'seasons');

    } else if ($_REQUEST['search_type'] === 'price_id') {
        $field_name = 'price_name';
        $data = getResultForSeason('prices.id', 'price_name', 'price_id', 'prices');

    } else if ($_REQUEST['search_type'] === 'age_range_id') {
        $field_name = 'range_str';
        $data = getResultForSeason('age_range.id', 'range_str', 'age_range_id', 'age_range');
    }
}
?>


<?php


$z = array();

foreach($data as $row){

    array_push($z,array("y"=>$row['count'], "label"=>$row[$field_name]));


}

$dataPoints = array(
    array("label"=>"Industrial", "y"=>51.7),
    array("label"=>"Transportation", "y"=>26.6),
    array("label"=>"Residential", "y"=>13.9),
    array("label"=>"Commercial", "y"=>7.8)
)

?>
<!DOCTYPE HTML>
<html>
<head>

<!--    Script for parameter chart-->
<!--    Used this guide to make it:-->
<!--    https://canvasjs.com/html5-javascript-bar-chart/-->
<!--    https://canvasjs.com/docs/charts/chart-options/data/indexlabel/-->
<!--    https://www.chartjs.org/docs/latest/charts/mixed.html-->

    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light2",
                title:{
                    text:"Parameter <?php echo explode( '_', $field_name )[0]; ?> Result"
                },
                axisX:{
                    interval: 1
                },

                data: [{
                    type: "bar", //"bar", "column
                    name: "parameter",
                    axisYType: "secondary",
                    color: "#014D65",
                    indexLabel:"{y}",
                    dataPoints: <?php echo json_encode($z, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

        }
    </script>


</head>
<body>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<div id="chartContainer" style="height: 370px; width: 100%;"></div>

<div>
    <form method="post">
        <select class="btn" name="search_type" id="search_type">
            <option value="type_id">Type</option>
            <option value="theme_id">Theme</option>
            <option value="season_id">Season</option>
            <option value="price_id">Price</option>
            <option value="age_range_id">Age range</option>
        </select>
        <input class="btn btn-primary" type="submit" name="display_chart" value="Change Parameter">
    </form>
</div>
</body>
</html>
