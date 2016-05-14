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
				<li><a href="index.html">Home</a></li>
				<li class="active"><a href="projects.html">Projects</a></li>
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
						$PROJECT_ID = $_GET["p"];
						
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
						$getProject = "SELECT * FROM PROJECTS WHERE ID = '"  . $PROJECT_ID . "'";
						$result = mysql_query($getProject);
						
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
							$getCorolation = "SELECT * FROM PROJECT_FOLLOWES WHERE PROJECT_ID = \""  . $PID . "\" AND USER_ID=\"". $UID . "\" LIMIT 1";
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
						
						echo("										
								<header>
									<h2><a href=\"#\" title=\"" . $value->TITLE . "\">" . $value->TITLE . "</a></h2>
								</header>
								
								<footer>
									<p class=\"post-info\">Last Updated At " . $value->LAST_UPDATED . "</p>
								</footer>
								
								<content>
									<img src=\"imgs/standard_project_img.png\" class=\"project_logo\">
									<div class=\"projectInfo\">
										<ul>
											<li>Title : " . $value->TITLE . "</li>
											<li>Founder : <a href=\"profile.html\">" . getName($value->CREATOR) . "</a></li>
											<li>Founded On :  " . $value->STARTED . "</li>
											<li>Last Updated : " . $value->LAST_UPDATED . "</li>
											<li>Working : <a href=\"people.html\">100 People</a></li>
											<li>Following : <a href=\"people.html\">250 People</a></li>
										</ul>
									</div>
									<p style=\"display: block; width: 100%;\">" . $value->DESCRIPTION . "<br><br></p>");
						
						if($value->GITHUB != "" or $value->FACEBOOK != "" or $value->TWITTER != "" or $value->REDDIT != "" or $value->WEBPAGE != ""){
							echo"<div class=\"pages\">
								<h4>Pages</h4>";
								
								if($value->GITHUB != ""){
									echo"<a href=\"http://www.github.com/" . $value->GITHUB . "\" style=\"color: #000 !important; background-color:transparent !important;\"><img src=\"imgs/GitHub-Mark-64px.png\"> " . $value->TITLE . "'s GitHub Page</a><br>";
								}
								
								if($value->FACEBOOK != ""){
									echo"<a href=\"http://www.facebook.com\"  style=\"color: #3b5998 !important; background-color:transparent !important;\"><img src=\"imgs/facebook_icon.png\"> " . $value->TITLE . "'s Facebook Page</a><br>";
								}
								
								if($value->TWITTER != ""){
									echo"<a href=\"http://www.twitter.com\" style=\"color: #55acee !important; background-color:transparent !important;\"><img src=\"imgs/TwitterLogo.png\"> " . $value->TITLE . "'s Twitter Page</a><br>";
								}
								
								if($value->reddit != ""){
									echo"<a href=\"http://www.reddit.com\" style=\"color: #ff4500 !important; background-color:transparent !important;\"><img src=\"imgs/reddit.png\"> " . $value->TITLE . "'s Reddit Page</a><br>";
								}
								
								if($value->PAGE != ""){
									echo"<a href=\"http://www.example.com\" style=\"color: #000 !important; background-color:transparent !important;\"><img src=\"imgs/webpage.png\"> " . $value->TITLE . "'s Website</a><br>";
								}

							echo "</div>";
						}
						
						
						$getJobs = "SELECT * FROM JOBS WHERE PROJECT = \"" . $value->ID . "\"";
						$result =  mysql_query($getJobs);
						
						$jobs = [];
						
						while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
							array_push($jobs, $row);
						}
						
						function getTimes($rawData){
							$times = array();
							foreach ($rawData as $key => $data){
								$times[$key] = strtotime($data[4]);
							}
							return $times;
						}
						
						$times = getTimes($projects);
						array_multisort($times, SORT_ASC  , $projects);
						
						
						if(count($jobs) > 0){
							echo("<div>
											<h4>Jobs/Positions Available (" . count($jobs) . ")</h4>
											<ul>
												");
												
							foreach($jobs as $row){
								echo "<li><a href=\"job.php?j=" . $row[0] . "\">" . $row[1] . "</a></li>";
							}
							
							echo("
										</ul>
									</div>
								</content>
							</article>");
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
	
	
	