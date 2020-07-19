<?php
include "functions/functions.php";
/*if( !isLogin() || $_SESSION['is_admin'] == 1 ) {

}*/

if( isset( $_REQUEST['search_type'] )) {
    $field_name = "";
    if( $_REQUEST['search_type'] === 'type_id' ) {
        $field_name = "type_name";
        $names = getTableData('trip_types');
    } else if( $_REQUEST['search_type'] === 'theme_id' ) {
        $field_name = "theme_name";
        $names = getTableData('themes');
    } else if( $_REQUEST['search_type'] === 'season_id' ) {
        $field_name = "season_name";
        $names = getTableData('seasons');
    } else if( $_REQUEST['search_type'] === 'price_id' ) {
        $field_name = "price_name";
        $names = getTableData('prices');
    } else if( $_REQUEST['search_type'] === 'age_range_id' ) {
        $field_name = "range_str";
        $names = getTableData('age_range');
    }

    $stat_data = getSearchStats( $_REQUEST['search_type'] );
    $chart_data = array();
    foreach ( $stat_data as $data ) {
        $chart_data[] = array(
            $names[ $data[ $_REQUEST['search_type'] ] ][$field_name],
            $data['count']
        );
    }

}

include "header.php";

if( isset( $stat_data )) {
    print_r($stat_data);
    echo "<br>**************************<br>";
    print_r($chart_data);
}
?>
    <form action="search_reports.php" method="post">
        <table>
            <tr>
                <td>
                    <label for="">Choose parameter:</label>
                </td>
                <td>
                    <select name="search_type" id="search_type">
                        <option value="type_id">Type</option>
                        <option value="theme_id">Theme</option>
                        <option value="season_id">Season</option>
                        <option value="price_id">Price</option>
                        <option value="age_range_id">Age range</option>
                    </select>
                </td>
            </tr>
            <tr>
               <td colspan="2">
                   <input type="submit" name="display_chart" value="Display chart">
               </td>
            </tr>
        </table>
    </form>

    <div id="chart_div">



    </div>
<?php
include "footer.php";