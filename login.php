<?php ob_start(); ?>
<html lang="en">
	<head>
		<title>%TITLE%</title>
		<meta charset="utf-8"/>
		<link href='http://fonts.googleapis.com/css?family=Kurale' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="style.css" type="text/css"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>
	<body onload="checkCookie()">

		<header class="mainheader">
			<nav><ul>
				<li class="active"><a href="index.html">Home</a></li>
				<li><a href="projects.html">Projects</a></li>
				<li><a href="#">Your Projects</a></li>
				<li><a href="#">Groups</a></li>
				<li><a href="#">Users</a></li>
				<li><a href="jobs.php">Jobs</a></li>
				<div class="log_sign" id="login">
					<li class="signup"><a href="signup.html">Sign Up</a></li>
					<li class="login"><a href="login.html">Log In</a></li>
				</div>
				<div class="loggedin" id="loggedin">
					<h5 id="user_name"></h5>
					<ul>
						<li><a href="#">Profile</a></li>
						<li><a href="#" id="messages">Messages (3)</a></li>
						<li><a href="#">Log Out</a></li>
					</ul>
				</div>
			</ul></nav>
		</header>
		
		<script>
			
			function getCookie(cname) {
				var name = cname + "=";
				var ca = document.cookie.split(';');
				for(var i = 0; i <ca.length; i++) {
					var c = ca[i];
					while (c.charAt(0)==' ') {
						c = c.substring(1);
					}
					if (c.indexOf(name) == 0) {
						return c.substring(name.length,c.length);
					}
				}
				return "";
			}
			
			function checkCookie() {
				var name=getCookie("NAME");
				if(name==""){
					document.getElementById("loggedin").style.display = "none";
				}else{
					document.getElementById("login").style.display = "none";
					document.getElementById("user_name").innerHTML = decodeURIComponent(decodeURIComponent(name)) + " (" + getCookie("MESSAGES") + ")";
					document.getElementById("messages").innerHTML = "Messages (" + getCookie("MESSAGES") + ")";
					
				}
			}
						
		</script>
		
		<div class="mainContent">
			<div class="news">
				<article class="topContent">
					<?php
						
											
						function generateRandomString($length = 128) {
							$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';
							$charactersLength = strlen($characters);
							$randomString = '';
							for ($i = 0; $i < $length; $i++) {
								$randomString .= $characters[rand(0, $charactersLength - 1)];
							}
							return $randomString;
						}
					
						$USER_PASS = $_POST["pass"];
						$USER_USERNAME = $_POST["usr"];
						$USER_LOGGED = $_POST["keep_logged"];
						
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
						$getID = "SELECT ID FROM USERS WHERE USERNAME='".$USER_USERNAME."' LIMIT 1";
						$result = mysql_query($getID);
						
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
						
						$value = mysql_fetch_object($result);
						$USER_ID = $value->ID;
						if($USER_ID == ""){
							$getID = "SELECT ID FROM USERS WHERE EMAIL='".$USER_USERNAME."' LIMIT 1";
							$result = mysql_query($getID);
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
							$value = mysql_fetch_object($result);
							$USER_ID = $value->ID;
						}
						
						#getting Salt
						$getSalt = "SELECT SALT FROM USERS WHERE ID='".$USER_ID."' LIMIT 1";
						$result = mysql_query($getSalt);
						$value = mysql_fetch_object($result);
						$USER_SALT = $value->SALT;
						
						#Getting Password
						$getPass = "SELECT PASSWORD FROM USERS WHERE ID='".$USER_ID."' LIMIT 1";
						$result = mysql_query($getPass);
						$value = mysql_fetch_object($result);
						$USER_PASSWORD = $value->PASSWORD;
						
						
						$joint = $USER_SALT . $USER_PASS;
						
						$PASSWORD = openssl_digest($joint, 'sha512');
						
						if($PASSWORD == $USER_PASSWORD){
							$data = "SELECT * FROM USERS WHERE ID='".$USER_ID."' LIMIT 1";
							$result = mysql_query($data);
							$value = mysql_fetch_object($result);
							
							$buffer=ob_get_contents();
							ob_end_clean();
							$buffer=str_replace("%TITLE%","Welcome " . $value->NAME ,$buffer);
							echo $buffer;
							echo '<header>
									<h2><a href="#" title="Welcome Success">Welcome '  .  $value->NAME . " " . $value->MID_NAME . " " . $value->SURNAME . '</a></h2>
								</header>
								
								<content>
									<p>Welcome ' .  $value->NAME . " " . $value->MID_NAME . " " . $value->SURNAME . '<br> Now you&#39;ve logged in you can access loads more stuff on DISCOVERABLE&#153;. Have a look round and enjoy.</p>
								</content>';
							
							$CID = generateRandomString();
							
							if($USER_LOGGED == true){
								$NAME = rawurlencode($value->NAME . " " . $value->SURNAME);
								setcookie("ID", $value->ID, time() + (30*24*60*60), "/"); #Expires In 30 Days
								setcookie("CID", $CID, time() + (30*24*60*60), "/");#Expires In 30 Days
								setcookie ("NAME", $NAME, time() + (30*24*60*60), "/");#Expires In 30 Days
								setcookie("MESSAGES", 0, time() + (30*24*60*60), "/");#Expires In 30 Days
								
								$sql = 'UPDATE USERS SET COOKIE_EXP = NOW() + INTERVAL 30 DAY, COOKIE = "' . $CID . '" WHERE ID="' . $value->ID . '"';
								$retval = mysql_query($sql);
								
							}else{
								$NAME = rawurlencode($value->NAME . " " . $value->SURNAME);
								setcookie("ID", $value->ID, 0, "/");#Expires A End Of Session
								setcookie("CID", $CID, 0, "/");#Expires A End Of Session
								setcookie ("NAME", $NAME, 0, "/");#Expires A End Of Session
								setcookie("MESSAGES", 0, 0, "/");#Expires A End Of Session
								
								#afk693SAwIf3Cc0ebbIfAv_QxURV9xVkMeqVihtP_JSBVSP42wkC0irxbgrlOkFyz4sRmWEkDuWxlJBndW_ddqLoHaJtvn02st
								
								$sql = 'UPDATE USERS SET COOKIE_EXP = NOW() + INTERVAL 1 DAY, COOKIE = "' . $CID . '" WHERE ID="' . $value->ID . '"';
								$retval = mysql_query($sql);
								if(! $retval ) {
									die('<header>
											<h2><a href="#" title="Error Occurred">Error '  . mysql_errno() . '</a></h2>
										</header>
										
										<content>
											<p>Sorry something went wrong.<br>It had the error code ' . mysql_error() . '<br>Please try again or if this
											problem persists contact us</p>
										</content>');
								} 
							}
							
						}else{
							$buffer=ob_get_contents();
							ob_end_clean();
							$buffer=str_replace("%TITLE%","Welcome " . $value->NAME ,$buffer);
							echo $buffer;
							echo '<header>
									<h2><a href="#" title="Incorrect Password">Incorrect Password, Email or Username</a></h2>
								</header>
								
								<content>
									<p>You imputed something wrong.</p>
								</content>';
						}
						
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

