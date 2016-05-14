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
				<li ><a href="index.html">Home</a></li>
				<li><a href="projects.html">Projects</a></li>
				<li><a href="#">Your Projects</a></li>
				<li><a href="#">Groups</a></li>
				<li class="active"><a href="#">Users</a></li>
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
						$TYPE = $_GET["t"];
						$ID = $_GET["id"];
						$ORDER = $_GET["o"];
						
						
						
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
						
						if($TYPE == "g"){
							
							$getGroup = "SELECT * FROM GROUPS WHERE ID = \"" . $ID . "\"";
							$result = mysql_query($getGroup);
							$group_data = mysql_fetch_object($result);

							
							$getUsers = "SELECT * FROM GROUP_FOLLOWERS WHERE GROUP_ID = \"" . $ID . "\"";
							//echo $getUsers;
							$result = mysql_query($getUsers);

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
							
							$users = [];
							
							while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
								array_push($users, $row);
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
							$buffer=str_replace("%TITLE%","People Who Follow " . $group_data->TITLE,$buffer);
							echo $buffer;
							echo('<header>
										<h2><a href="#" title="Hello">People Who Follow ' . $group_data->TITLE . '</a></h2>
									</header>
									
									<content>
										<p>'. count($users) . ' People follow ' . $group_data->TITLE . '</p>
										What not have a browse at some interesting <a href="statistics.html">Statistics</a> (Not Yet Working)
									');
								
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
							<h6>Search Results : <?php echo count($users);?></h6>
							
							<div style="display: none;" id="mainDiv">
								<div class="filter-col">
									<ul>
										<lh>Sort By</lh>
										<li><a href="javascript:void(0);" onclick="UpdateURL('o','a')">A-Z</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('o','z')">Z-A</a></li>
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
							
							//print_r($users);
							
							function getNames($rawData){
								$names = array();
								foreach ($rawData as $key => $data){
									$names[$key] = getName($data[1]);
								}
								return $names;
							}
							
							
							#SETTING ORDERS
							if($ORDER == "a"){
								$names = getNames($users);
								array_multisort($names, SORT_ASC , $users);
							} elseif($ORDER == "z"){
								$names = getNames($users);
								array_multisort($names, SORT_DESC  , $users);		
							}else{
								$names = getNames($users);
								array_multisort($names, SORT_ASC , $users);
							}
							
							echo "<div class=\"peopleList\">
										<ul class=\"leftList\">";
							
							$right = array();
							$left = array();
							foreach ($users as $k => $v) {
								if ($k % 2 == 0) {
									$left[] = $v;
								}
								else {
									$right[] = $v;
								}
							}
							
							
							foreach($left as $row){							
								echo"<li class=\"profile_small\"><a href=\"profile.php?u=" . $row[1] . "\">
									<img src=\"imgs/standard_project_img.png\">
									<header>" . getName($row[1]) ."</header>
								</a></li>";
							}
							
							echo "</ul>
									<ul class=\"rightList\">";
							
							foreach($right as $row){							
								echo"<li class=\"profile_small\"><a href=\"profile.php?u=" . $row[1] . "\">
									<img src=\"imgs/standard_project_img.png\">
									<header>" . getName($row[1]) ."</header>
								</a></li>";
							}
							
							echo "
							</ul>
							</div>";
							
							
						}
						
						
						mysql_close($conn);

					?>
					
						<!--<div class="peopleList">
							<ul class="leftList">
								<li class="profile_small"><a href="profile.html">
									<img src="imgs/standard_project_img.png">
									<header>Joseph Saunders</header>
								</a></li>
								
								<li class="profile_small"><a href="profile.html">
									<img src="imgs/standard_project_img.png">
									<header>Joseph Saunders</header>
								</a></li>
								
								<li class="profile_small"><a href="profile.html">
									<img src="imgs/standard_project_img.png">
									<header>Joseph Saunders</header>
								</a></li>
							</ul>
							<ul class="rightList">
								<li class="profile_small"><a href="profile.html">
									<img src="imgs/standard_project_img.png">
									<header>Joseph Saunders</header>
								</a></li>
								
								<li class="profile_small"><a href="profile.html">
									<img src="imgs/standard_project_img.png">
									<header>Joseph Saunders</header>
								</a></li>
								
								<li class="profile_small"><a href="profile.html">
									<img src="imgs/standard_project_img.png">
									<header>Joseph Saunders</header>
								</a></li>
							</ul>
						</div>-->
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