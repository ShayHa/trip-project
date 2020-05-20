<?php
include "functions/functions.php";
include "header.php";

if( !isLogin() or !$_SESSION['is_admin']) {
    echo '<script language="javascript"> alert("message successfully sent") </script>';
    header('location:home.php');
    exit;
}

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

$z = array();
foreach($data as $row){
    array_push($z,array("y"=>$row['count'], "label"=>$row[$field_name]));
}

?>


<?php


$pie_date = getResultForMonthlyRegistered();
$dataPoints = array();
foreach($pie_date as $row){
    array_push($dataPoints,array("label"=>$row['month'], "y"=>$row['registered users']));
}


?>


<!--    Script for parameter chart-->
<!--    Used this guide to make it:-->
<!--    https://canvasjs.com/html5-javascript-bar-chart/-->
<!--    https://canvasjs.com/docs/charts/chart-options/data/indexlabel/-->
<!--    https://www.chartjs.org/docs/latest/charts/mixed.html-->
<!--    https://canvasjs.com/docs/charts/how-to/render-multiple-charts-in-a-page/-->

<!--        Menu to see all charts-->
<!--    https://canvasjs.com/javascript-charts/chart-index-data-label/-->
<!--            all chart options-->
<!--    https://canvasjs.com/docs/charts/chart-options/-->

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

var chart1 = new CanvasJS.Chart("chartContainerPie", {
    theme: "light2",
    animationEnabled: true,
    title: {
        text: "Registered User Per Month - 2020"
    },
    data: [{
        type: "pie",
        indexLabel: "{y}",
        //yValueFormatString: "#,##0.00\"%\"",
        indexLabelPlacement: "inside",
        indexLabelFontColor: "#36454F",
        indexLabelFontSize: 18,
        indexLabelFontWeight: "bolder",
        showInLegend: true,
        legendText: "{label}",
        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
    }]
});
chart1.render();

    var chart2 = new CanvasJS.Chart("chartContainerLine", {
        zoomEnabled: true,
        animationEnabled: true,
        theme: "light2",
        title:{
            text: "Simple Line Chart"
        },
        axisY:{
            includeZero: false
        },
        data: [{
            type: "line",
            indexLabelFontSize: 16,
            dataPoints: [
                { y: 450 },
                { y: 414},
                { y: 520, indexLabel: "\u2191 highest",markerColor: "red", markerType: "triangle" },
                { y: 460 },
                { y: 450 },
                { y: 500 },
                { y: 480 },
                { y: 480 },
                { y: 410 , indexLabel: "\u2193 lowest",markerColor: "DarkSlateGrey", markerType: "cross" },
                { y: 500 },
                { y: 480 },
                { y: 510 }
            ]
        }]
    });
    chart2.render();
}
</script>




<div class="container">
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
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

<div class="container">
    <div class="row">
        <div class="col-sm-6" id="chartContainerPie" style="height: 370px;padding: 0px 10px 0 0;"></div>
        <div class="col-sm-6" id="chartContainerLine" style="height: 370px; padding: 0px 0px 0 10px;"></div>
    </div>
</div>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>

<?php
include "footer.php";
?>