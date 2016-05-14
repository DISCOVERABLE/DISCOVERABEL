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
				<li><a href="projects.php">Projects</a></li>
				<li><a href="#">Your Projects</a></li>
				<li><a href="groups.php">Groups</a></li>
				<li><a href="#">Users</a></li>
				<li><a href="jobs.php">Jobs</a></li>
				<div class="log_sign" id="login">
					<li class="signup"><a href="signup.html">Sign Up</a></li>
					<li class="login"><a href="login.html">Log In</a></li>
				</div>
				<div class="loggedin" id="loggedin">
					<h5 id="user_name"></h5>
					<ul>
						<li><a href="#" id="profile">Profile</a></li>
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
				
				document.getElementById("profile").href = "profile.php?u=" + getCookie("ID");
			}
						
		</script>
		
		<div class="mainContent">
			<div class="news">
				<article class="topContent">
				
					<?php					
						$JOB_ID = $_GET["j"];
						
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
						$getJob = "SELECT * FROM JOBS WHERE ID = '"  . $JOB_ID . "'";
						$result = mysql_query($getJob);
						
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
						
						function getName($id){
							$getUser = "SELECT NAME, SURNAME FROM USERS WHERE ID = '"  . $id . "' LIMIT 1";
							$userResult = mysql_query($getUser);
							$userValue = mysql_fetch_object($userResult);
							$FROM = $userValue->NAME . " " . $userValue->SURNAME;
							if(! $userResult ) {
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
							
							return $FROM;
						}
						
						function getProject($id){
							$getTheProject = "SELECT TITLE FROM PROJECTS WHERE ID = '"  . $id . "' LIMIT 1";
							$projectResult = mysql_query($getTheProject);
							$projectTitle = mysql_fetch_object($projectResult);
							if(! $projectResult ) {
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
							
							return $projectTitle->TITLE;
						}
						
						function getFollowers($SITE_ID){
							$result = mysql_query("SELECT * FROM GROUP_FOLLOWERS WHERE GROUP_ID = '" . $SITE_ID . "'");
							$FOLLOWS = mysql_num_rows($result);
							return $FOLLOWS;
						}
						
						$buffer=ob_get_contents();
						ob_end_clean();
						$buffer=str_replace("%TITLE%", $value->TITLE ,$buffer);
						echo $buffer;
						
						echo("										
								<header>
									<h2><a href=\"#\" title=\"" . $value->TITLE . "\">Job/Position</a></h2>
								</header>
								
								<content>
									<h4>Job Title : " . $value->TITLE . "</h4>
									<p>" . $value->DESCRIPTION . "</p>
									
									<h4 style=\"margin-bottom: 0;\">Information</h4>
									<ul style=\"margin-top: 0;\">
										<li>Project : <a href=\"project.php?p=" . $value->PROJECT . "\">" . getProject($value->PROJECT) . "</a></li>
										<li>Uploaded : " . $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $value->UPLOADED)->format('H:m:s d/m/y') . "</li>
										<li>No. Applicants : " . $value->NO_APPLICANTIONS . "</li>
										<li>Currently Active : " . $value->OPEN . "</li>
									</ul>
									
									
									<h4 style=\"margin-bottom: 0;\">Useful Skills</h4>
									<ul style=\"margin-top: 0;\">
										<li>Good Time Management</li>
										<li>Have a good understanding of PHP, Python and C</li>
									</ul>
									
									<h4 style=\"margin-bottom: 0;\">Other Information</h4>
									Paid : No<br>
									Full Time : No<br>
									For Fun : Yes<br>
									Level Of Commitment Wanted : Low
								</content>");
						
						mysql_close($conn);
					?>
				</article> 
				
				<article class="middleContent">
					<header>
						<h2><a href="#" title="What's On Offer">Apply For Position</a></h2>
					</header>
					
					<content>
						<p>If you would like to apply for a position please fill out the application below.</p>
						<form>
							Application (Only 512 Characters):
							<textarea style="resize: none; width:100%; height: 10%;" placeholder="You Application" maxlength=512></textarea>
							<input type="submit" value="Submit Application" class="submitButton">
						</form>
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