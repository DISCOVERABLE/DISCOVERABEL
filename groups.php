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
						
						$ORDER = $_GET["o"];
						$PUBLIC = $_GET["p"];
						
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
									problem persists contact us!</p>
								</content>');
						}
						
						mysql_select_db($conf["database"]);
						
						if($PUBLIC == "p"){
							$getGroups = "SELECT * FROM GROUPS WHERE PUBLIC=1";
						}elseif($PUBLIC == "i"){
							$getGroups = "SELECT * FROM GROUPS WHERE PUBLIC=0";
						}else{
							$getGroups = "SELECT * FROM GROUPS";
						}
						
						$result = mysql_query($getGroups);
												
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
									problem persists contact us.</p>
								</content>');
						}
						
						$groups = [];
						
						while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
							array_push($groups, $row);
						}
						
						function getFollowers($SITE_ID){
							$result = mysql_query("SELECT * FROM GROUP_FOLLOWERS WHERE GROUP_ID = '" . $SITE_ID . "'");
							$FOLLOWS = mysql_num_rows($result);
							return $FOLLOWS;
						}
						
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
						
						$buffer=ob_get_contents();
						ob_end_clean();
						$buffer=str_replace("%TITLE%","Groups" ,$buffer);
						echo $buffer;
						echo('<header>
								<h2><a href="#" title="Groups On DISCOVERABLE">Groups</a></h2>
							</header>
							
							<content>');
							
					?>
						
					<script>
				
						function updateQueryStringParameter(uri, key, value) {
						  var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
						  var separator = uri.indexOf('?') !== -1 ? "&" : "?";
						  if (uri.match(re)) {
							return uri.replace(re, '$1' + key + "=" + value + '$2');
						  }
						  else {
							return uri + separator + key + "=" + value;
						  }
						}
						
						function UpdateURL(option, updated){
							url = "";
							url = updateQueryStringParameter(document.URL, option, updated);
							console.log(url);
							window.location.href = url;
						}
					
					</script>
					
					<div class="OptionBar">
						<button id="mainButton">
							<span>Filters</span>
							<span id="mainSpan"> &#x25BC </span>
						</button>
						<button id="resetButton">
							<span>Reset</span>
							<span id="mainSpan"> &#10006 </span>
						</button>
						<h6>Search Results : <?php echo count($groups);?></h6>
						
						<div style="display: none;" id="mainDiv">
							<div class="filter-col">
								<ul>
									<lh>Sort By</lh>
									<li><a href="javascript:void(0);" onclick="UpdateURL('o','n')">Newest</a></li>
									<li><a href="javascript:void(0);" onclick="UpdateURL('o','o')">Oldest</a></li>
									<li><a href="javascript:void(0);" onclick="UpdateURL('o','a')">A-Z</a></li>
									<li><a href="javascript:void(0);" onclick="UpdateURL('o','z')">Z-A</a></li>
									<li><a href="javascript:void(0);" onclick="UpdateURL('o','m')">Most Users</a></li>
									<li><a href="javascript:void(0);" onclick="UpdateURL('o','l')">Least Users</a></li>
									<li><a href="javascript:void(0);" onclick="UpdateURL('o','r')">Most Recently Updated</a></li>
									<li><a href="javascript:void(0);" onclick="UpdateURL('o','t')">Updated Longest Ago</a></li>
								</ul>
							</div>
							
							<div class="filter-col">
								<ul>
									<lh>Privacy Status</lh>
									<li><a href="javascript:void(0);" onclick="UpdateURL('p','p')">Public</a></li>
									<li><a href="javascript:void(0);" onclick="UpdateURL('p','i')">Private</a></li>
									<li><a href="javascript:void(0);" onclick="UpdateURL('p','a')">Both</a></li>
								</ul>
							</div>
							
						</div>
					</div>
					
					<script>
						$(document).ready(function(){
							$('[data-toggle="tooltip"]').tooltip();   
						});
						
					</script>
					
					
					
					
					<script>
						var mainButton = document.getElementById("mainButton");
						var mainDiv = document.getElementById("mainDiv");
						var mainSpan = document.getElementById("mainSpan");
						var resetButton = document.getElementById("resetButton");
						var state = "hidden";
						resetButton.style.display = "none";

						resetButton.addEventListener("click", function() {
							console.log("reset Button Has Been Hit!");
							window.location.href = "http://192.168.1.102/Unknown/groups.php";

						});
						
						mainButton.addEventListener("click", function() {
							if(state == "hidden"){
								resetButton.style.display = "inline-block";
								mainDiv.style.display = "block";
								state = "vis";
								mainSpan.innerHTML = "&#x25B2";
							} else{
								mainDiv.style.display = "none";
								resetButton.style.display = "none";
								state = "hidden";
								mainSpan.innerHTML = "&#x25BC";
							}
						});
					</script>
					
					<?php
						
						function getTitles($rawData){
							$titles = array();
							foreach ($rawData as $key => $data){
								$titles[$key] = $data[1];
							}
							return $titles;
						}
						
						function getTimes($rawData){
							$times = array();
							foreach ($rawData as $key => $data){
								$times[$key] = strtotime($data[4]);
							}
							return $times;
						}
						
						function getUpdates($rawData){
							$times = array();
							foreach ($rawData as $key => $data){
								$times[$key] = strtotime($data[5]);
							}
							return $times;
						}
						
						function getViews($rawData){
							$views = array();
							foreach ($rawData as $key => $data){
								$views[$key] = getFollowers($data[0]);
							}
							return $views;
						}
						
						#SETTING ORDERS
						if($ORDER == "a"){
							$titles = getTitles($groups);
							array_multisort($titles, SORT_ASC , $groups);
						} elseif($ORDER == "z"){
							$titles = getTitles($groups);
							array_multisort($titles, SORT_DESC  , $groups);		
						} elseif($ORDER == "n"){
							$times = getTimes($groups);
							array_multisort($times, SORT_DESC  , $groups);
						}elseif($ORDER == "o"){
							$times = getTimes($groups);
							array_multisort($times, SORT_ASC  , $groups);
						} elseif($ORDER == "m"){
							$views = getViews($groups);
							array_multisort($views, SORT_DESC  , $groups);
						}elseif($ORDER == "l"){
							$views = getViews($groups);
							array_multisort($views, SORT_ASC  , $groups);
						}elseif($ORDER == "r"){
							$views = getUpdates($groups);
							array_multisort($views, SORT_DESC  , $groups);
						}elseif($ORDER == "t"){
							$views = getUpdates($groups);
							array_multisort($views, SORT_ASC  , $groups);
						}
						
						
						
						
						foreach($groups as $row){
							echo "
								<a class=\"project\" href=\"group.php?g=" . $row[0] . "\">
								<img src=\"imgs\standard_project_img.png\">
								<div>
									<header>"  . $row[1] . "</header>
									<footer>Created By " . getName($row[3]) . " And Has " . getFollowers($row[0]) . " Followers<br>
									Created " . $row[4] . "<br>
									Last Updated " . $row[5] . "
									</footer>
								</div>
							</a>";
						}
						
						echo "</content>";
						mysql_close($conn);
						
						//echo '
							//<a class="project" href="message.php?m=' . $row[0] . '">
							//<img src="imgs/standard_project_img.png">
							//<div>
								//<header>'  . $row[5] . '</header>
								//<footer>From ' . getName($row[2]) . '<br>
								//Sent ' . $row[3] . '
								//</footer>
							//</div>
						//</a>';
				
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