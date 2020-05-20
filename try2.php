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

<?php
$line_data = getTripsAdded();
$points_date = array();

foreach ($line_data as $row){
    //$date = date_create("".$row['year']."-".$row['month']."-01");
    $month = $row['month']-1;
    $date = "new Date(".$row['year'].",".$month.",01)";
    array_push($points_date,array("x"=>$date,"y"=>$row['count']));
    //array_push($points_date,array("x"=>date_format($date,"Y/m/d"), "y"=>$row['count']));
}
//echo print_r($points_date);
//echo json_encode($points_date, JSON_NUMERIC_CHECK);
$counter = 1;
$s = "[";
foreach($points_date as $point){

    if (sizeof($points_date) == $counter){
        $s = $s."{ x: ".$point['x'].", y: ".$point['y']." }]";
        break;
    }
    else{
        $s = $s."{ x: ".$point['x'].", y: ".$point['y']." },";
    }
    $counter++;
}
//echo $s;
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
    // https://canvasjs.com/docs/charts/chart-types/html5-step-line-chart/
    var chart2 = new CanvasJS.Chart("chartContainerLine", {
        animationEnabled: true,
        zoomEnabled: true,
        theme: "light2",
        title:{
            text: "Site Users"
        },
        axisX:{
            //valueFormatString: "DD MMM",
            crosshair: {
                enabled: true,
                snapToDataPoint: true
            }
        },
        axisY: {
            title: "Number of Users",
            crosshair: {
                enabled: true
            }
        },
        toolTip:{
            shared:true
        },
        legend:{
            cursor:"pointer",
            verticalAlign: "bottom",
            horizontalAlign: "left",
            dockInsidePlotArea: true,
            itemclick: toogleDataSeries
        },
        data: [{
            type: "line",
            showInLegend: true,
            name: "Total Users",
            markerType: "square",
            //xValueFormatString: "DD MMM, YYYY",
            color: "#F08080",
            dataPoints: <?php echo $s; ?>
            //     [
            //     { x: new Date(2017, 0, 3), y: 650 },
            //     { x: new Date(2017, 0, 4), y: 700 },
            //     { x: new Date(2017, 0, 5), y: 710 },
            //     { x: new Date(2017, 0, 6), y: 658 },
            //     { x: new Date(2017, 0, 7), y: 734 },
            //     { x: new Date(2017, 0, 8), y: 963 },
            //     { x: new Date(2017, 0, 9), y: 847 },
            //     { x: new Date(2017, 0, 10), y: 853 },
            //     { x: new Date(2017, 0, 11), y: 869 },
            //     { x: new Date(2017, 0, 12), y: 943 },
            //     { x: new Date(2017, 0, 13), y: 970 },
            //     { x: new Date(2017, 0, 14), y: 869 },
            //     { x: new Date(2017, 0, 15), y: 890 },
            //     { x: new Date(2017, 0, 16), y: 930 }
            // ]
        }]
    });
    chart2.render();

    function toogleDataSeries(e){
        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
            e.dataSeries.visible = false;
        } else{
            e.dataSeries.visible = true;
        }
        chart2.render();
    }
}
</script>




<div class="container" style="padding: 2px">
    <div id="chartContainer" style="height: 350px; width: 100%;"></div>
    <form method="post">
        <select class="btn" name="search_type" id="search_type" style="height:50px;  margin-top: 10px;">
            <option value="type_id">Type</option>
            <option value="theme_id">Theme</option>
            <option value="season_id">Season</option>
            <option value="price_id">Price</option>
            <option value="age_range_id">Age range</option>
        </select>
        <input class="btn btn-primary" type="submit" name="display_chart" value="Change Parameter"
               style="margin:10px 0px 0 7px;height:50px;">
    </form>
</div>
<div class="container" style="margin-top:0px">
    <div class="row">
        <div class="col-sm-6" id="chartContainerPie" style="height: 370px; width: 85%;padding: 0px 10px 0 0;"></div>
        <div class="col-sm-6" id="chartContainerLine" style="height: 370px;width: 85%;"></div>
    </div>
</div>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>

<?php
include "footer.php";
?>