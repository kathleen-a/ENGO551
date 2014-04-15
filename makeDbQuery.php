<?php
function connect()
    {
        $serverName = "lrx7mvr6si.database.windows.net,1433";
        $user = "ENGO551";
        $pwd = "UrbanSpoon1";
        $db = "RestaurantDatabase";
        try{
            $conn =  new PDO ( "sqlsrv:server = tcp:lrx7mvr6si.database.windows.net,1433; Database = RestaurantDatabase", $user, $pwd);
            $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        catch ( PDOException $e ) 
        {
            print( "Error connecting to SQL Server." );
            die(print_r($e));
        }
        return $conn;
    }

    // Radius Query
function queryRadius($latitude, $longitude, $radius)
    {
        $conn = connect();
 
        $sql = "DECLARE @point geography = geography::Point( {$latitude} , {$longitude}, 4326);
                DECLARE @radius float = {$radius};
                SELECT L.Latitude, L.Longitude, Re.RestaurantName, R.Rating, F.PriceRange, A.*
                FROM Location L
                INNER JOIN Restaurants Re
                ON L.LocationID = Re.LocationID
                INNER JOIN Ratings R
                ON Re.RestaurantID = R.RestaurantID
                INNER JOIN Franchise F
                ON Re.RestaurantName = F.RestaurantName
                INNER JOIN Address A
                ON L.LocationID = A.LocationID
                WHERE @point.STDistance(L.GeoLocation) <= @radius*1000";
 
       
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }

// Ratings Query	
function queryRatings($low, $high)
    {
        $conn = connect();
 
        $sql = "SELECT L.Latitude, L.Longitude, Re.RestaurantName, R.Rating, F.PriceRange, A.*
                FROM Location L
                INNER JOIN Restaurants Re
                ON L.LocationID = Re.LocationID
                INNER JOIN Ratings R
                ON Re.RestaurantID = R.RestaurantID
                INNER JOIN Franchise F
                ON Re.RestaurantName = F.RestaurantName
                INNER JOIN Address A
                ON L.LocationID = A.LocationID
                WHERE R.Rating BETWEEN {$low} AND {$high}";
 
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }
	
	// Price Range Query	
function queryPrice($range)
    {
        $conn = connect();
 
        $sql = "SELECT L.Latitude, L.Longitude, Re.RestaurantName, R.Rating, F.PriceRange, A.*
                FROM Location L
                INNER JOIN Restaurants Re
                ON L.LocationID = Re.LocationID
                INNER JOIN Ratings R
                ON Re.RestaurantID = R.RestaurantID
                INNER JOIN Franchise F
                ON Re.RestaurantName = F.RestaurantName
                INNER JOIN Address A
                ON L.LocationID = A.LocationID
                WHERE F.PriceRange = '{$range}'";
				
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }
	
	// Radius, Ratings and Price Query
function queryRadiusRatingsPrice($latitude, $longitude, $radius, $low, $high, $range)
    {
        $conn = connect();
 
        $sql = "DECLARE @point geography = geography::Point( {$latitude} , {$longitude}, 4326);
                DECLARE @radius float = {$radius};
                SELECT L.Latitude, L.Longitude, Re.RestaurantName, R.Rating, F.PriceRange, A.*
                FROM Location L
                INNER JOIN Restaurants Re
                ON L.LocationID = Re.LocationID
                INNER JOIN Ratings R
                ON Re.RestaurantID = R.RestaurantID
                INNER JOIN Franchise F
                ON Re.RestaurantName = F.RestaurantName
                INNER JOIN Address A
                ON L.LocationID = A.LocationID
                WHERE @point.STDistance(L.GeoLocation) <= @radius*1000
				AND R.Rating BETWEEN {$low} AND {$high}
                AND F.PriceRange = '{$range}'";
				 
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }
	
	// Radius and Ratings Query
function queryRadiusRatings($latitude, $longitude, $radius, $low, $high)
    {
        $conn = connect();
 
        $sql = "DECLARE @point geography = geography::Point( {$latitude} , {$longitude}, 4326);
                DECLARE @radius float = {$radius};
                SELECT L.Latitude, L.Longitude, Re.RestaurantName, R.Rating, F.PriceRange, A.*
                FROM Location L
                INNER JOIN Restaurants Re
                ON L.LocationID = Re.LocationID
                INNER JOIN Ratings R
                ON Re.RestaurantID = R.RestaurantID
                INNER JOIN Franchise F
                ON Re.RestaurantName = F.RestaurantName
                INNER JOIN Address A
                ON L.LocationID = A.LocationID
                WHERE @point.STDistance(L.GeoLocation) <= @radius*1000
				AND R.Rating BETWEEN {$low} AND {$high}";
				 
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }	
	
	// Radius and Price Query
function queryRadiusPrice($latitude, $longitude, $radius, $range)
    {
        $conn = connect();
 
        $sql = "DECLARE @point geography = geography::Point( {$latitude} , {$longitude}, 4326);
                DECLARE @radius float = {$radius};
                SELECT L.Latitude, L.Longitude, Re.RestaurantName, R.Rating, F.PriceRange, A.*
                FROM Location L
                INNER JOIN Restaurants Re
                ON L.LocationID = Re.LocationID
                INNER JOIN Ratings R
                ON Re.RestaurantID = R.RestaurantID
                INNER JOIN Franchise F
                ON Re.RestaurantName = F.RestaurantName
                INNER JOIN Address A
                ON L.LocationID = A.LocationID
                WHERE @point.STDistance(L.GeoLocation) <= @radius*1000
                AND F.PriceRange = '{$range}'";
				 
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }
	
	// Ratings and Price Query
    function queryRatingsPrice($low, $high, $range)
    {
        $conn = connect();
 
        $sql = "SELECT L.Latitude, L.Longitude, Re.RestaurantName, R.Rating, F.PriceRange, A.*
                FROM Location L
                INNER JOIN Restaurants Re
                ON L.LocationID = Re.LocationID
                INNER JOIN Ratings R
                ON Re.RestaurantID = R.RestaurantID
                INNER JOIN Franchise F
                ON Re.RestaurantName = F.RestaurantName
                INNER JOIN Address A
                ON L.LocationID = A.LocationID
				WHERE R.Rating BETWEEN {$low} AND {$high}
                AND F.PriceRange = '{$range}'";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }
    
    $lat=$_REQUEST["lat"];
    $long=$_REQUEST["long"];
    $radius=$_REQUEST["radius"];
    $rating=$_REQUEST["rating"];
    $price=$_REQUEST["price"];

    
    $ratingLowerBounds = array('1' => '95', '2' => '90', '3' => '85', '4' => '80', '5' => '75', '6' => '70', '7' => '0' );
    $ratingUpperBounds = array('1' => '100', '2' => '94', '3' => '89', '4' => '84', '5' => '79', '6' => '74', '7' => '70' );
    $priceStrings = array('1' => '$', '2' => '$$', '3' => '$$$', '4' => '$$$$');
    

    /*
    * Execute queries
    */
    // Radius Query
    
    if($radius != "1010" && $rating == "1010" && $price == "1010")
    {
        $list = queryRadius($lat, $long, $radius);
    }

    // Ratings Query	
    if($radius == "1010" && $rating != "1010" && $price == "1010")
    {
        $list = queryRatings($ratingLowerBounds[$rating], $ratingUpperBounds[$rating]);
    }

	// Price Range Query
    if($radius == "1010" && $rating == "1010" && $price != "1010")
    {
        $list = queryPrice($priceStrings[$price]);
    }

	// Radius, Ratings and Price Query
	if($radius != "1010" && $rating != "1010" && $price != "1010")
    {
        $list = queryRadiusRatingsPrice($lat, $long, $radius, $ratingLowerBounds[$rating], $ratingUpperBounds[$rating], $priceStrings[$price]);
    }
	
	// Radius and Ratings Query
	if($radius != "1010" && $rating != "1010" && $price == "1010")
    {
        $list = queryRadiusRatings($lat, $long, $radius, $ratingLowerBounds[$rating], $ratingUpperBounds[$rating]);
    }
    
	// Radius and Price Query
    if($radius != "1010" && $rating == "1010" && $price != "1010")
    {
        $list = queryRadiusPrice($lat, $long, $radius, $priceStrings[$price]);
    }

	// Ratings and Price Query
    if($radius == "1010" && $rating != "1010" && $price != "1010")
    {
        $list = queryRatingsPrice($ratingLowerBounds[$rating], $ratingUpperBounds[$rating], $priceStrings[$price]);
    }

    echo json_encode($list);

?>


