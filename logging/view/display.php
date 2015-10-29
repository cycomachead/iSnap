<?php

include '../config.php';

?>

<!doctype html>

<html>
	
	<head>
		<meta charset="UTF-8">
		<title>View Project</title>
		<link rel="stylesheet" type="text/css" href="table.css">
		<style>
			html {
				height: 100%;
			}
			body {
				height: calc(100% - 20px);
			}
			/* Credit: http://stackoverflow.com/questions/5645986/two-column-div-layout-with-fluid-left-and-fixed-right-column */
			#wrapper {
				margin-left: 1200px;
				height: 100%;
			}
			#content {
				float: right;
				width: 100%;
				display: block;
				height: 100%;
			}
			#sidebar {
				float: left;
				width: 1180px;
				margin-left: -1200px;
				height: 100%;
			}
			#cleared {
				clear: both;
			}
		</style>
		<script type="text/javascript">
			function loadSnap(id) {
				var xhr = new XMLHttpRequest();
				xhr.onreadystatechange = function() {
					if (xhr.readyState==4 && xhr.status==200) {
						document.getElementById('snap').contentWindow.ide.droppedText(xhr.responseText);
					}
				};
				xhr.open("GET", "code.php?id=" + id, true);
				xhr.send();
			}
		</script>
	</head>
	
	<body>
		<div id="wrapper">
			<div id="sidebar">
				 <iframe id="snap" width="100%" height="100%" src="../../snap.html?assignment=view"></iframe> 
			</div>
			<div id="content">
				<div style="overflow: scroll; height: 100%;">
				<?php
					if ($enble_viewer) {
						$id = $_GET['id'];
						$assignment = $_GET['assignment'];
						
						echo "<h3>Project: $id</h3>";
						
						$mysqli = new mysqli($host, $user, $password, $db);
						if ($mysqli->connect_errno) {
							die ("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
						}
						
						$query = "SELECT id, time, message, data, code <> '' as link, sessionID FROM $table WHERE projectID='$id' AND assignmentID='$assignment'";
						$result = $mysqli->query($query); 
						if (!$result) {
							die ("Failed to retrieve data: (" . $mysqli->errno . ") " . $mysqli->error);
						}
						
						echo "<table cellspacing='0'>";
						echo "<thead><th>Time</th><th>Message</th><th>Data</th><th>Session</th></thead>";
						while($row = mysqli_fetch_array($result)) {
							
							$id = $row['id'];
							$time = $row['time'];
							$message = $row['message'];
							$data = $row['data'];
							$link = $row['link'];
							$sessionID = $row['sessionID'];
							
							$sessionID = substr($sessionID, 0, 3); 
							
							$first = $time;
							if ($link) $first = "<a href='#$id' onclick='loadSnap(\"$id\")'>$first</a>";
							
							$link = "http://www.bodurov.com/JsonFormatter/view.aspx?json=" . urlencode($data);
							
							$link_text = $data;
							$cutoff = 45;
							if ($link_text == "\"\"") $link_text = "";
							if (strlen($link_text) > $cutoff) {
								$link_text = substr($link_text, 0, $cutoff) . "...";
							}
							$link = "<a target='_blank' href='$link' title='$data'>$link_text</a>";
							
							echo "<tr><td>$first</td><td>$message</td><td>$link</td><td>$sessionID</td></tr>";
						}
						echo "</table>";
					} else {
						echo "You do not have permission to view this page";
					}
				?>
				</div>
			</div>
			<div id="cleared"></div>
		</div>
	</body>
</html>