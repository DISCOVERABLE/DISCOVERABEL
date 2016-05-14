<?php
	echo "Started";
	#server info
	$conf = parse_ini_file('../../config.ini');

	$conn = mysql_connect($conf["host"], $conf["user"], $conf["password"]);
	
	
	if(! $conn ) {
      die('Could not connect: ' . mysql_error());
   }
   
	#data to go into the table
	$ID = $_GET["ID"];
	$USER_RATING = $_GET["rating"];
	mysql_select_db($conf["database"]);
	$sql = "SELECT * FROM Films WHERE ID='$ID' limit 1";
	$result = mysql_query($sql, $conn);
	$return = mysql_fetch_assoc($result);
	$RATING = $return['RATING'];
	if($RATING == ""){
		$RATING = 0;
	}
	echo "<br>" . $RATING;
	$VOTES = $return['VOTES'];
	
	if($VOTES == ""){
		$VOTES = 0;
	}
	
	echo "<br>" . $VOTES . "<br>";
	$NEW_RATING = (($RATING*$VOTES) + $USER_RATING)/($VOTES+1);
	echo $NEW_RATING . "<br>";
	
	$sql = 'UPDATE Films SET RATING = ' . $NEW_RATING . ', VOTES = ' . ($VOTES+1) .  ' WHERE ID = "' . $ID . '"';
	
	$retval = mysql_query( $sql, $conn );
   
	if(! $retval ) {
		die('Could not enter data: ' . mysql_error());
	}
	
	echo "Entered data successfully";
	
	mysql_close($conn);
?>