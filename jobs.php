<html lang="en">
	<head>
		<title>Jobs</title>
		<meta charset="utf-8"/>
		<link href='http://fonts.googleapis.com/css?family=Kurale' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="style.css" type="text/css"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>
	<body>
		<header class="mainheader">
			<nav><ul>
				<li><a href="index.html">Home</a></li>
				<li><a href="projects.html">Projects</a></li>
				<li><a href="#">Your Projects</a></li>
				<li><a href="#">Groups</a></li>
				<li><a href="#">Users</a></li>
				<li class="active"><a href="jobs.php">Jobs</a></li>
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
						<h2><a href="#" title="Jobs Available">Jobs</a></h2>
					</header>
					
					<content>
					
						<?php
							
							$SHOW = $_GET["p"];
							$PAY_TYPE = $_GET["t"];
							$PAY_TYPES = ["NON", "PWH", "OTP", "PLC", "PTS", "VOL", "POA"];
							$ORDER = $_GET["o"];
							$ACTIVE = $_GET["a"];
							
							$conf = parse_ini_file('../../config.ini');

							$conn = mysql_connect($conf["host"], $conf["user"], $conf["password"]);
								
							if(! $conn ) {
								die('An  Error Occurred when connecting to us<br>It has the code : ' . mysql_errno() . "<br>Please try again, or if this error persists please contact us.");
							}
							mysql_select_db($conf["database"]);
							
							$sql = "SELECT * FROM JOBS";
							
							$sql_params = [];
							
							if($SHOW == "p"){
								array_push($sql_params, "PAY > 0.00");
							} elseif($SHOW == "u"){
								array_push($sql_params, "PAY = 0.00");
							}else{
								$sql = "SELECT * FROM JOBS";
							}
							
							if(in_array($PAY_TYPE, $PAY_TYPES)){
								array_push($sql_params, "PAY_TYPE = " . "'" .$PAY_TYPE . "'");
							}else{
								$sql = "SELECT * FROM JOBS";
							}
							
							if($ACTIVE == "a"){
								array_push($sql_params, "OPEN = 1");
							}elseif($ACTIVE == "i"){
								array_push($sql_params, "OPEN = 0");
							}elseif($ACTIVE == "b"){
								$sql = "SELECT * FROM JOBS";
							}else{
								array_push($sql_params, "OPEN = 1");
							}
							
							if(count($sql_params) > 0){
								$sql = $sql . " WHERE";
								$sql = $sql . " " . $sql_params[0];
								if(count($sql_params) > 1){
									unset($sql_params[0]);
									foreach($sql_params as $param){
										$sql = $sql . " AND " . $param;
									}
								}
							}
							
							/*
							To show what the MySQL Query Will Look Like It will echo under the Header (Very visible) User should never see this
							echo $sql;*/
							
							$result = mysql_query($sql);
							
														
							$jobs = [];
							
							while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
								array_push($jobs, $row);
							}
														
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
							<h6>Search Results : <?php echo count($jobs);?></h6>
							
							<div style="display: none;" id="mainDiv">
								<div class="filter-col">
									<ul>
										<lh>Sort By</lh>
										<li><a href="javascript:void(0);" onclick="UpdateURL('o','n')">Newest</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('o','o')">Oldest</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('o','a')">A-Z</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('o','z')">Z-A</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('o','m')">Most Applicants</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('o','l')">Least Applicants</a></li>
									</ul>
								</div>
								
								<div class="filter-col">
									<ul>
										<lh>Pay</lh>
										<li><a href="javascript:void(0);" onclick="UpdateURL('p','p')">Paid</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('p','u')">Unpaid</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('p','a')">Both</a></li>
									</ul>
								</div>
								
								<div class="filter-col">
									<ul>
										<lh><a href="payment_info.html" title="More Info On Payment Types">Pay Type</a></lh>
										<li><a href="javascript:void(0);" onclick="UpdateURL('t','NON')">No Pay</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('t','PWH')">Per Hour</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('t','OTP')">One Time</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('t','PCL')">Per Line Of Code</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('t','PTS')">Per Job/Task</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('t','VOL')">Voluntary Payment</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('t','POA')">Price On Application</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('t','ANY')">Any</a></li>
									</ul>
								</div>
								
								<div class="filter-col">
									<ul>
										<lh>Status</lh>
										<li><a href="javascript:void(0);" onclick="UpdateURL('a','a')">Active</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('a','i')">Inactive</a></li>
										<li><a href="javascript:void(0);" onclick="UpdateURL('a','b')">Both</a></li>
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
								window.location.href = "http://192.168.1.102/Unknown/jobs.php";

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
									$times[$key] = strtotime($data[10]);
								}
								return $times;
							}
							
							function getViews($rawData){
								$views = array();
								foreach ($rawData as $key => $data){
									$views[$key] = $data[6];
								}
								return $views;
							}
							
							#SETTING ORDERS
							if($ORDER == "a"){
								$titles = getTitles($jobs);
								array_multisort($titles, SORT_ASC , $jobs);
							} elseif($ORDER == "z"){
								$titles = getTitles($jobs);
								array_multisort($titles, SORT_DESC  , $jobs);		
							} elseif($ORDER == "n"){
								$times = getTimes($jobs);
								array_multisort($times, SORT_DESC  , $jobs);
							}elseif($ORDER == "o"){
								$times = getTimes($jobs);
								array_multisort($times, SORT_ASC  , $jobs);
							} elseif($ORDER == "m"){
								$views = getViews($jobs);
								array_multisort($views, SORT_DESC  , $jobs);
							}elseif($ORDER == "l"){
								$views = getViews($jobs);
								array_multisort($views, SORT_ASC  , $jobs);
							}
							
							
							foreach($jobs as $row){
								echo "<a class='project' href='project.html'><div style='margin-left: 0;'>";
								echo "<header>" . $row[1];
								if($row[3] > 0.00){
									echo "<span class='paid'>PAID</span>";
								}
								echo "</header>";
								echo "<footer>For Project That Needs To Be Found";
								echo "Uploaded " . $row[10] . "<br>";
								echo $row[6] . " People Have Applied For This Job";
								if($row[7] == 1){
									echo "<span class='advert'>Ad</span>";
								}

								echo "</footer>";
								echo "</div></a>";
							}
							
							mysql_close($conn);
						?>
						
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