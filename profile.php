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
						<li><a href="messages.php" id="messages">Messages (3)</a></li>
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
						$LOOKUP_USER_ID = $_GET["u"];
						$USER_ID = $_COOKIE["ID"];
						
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
						$getUSER = "SELECT * FROM USERS WHERE ID='" . $LOOKUP_USER_ID . "'";
						$result = mysql_query($getUSER);
						
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
						
						if($value->ID != ""){
							$buffer=ob_get_contents();
							ob_end_clean();
							$buffer=str_replace("%TITLE%", $value->NAME . " " . $value->SURNAME, $buffer);
							echo $buffer;
							
							echo '<header>
										<h2 style="display:inline-block;"><a href="#" title="Name Of User">' . $value->NAME . " " . $value->MID_NAME . " " . $value->SURNAME . '</a></h2>
										<a href="#" class="followButton">Follow</a>
									</header>';
							
							$GENDERS = array("M"=>"Male",
							"F"=>"Female",
							"O"=>"Other");
							
							$result = mysql_query("SELECT * FROM USER_FOLLOWS WHERE FOLLOWED_ID = '" . $value->ID . "'");
							$FOLLOWED = mysql_num_rows($result);
							
							$result = mysql_query("SELECT * FROM USER_FOLLOWS WHERE USER_ID = '" . $value->ID . "'");
							$FOLLOWS = mysql_num_rows($result);
							
							echo "<content>
									<img src=\"imgs/standard_project_img.png\" class=\"project_logo\">
									<div class=\"projectInfo\">
										<ul>
											<li>Name : " . $value->NAME . " " . $value->MID_NAME . " " . $value->SURNAME . "</li>
											<li>User-Name : " . $value->USERNAME . "</li>
											<li>Joined : " . $value->JOINED . "</li>
											<li>Gender : " . $GENDERS[$value->GENDER] . "</li>
											<li>DOB : " . $value->DOB . "</li>
											<li>Location : <a href=\"https://www.google.co.uk/maps/search/" . $value->LOCATION . "+" . $value->COUNTRY . "\">" . $value->LOCATION . ", " . $value->COUNTRY . "</a></li>
											<li>Day Job : " . $value->JOB . "</li>
											<li>Working On : <a href=\"#\">1 Project</a></li>
											<li>Following : <a href=\"#\">" . $FOLLOWS . " People</a></li>
											<li>Followed By : <a href=\"#\">" . $FOLLOWED . " People</a></li>
										</ul>
									</div>
									<p style=\"display: block; width: 100%;\">" . $value->DESCRIPTION . "</p>
									<div class=\"pages\">
										<h4 style=\"display:block;\">Pages</h4>
										<a href=\"http://www.github.com\" style=\"color: #000 !important; background-color:transparent !important;\"><img src=\"imgs/GitHub-Mark-64px.png\"><span>" . $value->NAME . "'s GitHub Profile</span></a><br>
										<a href=\"http://www.facebook.com\"  style=\"color: #3b5998 !important; background-color:transparent !important;\"><img src=\"imgs/facebook_icon.png\">" . $value->NAME . "'s Facebook Profile</a><br>
										<a href=\"http://www.twitter.com\" style=\"color: #55acee !important; background-color:transparent !important;\"><img src=\"imgs/TwitterLogo.png\">" . $value->NAME . "'s Twitter Profile</a><br>
										<a href=\"http://www.reddit.com\" style=\"color: #ff4500 !important; background-color:transparent !important;\"><img src=\"imgs/reddit.png\">" . $value->NAME . "'s Reddit Profile</a><br>
									</div>
								</content>";
						}else{
							$buffer=ob_get_contents();
							ob_end_clean();
							$buffer=str_replace("%TITLE%", "No User Found", $buffer);
							echo $buffer;
							
							echo '<header>
										<h2 style="display:inline-block;"><a href="#" title="No User Found">No User Found</a></h2>
									</header>';
							
							echo "<content>
										<p>Well this is awkward,<br>There not here at the moment sorry. You can try again later but they still may not be in. You may even have the wrong person.</p>
									</content>";
						}
						
						mysql_close($conn);

						
					?>
					
					<!--<header>
						<h2 style="display:inline-block;"><a href="#" title="Hello">Joseph Saunders</a></h2>
						<a href="#" class="followButton">Follow</a>
					</header>
					
					<content>
						<img src="imgs/standard_project_img.png" class="project_logo">
						<div class="projectInfo">
							<ul>
								<li>Name : Joseph Henry Hunter Dawson Saunders</li>
								<li>User-Name : BigBoss9</li>
								<li>Joined : 05/04/2016 At 12:56:17</li>
								<li>Gender : Male</li>
								<li>DOB : 30 November 1999</li>
								<li>Location : London, England</li>
								<li>Day Job : Collage</li>
								<li>Working On : <a href="#">1 Project</a></li>
								<li>Following : <a href="#">10 People</a></li>
								<li>Followed By : <a href="#">53 People</a></li>
							</ul>
						</div>
						<p style="display: block; width: 100%;">Im Joseph;<br>I'm in colage studying Computer Science with Physics and Maths at the University of Bristol.</p>
						<div class="pages">
							<h4 style="display:block;">Pages</h4>
							<a href="http://www.github.com" style="color: #000 !important; background-color:transparent !important;"><img src="imgs/GitHub-Mark-64px.png">Joseph's GitHub Profile</a><br>
							<a href="http://www.facebook.com"  style="color: #3b5998 !important; background-color:transparent !important;"><img src="imgs/Facebook_Icon.png">Joseph's Facebook Profile</a><br>
							<a href="http://www.twitter.com" style="color: #55acee !important; background-color:transparent !important;"><img src="imgs/TwitterLogo.png">Joseph's Twitter Profile</a><br>
							<a href="http://www.reddit.com" style="color: #ff4500 !important; background-color:transparent !important;"><img src="imgs/reddit.png">Joseph's Reddit Profile</a><br>
						</div>
					</content>-->
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