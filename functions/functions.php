<?php
session_start();

define( "MAX_SEARCH_RESULTS", "5" );
/*
 * Initialize DB connection
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
     * Function to insert a new user to the database
     * */
    global $mysqli;
    $password = md5( $password );
    $select = "SELECT * FROM users WHERE `email`='$email' AND `password`='$password'";
    $results = $mysqli->query( $select );
    if( $results->num_rows === 1 ) {
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
    if( !empty( $_SESSION['is_login'] ) && $_SESSION['is_login'] == true ) {
        return true;
    } else {
        return false;
    }
}

function getUserById( $user_id ) {
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
    global $mysqli;
    $results = $mysqli->query( "SELECT * FROM `$table_name`" );
    return $results->fetch_all( MYSQLI_ASSOC );
}

function getTrips(){
    global $mysqli;

    $sql = "SELECT `trips`.*, `users`.`first_name`, `users`.`last_name`, `users`.`user_image` 
            FROM `trips` LEFT JOIN `users`
            ON `trips`.`user_id` = `users`.`id`";
    if( isLogin() ) {
        // אם המשתמש מחובר אנו לא רוצים להציג את הטיולים שהוא העלה
        $user_id = $_SESSION['user_id'];
        $sql .= " WHERE `trips`.`user_id`!=$user_id";
    }

    $results = $mysqli->query( $sql );
    return $results->fetch_all( MYSQLI_ASSOC );
}

function insertSearchHistory( $type_id, $theme_id, $season_id, $price_id, $age_range_id ) {
    global $mysqli;
    $user_id = 0;
    if( isLogin() ) {
        $user_id = $_SESSION['user_id'];
    }
    $insert = "INSERT INTO `search_history` (type_id, user_id, theme_id, season_id, price_id, age_range_id )
                                            VALUES($type_id, $user_id, $theme_id, $season_id, $price_id, $age_range_id )";

    $mysqli->query( $insert );
    return $mysqli->insert_id;
}

function getSearchStats( $search_type ) { // $search_type => type_id, theme_id, season_id....
    global $mysqli;
    $sql = "SELECT `$search_type`, COUNT(*) AS `count` FROM `search_history` GROUP BY `$search_type`";

//     $sql = "SELECT `seasons`.`id`, `search_history`.`$search_type`, `search_history`.COUNT(*) AS `count`
//            FROM `seasons` LEFT JOIN `search_history` GROUP BY `search_history`.`$search_type`";
//    $sql = "select `$search_type`, COALESCE(count(search_history.season_id),0) AS `count`
//            from seasons left join search_history on seasons.id = search_history.season_id group by season_name";
//     echo $sql;


    $result = $mysqli->query( $sql );
    return $result->fetch_all( MYSQLI_ASSOC );
}


function insertSearchTrip( $search_id, $trip_id, $score ) {
    /*
     * @param: search_id:
     *
     *
     * */
    global $mysqli;
    $insert = "INSERT INTO `search_history_trips` (`search_id`, `trip_id`, `score`) VALUES ( $search_id, $trip_id, $score )";
    $mysqli->query( $insert );
}

function insertMessage( $message, $from_user_id, $to_user_id ) {
    global $mysqli;
    $insert = "INSERT INTO `messages` (`message`, `from_user_id`, `to_user_id`) VALUES ( '$message', $from_user_id, $to_user_id )";
    $mysqli->query( $insert );
}

function getMessages( $user_id, $only_not_opened = true ) {
    global $mysqli;
    if( $only_not_opened ) {
        $sql = "SELECT * FROM `messages` WHERE `to_user_id`=$user_id AND `is_opened`=0 ORDER BY `date_sent` DESC";
    } else {
        $sql = "SELECT * FROM `messages` WHERE `to_user_id`=$user_id ORDER BY `date_sent` DESC";
    }

    $result = $mysqli->query( $sql );
    return $result->fetch_all( MYSQLI_ASSOC ); // [0 => ''] ['user_id' ]
}


function setMessagesStatus( $from_user_id, $to_user_id ) {
    global $mysqli;
    $update = "UPDATE `messages` SET `is_opened`=1 WHERE `from_user_id`=$from_user_id AND `to_user_id`=$to_user_id AND `is_opened`=0";
    $mysqli->query( $update );

}

function getTripById( $trip_id ){
    global $mysqli;
    $sql = "SELECT `trips`.*, `users`.`first_name`, `users`.`last_name`, `users`.`email`, `users`.`user_image` 
            FROM `trips` LEFT JOIN `users`
            ON `trips`.`user_id` = `users`.`id`
            WHERE `trips`.`id`=$trip_id";
    $results = $mysqli->query( $sql );
    return $results->fetch_assoc( );
}

#return the points of each user
function getPoints($trip_id){
    global $mysqli;
    $sql =  "SELECT points
                FROM `users` u left join `trips` t on u.id = t.user_id
                where t.id = $trip_id";
    $results = $mysqli->query( $sql );
    return $results->fetch_assoc( );
}

#update the points after user adds a trip
function updatePoints($user_id){
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
    $thing_to_give_up ) {
    global $mysqli;
    $insert = "INSERT INTO `trips` 
                          (user_id, destination_id, type_id, theme_id, season_id, age_range_id, price_id, trip_story,recommended_attractions, places_to_eat, hotels, good_to_know, thing_to_give_up)
                    VALUES($user_id, $destination_id, $type_id, $theme_id, $season_id, $age_range_id, $price_id, '$trip_story', '$recommended_attractions', '$places_to_eat', '$hotels', '$good_to_know', '$thing_to_give_up')";

    $mysqli->query( $insert );
}

function getRelation( $relation_type, $from, $to ) {
    global $mysqli;
    $result = $mysqli->query( "SELECT `value` FROM `relations` WHERE `relation_type`='$relation_type' AND `from_relation`=$from AND `to_relation`=$to LIMIT 1" );
    return $result->fetch_assoc();
}

function getUserTrips($user_id){

    global $mysqli;
    $sql = "SELECT * FROM `trips` WHERE `user_id`=$user_id ORDER BY `date_added` DESC";
    $result = $mysqli->query( $sql );
    return $result->fetch_all( MYSQLI_ASSOC ); // [0 => ''] ['user_id' ]
}

function getUserName($trip_id){
    global $mysqli;
    $sql = "select CONCAT(u.first_name, \" \", u.last_name) as \"name\"
            from users u INNER JOIN trips t on u.id = t.user_id
            where t.id = $trip_id";

    $result = $mysqli->query( $sql );
    return $result->fetch_all( MYSQLI_ASSOC );
}

function getResultForSeason($join_on, $field_name,$history_col,$table_name) { // $search_type => type_id, theme_id, season_id....
    global $mysqli;

    $sql = "select `$field_name`, COALESCE(count(search_history.$history_col),0) AS `count`
            from `$table_name` left join search_history on $join_on = search_history.$history_col group by `$field_name`";

//    echo $sql;
    $result = $mysqli->query( $sql );
    return $result->fetch_all( MYSQLI_ASSOC );
}


function getResultForMonthlyRegistered(){
    global $mysqli;

    $sql = "SELECT DATE_FORMAT(u.register_date, '%M') as \"month\",
                    EXTRACT(YEAR from u.register_date) as \"year\" ,
                     count(*) as \"registered users\" 
            FROM users u 
            group by month, year 
            HAVING year = 2020";

    $result = $mysqli->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getTripsAdded(){
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