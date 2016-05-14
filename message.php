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
						
						$MESSAGE_ID = $_GET["m"];
						
						$WEB_USER_ID = $_COOKIE["ID"];
						#$USER_PASS = $_POST["pass"];
						#$USER_USERNAME = $_POST["usr"];
						#$USER_LOGGED = $_POST["keep_logged"];
						
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
						$getID = "SELECT TO_ FROM MESSAGES WHERE ID = '"  . $MESSAGE_ID . "' LIMIT 1";
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
						$USER_ID = $value->TO_;
						
						#IF TO == YOU 
						#TO CHECK IF THE MESSAGE IS MENT FOR YOU
						
						if($WEB_USER_ID != ""){
							if($WEB_USER_ID == $USER_ID){
								$getUser = "SELECT COOKIE, COOKIE_EXP FROM USERS WHERE ID = '"  . $USER_ID . "' LIMIT 1";
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
						
						
						$getMessage = "SELECT * FROM MESSAGES WHERE ID = '"  . $MESSAGE_ID . "' LIMIT 1";
						$result = mysql_query($getMessage);
						$value = mysql_fetch_object($result);
						
						$getUser = "SELECT NAME, SURNAME FROM USERS WHERE ID = '"  . $value->FROM_ . "' LIMIT 1";
						$userResult = mysql_query($getUser);
						$userValue = mysql_fetch_object($userResult);
						$FROM = $userValue->NAME . " " . $userValue->SURNAME;
						
						
						$buffer=ob_get_contents();
						ob_end_clean();
						$buffer=str_replace("%TITLE%","Message From " . $userValue->NAME ,$buffer);
						echo $buffer;
						
						
						/* New Format With Reply (R), Forward(F), Delete(D)
						
							<header>
								<h2 style="
									display: inline;
								">Message From <a href="#" title="Error Occurred">Andrew Saunders</a></h2>
								<div style="
									display: inline-block;
									float: right;
								">
								  <a href="#">R</a>
								  <a href="#">F</a>
								  <a href="#">D</a>
								</div><h2>About Hello</h2>									
							</header>

						*/
						
						echo('<header>
								<h2>Message From <a href="#" title="Error Occurred">' . $FROM . '</a></h2>
								<h2>About ' . $value->SUBJECT . '</h2>									
							</header>
							
							<footer>
								<p class="post-info">To You<br>
								Sent ' .  $value->SENT . '
								</p>
							</footer>
							
							<content>
								<p>' . $value->SUBJECT . '</p>
							</content>');
						
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



