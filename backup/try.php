<?php
include "functions/functions.php";
include "header.php";

?>

<?php
//echo print_r(getResultForSeason());
$data = getResultForSeason();
$z = array();
$shay = 1;
foreach($data as $row){
//    echo $row['season_name'];
//    echo $row['count'];
    array_push($z,array("x"=>$row['season_name'],"y"=>$row['count']));
    $shay= $shay+10;
}
echo print_r($z);

echo "<br><br>";
echo json_encode($z);
$a =
    array(
    array("x"=> 10, "y"=> 41),
    array("x"=> 20, "y"=> 35, "indexLabel"=> "Lowest"),
    array("x"=> 30, "y"=> 50),
    array("x"=> 40, "y"=> 45),
    array("x"=> 50, "y"=> 52),
    array("x"=> 60, "y"=> 68),
    array("x"=> 70, "y"=> 38),
    array("x"=> 80, "y"=> 71, "indexLabel"=> "Highest"),
    array("x"=> 90, "y"=> 52),
    array("x"=> 100, "y"=> 60),
    array("x"=> 110, "y"=> 36),
    array("x"=> 120, "y"=> 49),
    array("x"=> 130, "y"=> 41) )
;
echo json_encode($a);
?>
<!DOCTYPE HTML>
<html>
<head>
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title:{
                    text: "Simple Column Chart with Index Labels"
                },
                data: [{
                    type: "pie", //change type to bar, line, area, pie, etc,
                    //indexLabel: "{y}", //Shows y value on all Data Points
                    indexLabelFontColor: "#5A5757",
                    indexLabelPlacement: "inside",
                    dataPoints: <?php echo json_encode($z, JSON_UNESCAPED_SLASHES); ?>
                }],
                options:{
                    legend:{
                        display:true,
                        labels:"{x}"
                    }
                }
            });
            chart.render();

        }
    </script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
<?php
include "footer.php";
?>
