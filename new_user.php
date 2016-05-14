
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
		<title>Sign Up</title>
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
					<header>
						<h2><a href="#" title="Hello">Test Size</a></h2>
					</header>
					
					<footer>
						<p class="post-info">This Is A Post By Joseph Saunders The Creator Of This Site</p>
					</footer>
					
					<content>
						<p><?php
						
													
							$conf = parse_ini_file('../../config.ini');

							$conn = mysql_connect($conf["host"], $conf["user"], $conf["password"]);
								
							if(! $conn ) {
								die('An  Error Occurred when connecting to us<br>It has the code : ' . mysql_errno() . "<br>Please try again, or if this error persists please contact us.");
							}
							
							mysql_select_db($conf["database"]);
							
							#Checking USERNAME
							$query = mysql_query("SELECT * FROM USERS WHERE USERNAME='".$USERNAME."'");
							if(mysql_num_rows($query) > 0){
								echo $USERNAME . " as an user name is already in our system. You may already have an account is so <a href='login.html'>Log In</a>. If not please try another email.";
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
								echo $EMAIL . " as an email is already in our system. You may already have an account is so <a href='login.html'>Log In</a>. If not please try another email.";
							}

							
							$sql = ' INSERT INTO USERS VALUES("' . $ID .'", "'. $USERNAME . '", "'. $PASSWORD . '", "'. $salt . '", "'. $NAME . '", "'. $MID_NAME . '", "'. $SURNAME . '", "'
						   . $EMAIL . '", "'. $DOB . '", NOW(), "' . $PHONE . '", "'. $JOB . '", "'. $GENDER . '", "'. $LOCATION . '", "'. $COUNTRY . '")';
							
							$retval = mysql_query( $sql, $conn );
						   
							if(! $retval ) {
								die('An Unknown Error Occurred <br>It has the code : ' . mysql_errno() . "<br>Please try again, or if this error persists please contact us.");
							}
							
							echo "Welcome " . $NAME . " " . $MID_NAME . " " . $SURNAME;
							
							mysql_close($conn);
						?></p>
					</content>
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
