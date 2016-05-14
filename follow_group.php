<?php
	
	echo "Running<br>";
	
	$PID = $_GET["pid"];
	$UID = $_COOKIE["ID"];
	
	
	$conf = parse_ini_file('../../config.ini');

	$conn = mysql_connect($conf["host"], $conf["user"], $conf["password"]);
		
	if(! $conn ){				
		$buffer=ob_get_contents();
		ob_end_clean();
		$buffer=str_replace("%TITLE%","ERROR " . mysql_errno() ,$buffer);
		echo $buffer;
		die('<header>
				<h2><a href="#" title="Error Occurred">Error '  . mysql_errno() . '</a></h2>
			</header>
			
			<content>
				<p>Sorry something went wrong.<br>It had the error code ' . mysql_errno() . '<br>Please try again or if this
				problem persists contact us</p>
			</content>');
	}
	
	mysql_select_db($conf["database"]);

	#IF TO == YOU 
	#TO CHECK IF THE MESSAGE IS MENT FOR YOU
	
	if($UID != ""){
		$getUser = "SELECT COOKIE, COOKIE_EXP FROM USERS WHERE ID = '"  . $UID . "' LIMIT 1";
		$userResult = mysql_query($getUser);
		$userValue = mysql_fetch_object($userResult);
		if(strtotime($userValue->COOKIE_EXP)>time()){
			if($userValue->COOKIE != $_COOKIE["CID"]){
				$buffer=ob_get_contents();
				ob_end_clean();
				$buffer=str_replace("%TITLE%","No No No Permission" ,$buffer);
				echo $buffer;
				die('<header>
				<h2><a href="#" title="Error Occurred">You Do Not Have Permission</a></h2>
				</header>
				
				<content>
					<p>You do not have permission to access that email.</p>
				</content>');
			}
		}else{
			$buffer=ob_get_contents();
			ob_end_clean();
			$buffer=str_replace("%TITLE%","No No No Permission" ,$buffer);
			echo $buffer;
			die('<header>
			<h2><a href="#" title="Error Occurred">You Do Not Have Permission</a></h2>
			</header>
			
			<content>
				<p>You do not have permission to access that email.</p>
			</content>');
		}
	}else{
		header('Location: '. "login.html");
	}
	
	
	function checkFollowSatatus($PID, $UID){
		$getCorolation = "SELECT * FROM GROUP_FOLLOWERS WHERE GROUP_ID = \""  . $PID . "\" AND USER_ID=\"". $UID . "\" LIMIT 1";
		echo $getCorolation;
		$result = mysql_query($getCorolation);
		
		if(! $result ) {
			$buffer=ob_get_contents();
			ob_end_clean();
			$buffer=str_replace("%TITLE%","ERROR " . mysql_errno() ,$buffer);
			echo $buffer;
			die('<header>
					<h2><a href="#" title="Error Occurred">Error '  . mysql_errno() . '</a></h2>
				</header>
				
				<content>
					<p>Sorry something went wrong.<br>It had the error code ' . mysql_errno() . '<br>Please try again or if this
					problem persists contact us</p>
				</content>');
		}
		if(mysql_fetch_array($result) !== false){
			return true;
		}else{
			return false;
		}
	}
	
	if(checkFollowSatatus($PID, $UID)){
		echo "Following";
		$sql = "DELETE FROM GROUP_FOLLOWERS WHERE GROUP_ID = \""  . $PID . "\" AND USER_ID=\"". $UID . "\"";
		$result = mysql_query($sql);
		if ($result === TRUE) {
			echo "Record deleted successfully";
		} else {
			echo "Error deleting record: " . mysql_errno();
		}
	}else{
		$sql = "INSERT INTO GROUP_FOLLOWERS VALUES(\""  . $PID . "\",\"". $UID . "\")";
		echo $sql;
		$result = mysql_query($sql);
		if ($result === TRUE) {
			echo "Record entered successfully";
		} else {
			echo "Error entered record: " . mysql_errno();
		}
	}
	
	header('Location: '."group.php?g=" . $PID);
	
	mysql_close($conn);
	
?>