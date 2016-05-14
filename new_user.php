
<?php
	
	function generateRandomString($length = 32) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	
	#Getting Values From Forms
	$ID = generateRandomString($length = 10);
	$USERNAME = $_POST["usr"];
	$NAME = $_POST["name"];
	$MID_NAME = $_POST["mid_name"];
	$SURNAME = $_POST["lname"];
	$EMAIL = $_POST["email"];
	$RAW_PASS = $_POST["pass"];
	$DOB = $_POST["dob"];
	$PHONE = $_POST["phone"];
	$JOB = $_POST["job"];
	$GENDER = $_POST["gender"];
	$LOCATION = $_POST["location"];
	$COUNTRY = $_POST["country"];
	

	$salt = generateRandomString($length = 32);
	$joint = $salt . $RAW_PASS;
	$PASSWORD = openssl_digest($joint, 'sha512');	
	
?>

<html lang="en">
	<head>
		<title>%TITLE%</title>
		<meta charset="utf-8"/>
		<link href='http://fonts.googleapis.com/css?family=Kurale' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="style.css" type="text/css"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>
	<body>
		<header class="mainheader">
			<nav><ul>
				<li class="active"><a href="index.html">Home</a></li>
				<li><a href="projects.html">Projects</a></li>
				<li><a href="#">Your Projects</a></li>
				<li><a href="#">Groups</a></li>
				<li><a href="#">Users</a></li>
				<li><a href="jobs.php">Jobs</a></li>
				<div class="log_sign">
					<li class="signup"><a href="signup.html">Sign Up</a></li>
					<li class="login"><a href="login.html">Log In</a></li>
				</div>
			</ul></nav>
		</header>
		<div class="mainContent">
			<div class="news">
				<article class="topContent">
						
						<?php
						
													
							$conf = parse_ini_file('../../config.ini');

							$conn = mysql_connect($conf["host"], $conf["user"], $conf["password"]);
								
							if(! $conn ) {
								$buffer=ob_get_contents();
								ob_end_clean();
								$buffer=str_replace("%TITLE%","ERROR " . mysql_errno() ,$buffer);
								echo $buffer;
								die('<header>
										<h2><a href="#" title="Error Occurred">Error '  . mysql_errno() . '</a></h2>
									</header>
									
									<content>
										<p>Sorry something went wrong.<br>It had the error code ' . mysql_errno() . '<br>Please try again or if this
										problem persists contact us.</p>
								</content>');
							}
							
							mysql_select_db($conf["database"]);
							
							#Checking USERNAME
							$query = mysql_query("SELECT * FROM USERS WHERE USERNAME='".$USERNAME."'");
							if(mysql_num_rows($query) > 0){
								$buffer=ob_get_contents();
								ob_end_clean();
								$buffer=str_replace("%TITLE%","Username Already In Use" ,$buffer);
								echo $buffer;
								die('<header>
										<h2><a href="#" title="Error Occurred">Username "' . $USERNAME . '" Already In Use</a></h2>
									</header>
									
									<content>
										<p>Sorry but the username "' . $USERNAME . '" is alredy in use please go back and pick a different one.</p>
									</content>');
							}
							
							#Checking ID
							$query = mysql_query("SELECT * FROM USERS WHERE ID='".$ID."'");
							if(mysql_num_rows($query) > 0){
								while(mysql_num_rows($query) > 0){
									$ID = generateRandomString($length = 10);
									$query = mysql_query("SELECT * FROM USERS WHERE ID='".$ID."'");
								}
							}
							
							#Checking EMAIL
							$query = mysql_query("SELECT * FROM USERS WHERE EMAIL='".$EMAIL."'");
							if(mysql_num_rows($query) > 0){
								$buffer=ob_get_contents();
								ob_end_clean();
								$buffer=str_replace("%TITLE%","Email Already In Use" ,$buffer);
								echo $buffer;
								die('<header>
										<h2><a href="#" title="EmaIl In Use">Email "' . $EMAIL . '" Already In Use</a></h2>
									</header>
									
									<content>
										<p>Sorry but the email "' . $EMAIL . '" is already in use. You may already own a account. Why not see if you can <a href="login.html">log in</a>.</p>
									</content>');
							}

							
							$sql = ' INSERT INTO USERS VALUES("' . $ID .'", "'. $USERNAME . '", "'. $PASSWORD . '", "'. $salt . '", "'. $NAME . '", "'. $MID_NAME . '", "'. $SURNAME . '", "'
						   . $EMAIL . '", "'. $DOB . '", NOW(), "' . $PHONE . '", "'. $JOB . '", "'. $GENDER . '", "'. $LOCATION . '", "'. $COUNTRY . '", NULL, NULL, NULL, NULL, "")';
						   
							$retval = mysql_query( $sql, $conn );
						   
							if(! $retval ) {
								$buffer=ob_get_contents();
								ob_end_clean();
								$buffer=str_replace("%TITLE%","Welcome " . $NAME,$buffer);
								echo $buffer;
								die('<header>
										<h2><a href="#" title="Error Occurred">Error '  . mysql_errno() . '</a></h2>
									</header>
									
									<content>
										<p>Sorry something went wrong.<br>It had the error code ' . mysql_errno() . '<br>Please try again or if this
										problem persists contact us</p>
									</content>');
							}
							
							echo"<header>
										<h2><a href=\"#\" title=\"Hello\">Welcome " . $NAME . " " . $SURNAME . "</a></h2>
									</header>
									
									<content>
										<p>Welcome To DISCOVERABLE " . $NAME . " " . $MID_NAME . " " . $SURNAME . "</p>
								</content>";
							
							mysql_close($conn);
						?>
				</article>
				
			</div>
		</div>

		
		<footer class="mainFooter">
			<p>Copyright &copy; 2015 <a href="#" title="Joseph-Saunders">Joseph Saunders</a>
				<ul class="HeadList">
					<li class="HeadElement"><h4>Pages</h4>
						<ul class="subList">
							<li><a href="#">Home</a></li>
							<li><a href="#">Projects</a></li>
							<li><a href="#">Your Projects</a></li>
							<li><a href="#">Groups</a></li>
							<li><a href="#">Users</a></li>
							<li><a href="jobs.php">Jobs</a></li>
							<li><a href="#">Log In</a></li>
							<li><a href="#">Sign Up</a></li>
							<li><a href="#">Contact</a></li>
						</ul>
					</li>
					
					<li class="HeadElement"><h4>Info and T&C's</h4>
						<ul class="subList">
							<li><a href="#">EULA</a></li>
							<li><a href="#">T&C's</a></li>
							<li><a href="#">Info On Unknown&trade;</a></li>
							<li><a href="#">Copyright</a></li>
							<li><a href="#">Freedom Of Information</a></li>
						</ul>
					</li>
					
					<li class="HeadElement"><h4>Contact</h4>
						<ul class="subList">
							<li><a href="#">Website: www.example.com</a></li>
							<li><a href="#">Email: contact@example.com</a></li>
							<li><a href="#">Reddit: r/Unknown</a></li>
							<li><a href="#">Phone: 08855531345</a></li>
						</ul>
					</li>
				</ul>
			</p>
		</footer>
		
	</body>
</html>
