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
						<li><a href="log_out.php">Log Out</a></li>
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
						$ID = "";
						
						if(!isset($_COOKIE["ID"])) {
								$buffer=ob_get_contents();
								ob_end_clean();
								$buffer=str_replace("%TITLE%","Your Not Logged In" ,$buffer);
								echo $buffer;
								die('<header>
										<h2><a href="#" title="Error Occurred">Your Not Logged In</a></h2>
									</header>
									
									<content>
										<p>You are not logged in and so therefore cannot log out.</p>
									</content>');
						} else {
							
							$sql = "UPDATE USERS SET COOKIE=NULL, COOKIE_EXP=NULL WHERE ID='" . $_COOKIE["ID"] . "'";
							
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
										<p>Sorry something went wrong when you where trying to log out.<br>It had the error code ' . mysql_errno() . '<br>Please try again or if this
										problem persists contact us</p>
									</content>');
							}
							
							mysql_select_db($conf["database"]);
							
							$result = mysql_query($sql);
							
							if(! $result ) {
								$buffer=ob_get_contents();
								ob_end_clean();
								$buffer=str_replace("%TITLE%","ERROR " . mysql_errno() ,$buffer);
								echo $buffer;
								die('<header>
										<h2><a href="#" title="Error Occurred">Error '  . mysql_errno() . '</a></h2>
									</header>
									
									<content>
										<p>Sorry something went wrong when you where trying to log out.<br>It had the error code ' . mysql_errno() . '<br>Please try again or if this
										problem persists contact us</p>
									</content>');
							}
							
							mysql_close($conn);

							if (isset($_COOKIE['ID'])) {
								unset($_COOKIE['ID']);
								setcookie('ID', '', time() - 3600, '/'); // empty value and old timestamp
							}
							
							if (isset($_COOKIE['CID'])) {
								unset($_COOKIE['CID']);
								setcookie('CID', '', time() - 3600, '/'); // empty value and old timestamp
							}
							
							if (isset($_COOKIE['NAME'])) {
								unset($_COOKIE['NAME']);
								setcookie('NAME', '', time() - 3600, '/'); // empty value and old timestamp
							}
							
							if (isset($_COOKIE['MESSAGES'])) {
								unset($_COOKIE['MESSAGES']);
								setcookie('MESSAGES', '', time() - 3600, '/'); // empty value and old timestamp
							}
							
						}
						
						$buffer=ob_get_contents();
						ob_end_clean();
						$buffer=str_replace("%TITLE%","Bye. Bye" ,$buffer);
						echo $buffer;
						echo('<header>
								<h2><a href="#" title="Logged Out Bye,Bye">Bye, Bye Sorry To See You Go</a></h2>
							</header>
							
							<content>
								<p>Where sorry to see you go.<br>You can always log back in later.<br>Hope to see you again soon.</p>
							</content>');
						
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





