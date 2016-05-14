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
						$GROUP_ID = $_GET["g"];
						
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
						$getGroup = "SELECT * FROM GROUPS WHERE ID = '"  . $GROUP_ID . "'";
						$result = mysql_query($getGroup);
						
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
						
						function getFollowers($SITE_ID){
							$result = mysql_query("SELECT * FROM GROUP_FOLLOWERS WHERE GROUP_ID = '" . $SITE_ID . "'");
							$FOLLOWS = mysql_num_rows($result);
							return $FOLLOWS;
						}
						
						function checkFollowSatatus($PID, $UID){
							$getCorolation = "SELECT * FROM GROUP_FOLLOWERS WHERE GROUP_ID = \""  . $PID . "\" AND USER_ID=\"". $UID . "\" LIMIT 1";
							//echo $getCorolation;
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
								return "Following";
							}else{
								return "Follow";
							}
						}
						
						$buffer=ob_get_contents();
						ob_end_clean();
						$buffer=str_replace("%TITLE%", $value->TITLE ,$buffer);
						echo $buffer;
						echo("<header>
									<h2 style=\"display: inline-block;\"><a href=\"#\" title=\"Group Title\">" . $value->TITLE . "</a></h2>
									<a href=\"follow_group.php?pid=" . $value->ID . "\" class=\"followButton\">" . checkFollowSatatus($value->ID, $_COOKIE["ID"]) . "</a>
								</header>
								
								<footer>
									<p class=\"post-info\">Last Updated " . $value->LAST_UPDATED . "</p>
								</footer>
								
								<content>
									<img src=\"imgs/standard_project_img.png\" class=\"project_logo\">
									<div class=\"projectInfo\">
										<ul>
											<li>Title : " . $value->TITLE . "</li>
											<li>Founder : <a href=\"profile.html\">" . getName($value->CREATOR) . "</a></li>
											<li>Founded On :  " . $value->CREATED . "</li>
											<li>Last Updated : ". $value->LAST_UPDATED . "</li>
											<li>People Following : <a href=\"people_list.php?t=g&id=" . $value->ID . "&o=a\">" . getFollowers($value->ID) . " People</a></li>
										</ul>
									</div>
									<p style=\"display: block; width: 100%;\">" . $value->DESCRIPTION . "<br><br></p>
									<div class=\"pages\">
										<h4>Pages</h4>
										<a href=\"http://www.github.com\" style=\"color: #000 !important; background-color:transparent !important;\"><img src=\"imgs/GitHub-Mark-64px.png\"> Test Project's GitHub Page</a><br>
										<a href=\"http://www.facebook.com\"  style=\"color: #3b5998 !important; background-color:transparent !important;\"><img src=\"imgs/facebook_icon.png\"> Test Project's Facebook Page</a><br>
										<a href=\"http://www.twitter.com\" style=\"color: #55acee !important; background-color:transparent !important;\"><img src=\"imgs/TwitterLogo.png\"> Test Project's Twitter Page</a><br>
										<a href=\"http://www.reddit.com\" style=\"color: #ff4500 !important; background-color:transparent !important;\"><img src=\"imgs/reddit.png\"> Test Project's Reddit Page</a><br>
										<a href=\"http://www.example.com\" style=\"color: #000 !important; background-color:transparent !important;\"><img src=\"imgs/webpage.png\"> Test Project's Website</a><br>
									</div>
									
									

									<table>
										<tr>
											<th>Topic</th>
											<th>Posted By</th>
											<th>Views</th>
											<th>Comments</th>
											<th>Uploaded</th>
										</tr>
										<tr class=\"hoverListItem\" onclick=\"document.location = 'index.html';\">
											<td>HTML Table</td>
											<td>Joseph Saunders</td>
											<td>675</td>
											<td>32</td>
											<td>21:18:03 02/05/2016</td>
										</tr>
										
										<tr class=\"hoverListItem\">
											<td>HTML Table</td>
											<td>Joseph Saunders</td>
											<td>675</td>
											<td>32</td>
											<td>21:18:03 02/05/2016</td>
										</tr>
									</table>
									
									<div>
										<h4>Jobs/Positions Available</h4>
										<ul>
											<li><a href=\"job.html\">Head Of API's</a></li>
										</ul>
									</div>
								</content>");
						
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