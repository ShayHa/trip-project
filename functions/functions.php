<?php
session_start();

define( "MAX_SEARCH_RESULTS", "5" );
/*
 * Initialize DB connection
 * From outer config file
 */

$configs = include("../config.php");
$host = $configs['host'];
$database = $configs['database'];
$user =$configs['user'];
$password = $configs['password'];

// mysqli constructor
$mysqli = new mysqli( $host, $user, $password, $database );
/*
 * This is to ensure getting error if the connection params are not good
 * And to see what is the error.
 * */
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
        . $mysqli->connect_error);
}

$mysqli->set_charset('utf8' );

function signup( $email, $password, $first_name, $last_name ) {
    /*
     * Function to insert a new user to the database
     * The function will encrypt the users' password so we will not keep his original password.
     * */
    global $mysqli;
    $password = md5( $password );
    $insert = "INSERT INTO users (`email`, `password`, `first_name`, `last_name`) VALUES( '$email', '$password', '$first_name', '$last_name')";
    $mysqli->query( $insert );
}

function checkIfExsits($email){
    /*
     * Function to check whether the user is already exists or its a new one
     * based on the email used to register.
    * */
    global $mysqli;
    $sql = "SELECT * FROM users WHERE `email`='$email'";
    $results = $mysqli->query( $sql );
    if ($results->num_rows !=0){
        return true;
    } else{
        return false;
    }
}

function signin( $email, $password ) {
    /*
     * Function to check if a user is already exists on
     * our DB by checking the email and password.
     * */
    global $mysqli;
    $password = md5( $password );
    $select = "SELECT * FROM users WHERE `email`='$email' AND `password`='$password'";
    $results = $mysqli->query( $select );
    if( $results->num_rows === 1 ) {
        // Set session params to be used later to show more things for admins and so on
        $user = $results->fetch_assoc();
        $_SESSION['is_login'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['is_admin'] = $user['is_admin'];
        return true;
    } else {
        return false;
    }
}

function isLogin() {
    /*
     * Function to check if a user is logged in or not
     * Used for various options such as letting him to share a trip and so on.
     * */
    if( !empty( $_SESSION['is_login'] ) && $_SESSION['is_login'] == true ) {
        return true;
    } else {
        return false;
    }
}

function getUserById( $user_id ) {
    /*
     * Function to get specific user data
     * Mainly used for messages to get the "from_user" details.
     * */
    global $mysqli;
    $select = "SELECT * FROM `users` WHERE `id`=$user_id";
    $results = $mysqli->query( $select );
    if( $results->num_rows === 1 ) {
        $user = $results->fetch_assoc();
        return $user;
    } else {
        return false;
    }
}


function getTableData( $table_name ) {
    /*
     * Function to get full table data.
     * Mainly used for home page for the select values
     * */
    global $mysqli;
    $results = $mysqli->query( "SELECT * FROM `$table_name`" );
    return $results->fetch_all( MYSQLI_ASSOC );
}

function getTrips(){
    /*
     * Function to get trips from DB.
     * If a user is logged in we will not take his own trips.
     * */
    global $mysqli;

    $sql = "SELECT `trips`.*, `users`.`first_name`, `users`.`last_name`, `users`.`user_image` 
            FROM `trips` LEFT JOIN `users`
            ON `trips`.`user_id` = `users`.`id`";
    if( isLogin() ) {
        // checking if a user is logged in so we will not show him his own trips
        $user_id = $_SESSION['user_id'];
        $sql .= " WHERE `trips`.`user_id`!=$user_id";
    }

    $results = $mysqli->query( $sql );
    return $results->fetch_all( MYSQLI_ASSOC );
}

function insertSearchHistory( $type_id, $theme_id, $season_id, $price_id, $age_range_id ) {
    /*
     * Function to insert the search a user or a guest searched for.
     * Saves the user id if he was logged it, else default value of 0
     * https://www.w3schools.com/php/func_mysqli_insert_id.asp Auto generate the id to return the search_id
     * */
    global $mysqli;
    $user_id = 0;
    if( isLogin() ) {
        $user_id = $_SESSION['user_id'];
    }
    $insert = "INSERT INTO `search_history` (user_id,type_id, theme_id, season_id, price_id, age_range_id )
                                            VALUES($user_id,$type_id, $theme_id, $season_id, $price_id, $age_range_id )";

    $mysqli->query( $insert );
    return $mysqli->insert_id;
}

function getSearchStats( $search_type ) { // $search_type => type_id, theme_id, season_id....
    /*
     * !!!NOT IN USE ANYMORE!!!
     * Function used to give result for each search
     * */
    global $mysqli;
    $sql = "SELECT `$search_type`, COUNT(*) AS `count` FROM `search_history` GROUP BY `$search_type`";
    $result = $mysqli->query( $sql );
    return $result->fetch_all( MYSQLI_ASSOC );
}


function insertSearchTrip( $search_id, $trip_id, $score ) {
    /*
     * Inserting the trips that were shown as results.
     * Saving the trip_id as well as the score it got.
     * */
    global $mysqli;
    $insert = "INSERT INTO `search_history_trips` (`search_id`, `trip_id`, `score`) VALUES ( $search_id, $trip_id, $score )";
    $mysqli->query( $insert );
}

function insertMessage( $message, $from_user_id, $to_user_id ) {
    /*
     * Inserting messages from user to user so we can track which user read which message.
     * */
    global $mysqli;
    $insert = "INSERT INTO `messages` (`message`, `from_user_id`, `to_user_id`) VALUES ( '$message', $from_user_id, $to_user_id )";
    $mysqli->query( $insert );
}

function getMessages( $user_id, $only_not_opened = true ) {
    /*
     * only_not_open = true --> shows only messages that the user did not see
     * only_not_open = false --> shows all messages
     *
     * */
    global $mysqli;
    if($only_not_opened) {
        $sql = "SELECT * FROM `messages` WHERE `to_user_id`=$user_id AND `is_opened`=0 ORDER BY `date_sent` DESC";
    } else {
        $sql = "SELECT * FROM `messages` WHERE `to_user_id`=$user_id ORDER BY `date_sent` DESC";
    }

    $result = $mysqli->query( $sql );
    return $result->fetch_all( MYSQLI_ASSOC ); // [0 => ''] ['user_id' ]
}


function setMessagesStatus( $from_user_id, $to_user_id ) {
    /*
     * Function used to update the message that user read
     *
     * */
    global $mysqli;
    $update = "UPDATE `messages` SET `is_opened`=1 WHERE `from_user_id`=$from_user_id AND `to_user_id`=$to_user_id AND `is_opened`=0";
    $mysqli->query( $update );

}

function getTripById( $trip_id ){
    /*
     * Function to get the user trip by trip_id
     * Since trip id is unique and connected to a specific user we can obtain the
     * trip and user details by trip_id
     * */
    global $mysqli;
    $sql = "SELECT `trips`.*, `users`.`first_name`, `users`.`last_name`, `users`.`email`, `users`.`user_image` 
            FROM `trips` LEFT JOIN `users`
            ON `trips`.`user_id` = `users`.`id`
            WHERE `trips`.`id`=$trip_id";
    $results = $mysqli->query( $sql );
    return $results->fetch_assoc( );
}


function getPoints($trip_id){
    /*
     * Get the points of a user based on the trip id.
     *
     * */
    global $mysqli;
    $sql =  "SELECT points
                FROM `users` u left join `trips` t on u.id = t.user_id
                where t.id = $trip_id";
    $results = $mysqli->query( $sql );
    return $results->fetch_assoc( );
}

function updatePoints($user_id){
    /*
     * Update the user points after he added a trip
     *
     * */
    global $mysqli;
    $sql = "
            UPDATE users 
            SET 
            points = points+1
            WHERE
            users.id = $user_id";
    $mysqli->query( $sql );
}

function addNewTrip(
    $user_id,
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
    $thing_to_give_up)
{
    /*
     * Inserting a new trip the user shared into the trips table
     *
     * */
    global $mysqli;
    $insert = "INSERT INTO `trips` 
                          (user_id, destination_id, type_id, theme_id, season_id, age_range_id, price_id, trip_story,recommended_attractions, places_to_eat, hotels, good_to_know, thing_to_give_up)
                    VALUES($user_id, $destination_id, $type_id, $theme_id, $season_id, $age_range_id, $price_id, '$trip_story', '$recommended_attractions', '$places_to_eat', '$hotels', '$good_to_know', '$thing_to_give_up')";

    $mysqli->query($insert);
}

function getRelation( $relation_type, $from, $to ) {
    /*
     * Get the relation value between for season, type, theme, price, age
     *
     * */
    global $mysqli;
    $result = $mysqli->query( "SELECT `value` FROM `relations` WHERE `relation_type`='$relation_type' AND `from_relation`=$from AND `to_relation`=$to LIMIT 1" );
    return $result->fetch_assoc();
}

function getUserTrips($user_id){
    /*
     * Function to get users trips
     * */
    global $mysqli;
    $sql = "SELECT * FROM `trips` WHERE `user_id`=$user_id ORDER BY `date_added` DESC";
    $result = $mysqli->query( $sql );
    return $result->fetch_all( MYSQLI_ASSOC ); // [0 => ''] ['user_id' ]
}

function getUserName($trip_id){
    /*
     * Function to get the name and last name of a user based on trip_id
     * !!NOT IN USE ANYMORE!!
     * */
    global $mysqli;
    $sql = "select CONCAT(u.first_name, \" \", u.last_name) as \"name\"
            from users u INNER JOIN trips t on u.id = t.user_id
            where t.id = $trip_id";

    $result = $mysqli->query( $sql );
    return $result->fetch_all( MYSQLI_ASSOC );
}

function getResultForSeason($join_on, $field_name,$history_col,$table_name) { // $search_type => type_id, theme_id, season_id....
    /*
     * Function used for the first report to count how many times people searched for each result of a parameter.
     * For example for Type parameter how many times they searched for
     *
     * */
    global $mysqli;

    $sql = "select `$field_name`, COALESCE(count(search_history.$history_col),0) AS `count`
            from `$table_name` left join search_history on $join_on = search_history.$history_col group by `$field_name`";

//    echo $sql;
    $result = $mysqli->query( $sql );
    return $result->fetch_all( MYSQLI_ASSOC );
}


function getResultForMonthlyRegistered(){
    /*
     * Function used for the second report, get monthly registered user for year 2020 and order by month
     *
     * */
    global $mysqli;

    $sql = "SELECT DATE_FORMAT(u.register_date, '%M') as \"month\",
                    EXTRACT(YEAR from u.register_date) as \"year\" ,
                     count(*) as \"registered users\" 
            FROM users u 
            group by month, year 
            HAVING year = 2020
            order by FIELD(month,'January','February','March','April','May','June','July','August','September','October','November','December')";

    $result = $mysqli->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getTripsAdded(){
    /*
     * Function used for the third report, get monthly added trips for year 2020 and order by
     *
     * */
    global $mysqli;

    $sql = "SELECT EXTRACT(MONTH from t.date_added) as \"month\",
                    EXTRACT(YEAR from t.date_added) as \"year\" ,
                     count(*) as \"count\" 
            FROM trips t 
            group by month, year 
            HAVING year = 2020";

    $result = $mysqli->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


