<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>CivEx3 Logs</title>

	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Theme CSS -->
	<link href="css/clean-blog.css" rel="stylesheet">

	<!-- Calendar plugin -->
	<link rel="stylesheet" href="css/flatpickr.css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	<style>
	
		/*	Navigation bar reports.	*/
		.report-tile
		{
			float: left;
			width: 15em;
			height: 10em;
		}

	
	</style>

	<!-- jQuery -->
	<script src="js/jquery.js"></script>
	<script src="js/flatpickr.js"></script>
	
	<script src="js/materials.js"></script>

	<script>
		function wrapInput(label, type, value)
		{
			return $("<div>" + label + ": <br><input type='" + type + "' size=30 value='" + value + "'> </div>");
		}
		function wrapSelect(label)
		{
			return $("<div>" + label + ": <br><select></select> </div>");
		}
	
		function changeReport(report)
		{
			// This function is called when the user selects a report from the initial menu.
			// The navigaiton bar is hidden from view and the report parameters panel is 
			// dynamically populated with the DOM elements needed for each page.
		
			$("#nav").slideUp();
			$("#reports").slideUp("fast");
			$("#reportParams").empty();
			var rparams = document.querySelector("#reportParams");

			switch(report)
			{
				case 'Chat':
					
					// Bootstrap divides the .row into 12 .col-* classes, so .col-xs-6 is like getting two 50% columns.
					var columns = $("<div class='container'><div class='row'><div class='col-xs-6' id='col1'></div><div class='col-xs-6' id='col2'></div></div></div>");
					
					// Textbox to Filter on Message Column
					var col_message = wrapInput("Message", "input", "");
					$(col_message).find("input").click(function () {
						$(this).select();
					});

					// Textbox to Filter on Channel Column
					var col_channel = wrapInput("Channel", "input", "");
					$(col_channel).find("input").click(function () {
						$(this).select();
					});

					// Textbox to Filter on Sender Column
					var col_sender = wrapInput("Sender", "input", "");
					$(col_sender).find("input").click(function () {
						$(this).select();
					});

					// Textbox to Filter on Receiver Column
					var col_receiver = wrapInput("Receiver", "input", "");
					$(col_receiver).find("input").click(function () {
						$(this).select();
					});

					// Datetime picker to Filter on Timestamp From
					var col_timestamp = $("<div style='float: right;'>End Date/Time<br><input type='text' id='dateto' value=''></div>" +
						"<div style='float: right;'>Start Date/Time<br><input type='text' id='datefrom' value=''></div>");
					$(col_timestamp).find("#datefrom").flatpickr({enableTime: true, inline: true, time_24hr: true, allowInput: true});
					$(col_timestamp).find("#dateto").flatpickr({enableTime: true, inline: true, time_24hr: true, allowInput: true});

					var btnFilter = document.createElement("input");
					btnFilter.type = "button";
					btnFilter.value = "Search";
					
					var applyFilter = function()
					{
						var params = {
							"reportType": report
							,"col_message": $(col_message).find("input").val()
							,"col_channel": $(col_channel).find("input").val()
							,"col_sender": $(col_sender).find("input").val()
							,"col_receiver": $(col_receiver).find("input").val()
							,"col_timestamp_from": $(col_timestamp).find("#datefrom").val()
							,"col_timestamp_to": $(col_timestamp).find("#dateto").val()
						}
						loadTableData(params);
					}
					
					$(btnFilter).click(function () {
						applyFilter();
					});

					// Add the controls created above to the DOM.
					$(columns).find("#col1").append(col_message);
					$(columns).find("#col1").append(col_channel);
					$(columns).find("#col1").append(col_sender);
					$(columns).find("#col1").append(col_receiver);

					$(columns).find("#col2").append(col_timestamp);

					$(columns).find("#col1").append("<hr>");

					$(columns).find("input[type=text]").keypress(function (evt) {
						if (evt.keyCode == 13)
						{
							applyFilter();
						}
					});

					$(columns).find("#col1").append(btnFilter);
					
					$(rparams).append(columns);

				break;
				case 'Blocks / Reinforcements':
					
					// Bootstrap divides the .row into 12 .col-* classes, so .col-xs-6 is like getting two 50% columns.
					var columns = $("<div class='container'><div class='row'><div class='col-xs-6' id='col1'></div><div class='col-xs-6' id='col2'></div></div></div>");
					
					// Textbox to Filter on Player Name
					var col_playername = wrapInput("Player Name", "input", "");
					$(col_playername).find("input").click(function () {
						$(this).select();
					});

					
					// Drop-down list to select the Action Column.
					var col_action = wrapSelect("Action");
					$.ajax({
						"url": "reportData.php"
						,"method": "GET"
						,"contentType": "json"
						,"data": {"reportType": "Blocks_Action"}
						,"success": function (d)
						{
							$(col_action).find("select").append("<option></option>");
							var items = d;
							for (var i=0; i < items.length; i++)
							{
								$(col_action).find("select").append("<option>" + items[i].col_action + "</option>");
							}
						}
						,"error": function (e)
						{
							console.error("Error building Item Held Right list:", e);
						}
					});
					
					// Drop-down list to select the Item Held Right column.
					var col_item_held_left = wrapSelect("Item Held Left");

					$(col_item_held_left).find("select").append("<option></option>");
					var items = mc_materials; // Use the materials list in js/mc_materials.js
					for (var i=0; i < items.length; i++)
					{
						$(col_item_held_left).find("select").append("<option>" + items[i] + "</option>");
					}


					// Drop-down list to select the Item Held Right column.
					var col_item_held_right = wrapSelect("Item Held Right");

					$(col_item_held_right).find("select").append("<option></option>");
					var items = mc_materials; // Use the materials list in js/mc_materials.js
					for (var i=0; i < items.length; i++)
					{
						$(col_item_held_right).find("select").append("<option>" + items[i] + "</option>");
					}

					
					// Drop-down list to select the Block Type column.
					var col_block_type = wrapSelect("Block Type");

					$(col_block_type).find("select").append("<option></option>");
					var items = mc_materials; // Use the materials list in js/mc_materials.js
					for (var i=0; i < items.length; i++)
					{
						$(col_block_type).find("select").append("<option>" + items[i] + "</option>");
					}
										
					// Textbox to Filter on Block Position Column
					var col_position = wrapInput("Position", "input", "");
					$(col_position).find("input").click(function () {
						$(this).select();
					});

					// Textbox to Filter on Block Position +/- Column
					var col_position_plusminus = wrapInput("Position +/-", "", "");
					$(col_position_plusminus).find("input").click(function () {
						$(this).select();
					});

					// Textbox to Filter on Content Column
					var col_content = wrapInput("Content", "input", "");
					$(col_content).find("input").click(function () {
						$(this).select();
					});
					
					// Different on click behavior for the position field.
					$(col_position).find("input").click(function () {

						if ($(this).text().length == 0)
						{
							$(this).text("0 0 0");
						}

						$(this).select();
					});

					// Datetime picker to Filter on Timestamp From
					var col_timestamp = $("<div style='float: right;'>End Date/Time<br><input type='text' id='dateto' value=''></div>" +
						"<div style='float: left;'>Start Date/Time<br><input type='text' id='datefrom' value=''></div>");
					$(col_timestamp).find("#datefrom").flatpickr({enableTime: true, inline: true, time_24hr: true, allowInput: true});
					$(col_timestamp).find("#dateto").flatpickr({enableTime: true, inline: true, time_24hr: true, allowInput: true});

					var btnFilter = document.createElement("input");
					btnFilter.type = "button";
					btnFilter.value = "Search";
					
					var applyFilter = function()
					{
						var params = {
							"reportType": report
							,"col_playername": $(col_playername).find("input").val()
							,"col_item_held_left": $(col_item_held_left).find("select").val()
							,"col_item_held_right": $(col_item_held_right).find("select").val()
							,"col_block_type": $(col_block_type).find("select").val()
							,"col_action": $(col_action).find("select").val()
							,"col_content": $(col_content).find("input").val()
							,"col_timestamp_from": $(col_timestamp).find("#datefrom").val()
							,"col_timestamp_to": $(col_timestamp).find("#dateto").val()
						}
						loadTableData(params);
					}
					
					$(btnFilter).click(function () {
						applyFilter();
					});

					// Add the controls created above to the DOM.
					$(columns).find("#col1").append(col_playername);
					$(columns).find("#col1").append(col_action);
					$(columns).find("#col1").append(col_item_held_left);
					$(columns).find("#col1").append(col_item_held_right);
					$(columns).find("#col1").append(col_block_type);

					$(columns).find("#col2").append(col_position);
					$(columns).find("#col2").append(col_position_plusminus);
					$(columns).find("#col2").append(col_content);
					$(columns).find("#col2").append(col_timestamp);

					$(columns).find("#col1").append("<hr>");

					$(columns).find("input[type=text]").keypress(function (evt) {
						if (evt.keyCode == 13)
						{
							applyFilter();
						}
					});
					// Different on click behavior for the position field.
					$(col_position).find("input").keypress(function (evt) {
						var key = String.fromCharCode(evt.which)
						var re = /[^0-9 ]/;
						if (key.match(re))
						{
							evt.preventDefault();
						}
					});


					$(columns).find("#col1").append(btnFilter);
					
					$(rparams).append(columns);

				break;
			}
		}
		
		function buildTable(json)
		{
			// When AJAX event is successfull, display
			// the rows returned as an HTML table.

			$("#pageContainer").empty();
			json = JSON.parse(json);
			
			var table = document.createElement("table");
			table.classList.add("table");
			table.classList.add("report-table");
			
			
			for (var i=0; i<json.length; i++)
			{
				if (i == 0)
				{
					var row = document.createElement("thead");
				}
				else
				{
					var row = document.createElement("tr");
				}				
				table.appendChild(row);

				for (var key in json[i])
				{
					var td = document.createElement("td");
					td.appendChild(document.createTextNode(json[i][key]));
					row.appendChild(td);
				}
			}
			
			
			var rp = document.querySelector("#pageContainer")
			rp.appendChild(table);
			$("#reports").slideDown("slow");

		
		}
		
		function loadTableData(params)
		{
			// Start AJAX call and populate the HTML table
			// element with the resulting data.
			//
			// params argument should be a JSON object with the
			// $_GET parameters to be passed to PHP as key/value
			// pairs.
			$("#pageContainer").empty().html("<h2>Loading...</h2>");

			$.ajax({
				"url": "reportData.php"
				,"method": "GET"
				,"contentType": "json"
				,"data": params
				,"success": function (d)
				{
					buildTable(JSON.stringify(d));
				}
				,"error": function (e)
				{
					console.error("Error building chat table:", e);
				}
			});
		
		}
		
		$(document).ready(function (m) {
	
		
			$(".post-preview").click(function (m) {
				var report = $(m.currentTarget).find("h2")[0].innerText;
				
				switch (report)
				{
					case "Blocks / Reinforcements":
						changeReport(report);
						loadTableData();
					break;				
					case "Chat":
						changeReport(report);
						loadTableData();
					break;				
					case "Commands":
						$("#pageContainer").html("Command logs report.");
					break;				
					case "Entities":
						$("#pageContainer").html("Entity logs report.");
					break;				
					case "Users":
						$("#pageContainer").html("User management report.");
					break;				
				}
			});
			
		});
		
		
	</script>


</head>

<body>

	<!-- Navigation -->
	<nav class="navbar navbar-default navbar-custom navbar-fixed-top">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header page-scroll">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					Menu <i class="fa fa-bars"></i>
				</button>	
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="index.html">Home</a>
					</li>
					<li>
						<a href="login.html">Login / Register</a>
					</li>
				</ul>
			</div>
			<!-- /.navbar-collapse -->
		</div>
		<!-- /.container -->
	</nav>

	<!-- Page Header -->
	<!-- Set your background image for this header on the line below. -->
	<header class="intro-header" style="background-image: url('img/home-bg.jpg')" style="height: 2em;">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
					<div style="color: #f1f1f1; text-align: center;">
						<h1>CivEx3 Logs</h1>
					</div>
				</div>
			</div>
		</div>
	</header>

	<!-- Main Content -->
	<a name="main"></a>
	<div id="nav" class="container">
		<div class="row">
			<div class="post-preview report-tile">
				<a href="#">
					<h2 class="post-title">
						Blocks / Reinforcements
					</h2>
					<h3 class="post-subtitle">
						Reinforcement logs.
					</h3>
				</a>
				<p class="post-meta">Most recent log activity: November 30, 2016</p>
			</div>

			<div class="post-preview report-tile">
				<a href="#">
					<h2 class="post-title">
						Chat
					</h2>
					<h3 class="post-subtitle">
			Chat history.
					</h3>
				</a>
				<p class="post-meta">Most recent log activity: November 30, 2016</p>
			</div>

			<div class="post-preview report-tile">
				<a href="#">
					<h2 class="post-title">
						Commands
					</h2>
					<h3 class="post-subtitle">
			Command logs.
					</h3>
				</a>
				<p class="post-meta">Most recent log activity: November 30, 2016</p>
			</div>

			<div class="post-preview report-tile">
				<a href="#">
					<h2 class="post-title">
						Entities
					</h2>
					<h3 class="post-subtitle">
						Entity logs.
					</h3>
				</a>
				<p class="post-meta">Most recent log activity: November 30, 2016</p>
			</div>
			

			<div class="post-preview report-tile">
				<a href="#">
					<h2 class="post-title">
						Users
					</h2>
					<h3 class="post-subtitle">
						User management.
					</h3>
				</a>
				<p class="post-meta">Most recent log activity: November 30, 2016</p>
			</div>

		</div>
		
	</div>

	<div id="reports" class="container">
		<div id="reportParams">
		</div>
		<div id="pageContainer" style="overflow: scroll; padding: 0px; margin: 0px;">
			<p>
				Select a report from the menu above.
			</p>
		</div>
	</div>

	<hr>

	<!-- Footer -->
	<footer>
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
					<ul id="pageID" class="list-inline text-center">
						<li>
							<a href="#">
								<span class="fa-stack fa-lg">
									<i class="fa fa-circle fa-stack-2x"></i>
									<i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
								</span>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="fa-stack fa-lg">
									<i class="fa fa-circle fa-stack-2x"></i>
									<i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
								</span>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="fa-stack fa-lg">
									<i class="fa fa-circle fa-stack-2x"></i>
									<i class="fa fa-github fa-stack-1x fa-inverse"></i>
								</span>
							</a>
						</li>
					</ul>
					<p class="copyright text-muted">Copyright &copy; CivEx 2016</p>
				</div>
			</div>
		</div>
	</footer>

	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>

	<!-- Contact Form JavaScript -->
	<script src="js/jqBootstrapValidation.js"></script>
	<script src="js/contact_me.js"></script>

	<!-- Theme JavaScript -->
	<script src="js/clean-blog.min.js"></script>

</body>

</html>
