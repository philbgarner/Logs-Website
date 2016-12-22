/*


	ui_parameters.js


	Each of these functions is called in the changeReport() function from
	index.php.
	
	They are defined here to make it easier to organize the main file
	and allow flexible changes in future.


*/


	// Helper functions.

	function wrapInput(label, type, value)
	{
		return $("<div>" + label + ": <br><input type='" + type + "' size=30 value='" + value + "'> </div>");
	}
	function wrapSelect(label)
	{
		return $("<div>" + label + ": <br><select></select> </div>");
	}
	function optionSeries(s)
	{
		var els = "";
		for (var i = 1; i <= s; i++)
		{
			els += "<option>" + i + "</option>";
		}
		return els;
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
		
		
		for (var i=0; i<json.length - 1; i++)
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
		if (json.length > 0)
		{
			var rowCount = json[json.length - 1].row_count;
		
			var rp = document.querySelector("#pageContainer")
			rp.appendChild(table);
			$("#pageContainer").append("<span>Page:</span><select id='pageNo'>" + optionSeries(rowCount / 20 + 1) + "</select><input type='button' value='Go' />");
			$("#pageContainer input").click(function () {
				$("#reportParams input[value='Search']")[0].click();
			});
			$("#pageContainer select").change(function () {

			});
		}
		
		$("#reports").slideDown("fast");

	
	}
	
	function loadTableData(params, callback)
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
				if (callback != undefined)
					callback();
			}
			,"error": function (e)
			{
				console.error("Error building chat table:", e);
			}
		});
	
	}
	
	
	function loadUserGroupsData(id, callback)
	{
		// Start AJAX call and populate the User Groups dialog
		// with the user's current groups and the list of available
		// groups to add them to.
		//
		// params argument should be a JSON object with the
		// $_GET parameters to be passed to PHP as key/value
		// pairs.


		// Function takes array and outputs either
		// the x or the + variant of the <li> item
		var csvToLi = function(csv, currentList)
		{

			if (currentList == undefined)
				currentList = false;
				
			var el = "";
			for (var i=0; i<csv.length; i++)
			{
				if (currentList)
				{				
					el += '<li><span class="label">' + csv[i] + 
						'</span><button type="button" class="close" aria-label="Remove"><span aria-hidden="true">&cross;</span></button>' +
						'</li>';
				}
				else
				{
					el += '<li><span class="label">' + csv[i] + 
						'</span><button type="button" class="close" aria-label="Add"><span aria-hidden="true">&#10010;</span></button>' +
						'</li>';
				}
			}
			return el;
		}
		
		$.ajax({
			"url": "reportData.php"
			,"method": "GET"
			,"contentType": "json"
			,"data": {"reportType": "Users_Groups_List", "col_id": id}
			,"success": function (d)
			{
			
//					$("#userFullName").text(d[1].fname + ' ' + d[1].lname);

				if (d[1] != undefined && d[1] != null && d[1].group_list != null && d[1].group_list != undefined)
				{
					$("#currentGroups").empty().append( csvToLi(d[1].group_list.split(","), true) );
				
					var curlist = d[1].group_list.split(",");
				}
				else
				{
					$("#currentGroups").empty();
					var curlist = [];
				}

				$.ajax({
					"url": "reportData.php"
					,"method": "GET"
					,"contentType": "json"
					,"data": {"reportType": "Users_Groups_Available"}
					,"success": function (groups)
					{
						var newlist = [];
						groups = groups[0].group_list.split(",");
						for (var j=0; j<groups.length; j++)
						{
							var found = false;
							for (var i=0; i<curlist.length; i++)
							{
								if (curlist[i] == groups[j])
								{
									found = true;
									break;
								}
							}
							if (!found)
							{
								newlist.push(groups[j]);
							}
						}
						if (groups.length == 0)
							newlist = groups;
						
					
						$("#availableGroups").empty().append( csvToLi(newlist, false) );
				

						// Give the admin this group tag.
						var addTag = function ()
						{
							var cl = $(this).parent().clone();
							cl.find("span").eq(1).html("&cross;");
							cl.find("button").off("click").click(removeTag);
							$("#currentGroups").append(cl)
							$(this).parent().remove();
						}
						
						$("#availableGroups button[aria-label=Add]").click(addTag);

						// Take this group tag away from the admin.
						var removeTag = function ()
						{
							var cl = $(this).parent().clone();
							cl.find("span").eq(1).html("&#10010;");
							cl.find("button").off("click").click(addTag);
							
							$("#availableGroups").append(cl)
							$(this).parent().remove();
						}
						$("#currentGroups button[aria-label=Remove]").click(removeTag);
				
						if (callback != undefined)
							callback();
					}
					,"error": function (e)
					{
						console.error("Error building user groups view:", e);
					}
				});
						
				if (callback != undefined)
					callback();
			}
			,"error": function (e)
			{
				console.error("Error building user groups view:", e);
			}
		});
	
	}
		
	
	function loadReportGroupsData(id, callback)
	{
		// Start AJAX call and populate the User Groups dialog
		// with the user's current groups and the list of available
		// groups to add them to.
		//
		// params argument should be a JSON object with the
		// $_GET parameters to be passed to PHP as key/value
		// pairs.


		// Function takes array and outputs either
		// the x or the + variant of the <li> item
		var csvToLi = function(csv, currentList)
		{

			if (currentList == undefined)
				currentList = false;
				
			var el = "";
			for (var i=0; i<csv.length; i++)
			{
				if (currentList)
				{				
					el += '<li><span class="label">' + csv[i] + 
						'</span><button type="button" class="close" aria-label="Remove"><span aria-hidden="true">&cross;</span></button>' +
						'</li>';
				}
				else
				{
					el += '<li><span class="label">' + csv[i] + 
						'</span><button type="button" class="close" aria-label="Add"><span aria-hidden="true">&#10010;</span></button>' +
						'</li>';
				}
			}
			return el;
		}
		
		$.ajax({
			"url": "reportData.php"
			,"method": "GET"
			,"contentType": "json"
			,"data": {"reportType": "Report_Groups_List", "col_id": id}
			,"success": function (d)
			{

				if (d[1] != undefined && d[1] != null && d[1].report_groups != undefined && d[1].report_groups != null)
				{
					$("#currentReportGroups").empty().append( csvToLi(d[1].report_groups.split(","), true) );

				
					var curlist = d[1].report_groups.split(",");
				}
				else
				{
					$("#currentReportGroups").empty();
					var curlist = [];
				}

				$.ajax({
					"url": "reportData.php"
					,"method": "GET"
					,"contentType": "json"
					,"data": {"reportType": "Users_Groups_Available"}
					,"success": function (groups)
					{
						var newlist = [];
						groups = groups[0].group_list.split(",");
						for (var j=0; j<groups.length; j++)
						{
							var found = false;
							for (var i=0; i<curlist.length; i++)
							{
								if (curlist[i] == groups[j])
								{
									found = true;
									break;
								}
							}
							if (!found)
							{
								newlist.push(groups[j]);
							}
						}
						if (groups.length == 0)
							newlist = groups;
					
						$("#availableReportGroups").empty().append( csvToLi(newlist, false) );
				

						// Give the admin this group tag.
						var addRTag = function ()
						{
							var cl = $(this).parent().clone();
							cl.find("span").eq(1).html("&cross;");
							cl.find("button").off("click").click(removeRTag);
							$("#currentReportGroups").append(cl)
							$(this).parent().remove();
						}
						
						$("#availableReportGroups button[aria-label=Add]").off("click").click(addRTag);

						// Take this group tag away from the admin.
						var removeRTag = function ()
						{
							var cl = $(this).parent().clone();
							cl.find("span").eq(1).html("&#10010;");
							cl.find("button").off("click").click(addRTag);
							
							$("#availableReportGroups").append(cl)
							$(this).parent().remove();
						}
						$("#currentReportGroups button[aria-label=Remove]").off("click").click(removeRTag);
				
						if (callback != undefined)
							callback();
					}
					,"error": function (e)
					{
						console.error("Error building user groups view:", e);
					}
				});
						
				if (callback != undefined)
					callback();
			}
			,"error": function (e)
			{
				console.error("Error building user groups view:", e);
			}
		});
	
	}

	// Chat Report
	function chatParams(report, reportId)
	{
		var rparams = document.querySelector("#reportParams");

		// Bootstrap divides the .row into 12 .col-* classes, so .col-xs-6 is like getting two 50% columns.
		var columns = $("<div class='container'><div class='row'><div class='col-xs-6' id='col1'></div><div class='col-xs-6' id='col2'></div></div></div>");

		// Textbox to Filter on Message Column
		var col_message = wrapInput("Message", "input", "");

		// Textbox to Filter on Channel Column
		var col_channel = wrapInput("Channel", "input", "");

		// Textbox to Filter on Sender Column
		var col_sender = wrapInput("Sender", "input", "");

		// Textbox to Filter on Receiver Column
		var col_receiver = wrapInput("Receiver", "input", "");

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
				"reportType": reportId
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
		$(columns).find("#col1").append($("<h2>" + report + "</h2>"));

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
	}

	// Blocks / Reinforcements
	function blockParams(report, reportId)
	{
	
					
		var rparams = document.querySelector("#reportParams");
	
		// Bootstrap divides the .row into 12 .col-* classes, so .col-xs-6 is like getting two 50% columns.
		var columns = $("<div class='container'><div class='row'><div class='col-xs-6' id='col1'></div><div class='col-xs-6' id='col2'></div></div></div>");
	
		// Textbox to Filter on Player Name
		var col_playername = wrapInput("Player Name", "input", "");

	
		// Drop-down list to select the Action Column.
		var col_action = wrapSelect("Action");
		$(col_action).find("select").append("<option></option>");
		var items = mc_actions; // Use the materials list in js/mc_materials.js
		for (var i=0; i < items.length; i++)
		{
			$(col_action).find("select").append("<option>" + items[i] + "</option>");
		}
	
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

		// Textbox to Filter on Block Position +/- Column
		var col_position_plusminus = wrapInput("Position +/-", "", "");

		// Textbox to Filter on Content Column
		var col_content = wrapInput("Content", "input", "");
	
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
			// Paginating
			var pageNo = $("#pageContainer select option:selected").val();
			
			var params = {
				"reportType": reportId
				,"col_playername": $(col_playername).find("input").val()
				,"col_item_held_left": $(col_item_held_left).find("select").val()
				,"col_item_held_right": $(col_item_held_right).find("select").val()
				,"col_block_type": $(col_block_type).find("select").val()
				,"col_action": $(col_action).find("select").val()
				,"col_content": $(col_content).find("input").val()
				,"col_timestamp_from": $(col_timestamp).find("#datefrom").val()
				,"col_timestamp_to": $(col_timestamp).find("#dateto").val()
				,"page_number": pageNo
			}
			loadTableData(params, function () {
				$("#pageContainer tr").each(function (index) {
					var col = $(this).find("td").eq(7);
					$(col).text(parseFloat($(col).text()).toFixed(1));
					var col = $(this).find("td").eq(8);
					$(col).text(parseFloat($(col).text()).toFixed(1));
					var col = $(this).find("td").eq(9);
					$(col).text(parseFloat($(col).text()).toFixed(1));
				});
				$("#pageContainer select option").filter(function () { return $(this).html() == pageNo; }).attr("selected", true);
			});
		}
	
		$(btnFilter).click(function () {
			applyFilter();
		});

		// Add the controls created above to the DOM.
		$(columns).find("#col1").append($("<h2>" + report + "</h2>"));

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
		// Different on keypress behavior for the position field.
		$(col_position).find("input").keypress(function (evt) {
			var key = String.fromCharCode(evt.which)
			var re = /[^0-9 \-]/;
			if (key.match(re))
			{
				evt.preventDefault();
			}
		});


		$(columns).find("#col1").append(btnFilter);
	
		$(rparams).append(columns);

	}

	// Sessions

	function sessionsParams(report, reportId)
	{

		var rparams = document.querySelector("#reportParams");

	
		// Bootstrap divides the .row into 12 .col-* classes, so .col-xs-6 is like getting two 50% columns.
		var columns = $("<div class='container'><div class='row'><div class='col-xs-6' id='col1'></div><div class='col-xs-6' id='col2'></div></div></div>");
	
		// Textbox to Filter on Session ID Column
		var col_session = wrapInput("Session ID", "input", "");

		// Textbox to Filter on Player Name Column
		var col_player = wrapInput("Player Name", "input", "");

		// Textbox to Filter on IP Address Column
		var col_ipaddress = wrapInput("IP Address", "input", "");

		// Datetime picker to Filter on Login From/To
		var col_timestamp = $("<div style='float: right;'>Login End Date/Time<br><input type='text' id='dateto' value=''></div>" +
			"<div style='float: right;'>Login Start Date/Time<br><input type='text' id='datefrom' value=''></div>");
		$(col_timestamp).find("#datefrom").flatpickr({enableTime: true, inline: true, time_24hr: true, allowInput: true});
		$(col_timestamp).find("#dateto").flatpickr({enableTime: true, inline: true, time_24hr: true, allowInput: true});

		// Datetime picker to Filter on Logout From/To
		var col_timestamp2 = $("<div style='float: right;'>Logout End Date/Time<br><input type='text' id='datetologout' value=''></div>" +
			"<div style='float: right;'>Logout Start Date/Time<br><input type='text' id='datefromlogout' value=''></div>");
		$(col_timestamp2).find("#datefromlogout").flatpickr({enableTime: true, inline: true, time_24hr: true, allowInput: true});
		$(col_timestamp2).find("#datetologout").flatpickr({enableTime: true, inline: true, time_24hr: true, allowInput: true});

		var btnFilter = document.createElement("input");
		btnFilter.type = "button";
		btnFilter.value = "Search";
	
		var applyFilter = function()
		{
			var params = {
				"reportType": reportId
				,"col_id": $(col_session).find("input").val()
				,"col_player_name": $(col_player).find("input").val()
				,"col_ip": $(col_ipaddress).find("input").val()
				,"col_timestamp_login_from": $(col_timestamp).find("#datefrom").val()
				,"col_timestamp_login_to": $(col_timestamp).find("#dateto").val()
				,"col_timestamp_logout_from": $(col_timestamp2).find("#datefromlogout").val()
				,"col_timestamp_logout_to": $(col_timestamp2).find("#datetologout").val()
			}
			loadTableData(params);
		}
	
		$(btnFilter).click(function () {
			applyFilter();
		});

		// Add the controls created above to the DOM.
		$(columns).find("#col1").append($("<h2>" + report + "</h2>"));

		$(columns).find("#col1").append(col_session);
		$(columns).find("#col1").append(col_player);
		$(columns).find("#col1").append(col_ipaddress);

		$(columns).find("#col2").append(col_timestamp);
		$(columns).find("#col2").append(col_timestamp2);

		$(columns).find("#col1").append("<hr>");

		$(columns).find("input[type=text]").keypress(function (evt) {
			if (evt.keyCode == 13)
			{
				applyFilter();
			}
		});

		$(columns).find("#col1").append(btnFilter);
	
		$(rparams).append(columns);


	}


	// Commands

	function commandsParams(report, reportId)
	{

		var rparams = document.querySelector("#reportParams");


		// Bootstrap divides the .row into 12 .col-* classes, so .col-xs-6 is like getting two 50% columns.
		var columns = $("<div class='container'><div class='row'><div class='col-xs-6' id='col1'></div><div class='col-xs-6' id='col2'></div></div></div>");

		// Textbox to Filter on Player Name Column
		var col_player = wrapInput("Player Name", "input", "");

		// Textbox to Filter on Comand Column
		var col_command = wrapInput("Command", "input", "");

		// Textbox to Filter on Arguments Column
		var col_arguments = wrapInput("Arguments", "input", "");

		var col_cancelled = wrapSelect("Cancelled");
		$(col_cancelled).find("select").append($("<option></option><option>Yes</option><option>No</option>"));

		// Datetime picker to Filter on Timestamp
		var col_timestamp = $("<div style='float: right;'>End Date/Time<br><input type='text' id='dateto' value=''></div>" +
			"<div style='float: right;'>Start Date/Time<br><input type='text' id='datefrom' value=''></div>");
		$(col_timestamp).find("#datefrom").flatpickr({enableTime: true, inline: true, time_24hr: true, allowInput: true});
		$(col_timestamp).find("#dateto").flatpickr({enableTime: true, inline: true, time_24hr: true, allowInput: true});

		var btnFilter = document.createElement("input");
		btnFilter.type = "button";
		btnFilter.value = "Search";

		var applyFilter = function()
		{
			var cancelVal = undefined;
			if ($(col_cancelled).find("option:selected").val() == "Yes")
			{
				cancelVal = 1;
			}
			else if ($(col_cancelled).find("option:selected").val() == "No")
			{	
				cancelVal = 0;
			}

			var params = {
				"reportType": reportId
				,"col_player": $(col_player).find("input").val()
				,"col_command": $(col_command).find("input").val()
				,"col_arguments": $(col_arguments).find("input").val()
				,"col_cancelled": cancelVal
				,"col_timestamp_from": $(col_timestamp).find("#datefrom").val()
				,"col_timestamp_to": $(col_timestamp).find("#dateto").val()
			}
			loadTableData(params);
		}

		$(btnFilter).click(function () {
			applyFilter();
		});

		// Add the controls created above to the DOM.
		$(columns).find("#col1").append($("<h2>" + report + "</h2>"));

		$(columns).find("#col1").append(col_player);
		$(columns).find("#col1").append(col_command);
		$(columns).find("#col1").append(col_arguments);
		$(columns).find("#col1").append(col_cancelled);

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


	}

	function entitiesParams(report, reportId)
	{


		var rparams = document.querySelector("#reportParams");


		// Bootstrap divides the .row into 12 .col-* classes, so .col-xs-6 is like getting two 50% columns.
		var columns = $("<div class='container'><div class='row'><div class='col-xs-6' id='col1'></div><div class='col-xs-6' id='col2'></div></div></div>");

		// Drop-down list to select the Entity Type column.
		var col_entity_type = wrapSelect("Entity Type");

		$(col_entity_type).find("select").append("<option></option>");
		var items = mc_entities; // Use the entities list in js/mc_entities.js
		for (var i=0; i < items.length; i++)
		{
			$(col_entity_type).find("select").append("<option>" + items[i] + "</option>");
		}


		var col_jocky = wrapSelect("Jocky");
		$(col_jocky).find("select").append($("<option></option><option>Yes</option><option>No</option>"));


		// Textbox to Filter on Entity Location Column
		var col_entity = wrapInput("Entity Location", "input", "");

		// Different on click behavior for the entity location field.
		$(col_entity).find("input").click(function () {

			if ($(this).text().length == 0)
			{
				$(this).text("0 0 0");
			}

			$(this).select();
		});

						
		var col_cancelled = $("<div><label>Cancelled <input type='checkbox'></label></div>");

		// Datetime picker to Filter on Timestamp
		var col_timestamp = $("<div style='float: right;'>End Date/Time<br><input type='text' id='dateto' value=''></div>" +
			"<div style='float: right;'>Start Date/Time<br><input type='text' id='datefrom' value=''></div>");
		$(col_timestamp).find("#datefrom").flatpickr({enableTime: true, inline: true, time_24hr: true, allowInput: true});
		$(col_timestamp).find("#dateto").flatpickr({enableTime: true, inline: true, time_24hr: true, allowInput: true});

		var btnFilter = document.createElement("input");
		btnFilter.type = "button";
		btnFilter.value = "Search";
	
		var applyFilter = function()
		{
			var jockyVal = undefined;
			if ($(col_jocky).find("option:selected").val() == "Yes")
			{
				jockyVal = 1;
			}
			else if ($(col_jocky).find("option:selected").val() == "No")
			{	
				jockyVal = 0;
			}
		
			var eloc = $(col_entity).find("input").val().split(" ");
			var params = {
				"reportType": reportId
				,"col_entity_type": $(col_entity_type).find("option:selected").val()
				,"col_jocky": jockyVal
				,"col_x": eloc[0]
				,"col_y": eloc[1]
				,"col_z": eloc[2]
				,"col_timestamp_from": $(col_timestamp).find("#datefrom").val()
				,"col_timestamp_to": $(col_timestamp).find("#dateto").val()
			}
			loadTableData(params, function () {
				$("#pageContainer tr").each(function (index) {
					var col = $(this).find("td").eq(4);
					$(col).text(parseFloat($(col).text()).toFixed(1));
					var col = $(this).find("td").eq(5);
					$(col).text(parseFloat($(col).text()).toFixed(1));
					var col = $(this).find("td").eq(6);
					$(col).text(parseFloat($(col).text()).toFixed(1));
				});
			});
		}
	
		$(btnFilter).click(function () {
			applyFilter();
		});

		// Add the controls created above to the DOM.
		$(columns).find("#col1").append($("<h2>" + report + "</h2>"));

		$(columns).find("#col1").append(col_entity_type);
		$(columns).find("#col1").append(col_jocky);
		$(columns).find("input[type=text]").keypress(function (evt) {
			if (evt.keyCode == 13)
			{
				applyFilter();
			}
		});
	
		$(columns).find("#col1").append(col_entity);

		$(columns).find("#col2").append(col_timestamp);

		$(columns).find("#col1").append("<hr>");

		// Different on keypress behavior for the entity location field.
		$(col_entity).find("input").keypress(function (evt) {
			var key = String.fromCharCode(evt.which)
			var re = /[^0-9 \-]/;
			if (key.match(re) && 
				!(evt.keyCode == 13 || evt.keyCode == 8 || evt.keyCode == 9)
			)
			{
				evt.preventDefault();
			}
		});

		$(columns).find("#col1").append(btnFilter);
	
		$(rparams).append(columns);
				
	}

	function usersParams(report, reportId)
	{

		var rparams = document.querySelector("#reportParams");

		// Bootstrap divides the .row into 12 .col-* classes, so .col-xs-6 is like getting two 50% columns.
		var columns = $("<div class='container'><div class='row'><div class='col-xs-6' id='col1'></div><div class='col-xs-6' id='col2'></div></div></div>");

		// Textbox to Filter on Player Name Column
		var col_admin = wrapInput("Admin Name", "input", "");

		var col_groups = wrapInput("Group(s)", "input", "");

		var btnFilter = document.createElement("input");
		btnFilter.type = "button";
		btnFilter.value = "Search";
	
		var btnNewGroup = document.createElement("input");
		btnNewGroup.type = "button";
		btnNewGroup.value = "New Group";
		$(btnNewGroup).click(function () {
		
			var newname = prompt("Enter New Group Name");
		
			$.ajax({
				"url": "reportData.php"
				,"method": "GET"
				,"contentType": "json"
				,"data": {"reportType": "Report_New_Group"
					, "col_group_name": newname}
				,"success": function (d)
				{
					$(btnNewGroup).text("Saved!");
					window.setTimeout(function () { $(btnNewGroup).text("New Group"); });
				}
				,"error": function (e)
				{
					$(btnNewGroup).text("Error.");
					console.error("Error creating group:", e);
				}
			});						
		});
	
		var applyFilter = function()
		{

			var params = {
				"reportType": reportId
				,"col_admin": $(col_admin).find("input").val()
				,"col_groups": $(col_groups).find("input").val()
			}
			loadTableData(params, function () {
				$("#pageContainer tr").each(function (index) {
					var col = $(this).find("td").eq(1);
					var el = $("<img src='" + $(col).text() + "' width=64 height=64>");
					$(col).empty().append(el);
				});
				$("#pageContainer tr").each(function (index) {
					var col_oauth_id = $(this).find("td").eq(7).text();
					if (col_oauth_id == "null")
						col_oauth_id = "";
					
					$(this).off("click").click(function () {
						loadUserGroupsData($(this).find("td").eq(0).text());
						$("#currentGroups").empty().text("Loading...");
						$("#availableGroups").empty().text("Loading...");
						$("#btnSaveChanges").text("Save Changes");
						$("#btnSaveChanges").off("click").click(function () {
							var saveButton = $(this);
							saveButton.text("Saving...");
						
							var cgEl = $("#currentGroups li");
							var curGroups = "";
							for (var i=0; i<cgEl.length; i++)
							{
								curGroups += $(cgEl[i]).find("span").eq(0).text();
								if (i < cgEl.length - 1)
								{
									curGroups += ",";
								}
							}
						
							$.ajax({
								"url": "reportData.php"
								,"method": "GET"
								,"contentType": "json"
								,"data": {"reportType": "Users_Set_Groups", "col_user_oauth_id": col_oauth_id, "col_group_names": curGroups}
								,"success": function (d)
								{
									saveButton.text("Saved!");
									window.setTimeout(function () { $("#dlgUserGroups").modal("hide"); applyFilter();}, 600);
								}
								,"error": function (e)
								{
									saveButton.text("Error.");
									console.error("Error building chat table:", e);
								}
							});

						});
						$("#dlgUserGroups").modal();
					});
				});
			});
		}
	
		$(btnFilter).click(function () {
			applyFilter();
		});

		// Add the controls created above to the DOM.
		$(columns).find("#col1").append($("<h2>" + report + "</h2>"));

		$(columns).find("#col1").append(col_admin);
		$(columns).find("#col1").append(col_groups);

		$(columns).find("#col1").append("<hr>");

		$(columns).find("input[type=text]").keypress(function (evt) {
			if (evt.keyCode == 13)
			{
				applyFilter();
			}
		});

		$(columns).find("#col1").append(btnFilter);

	
		$(rparams).append(columns);
					
	}

	function reportParams(report, reportId)
	{

		var rparams = document.querySelector("#reportParams");
	
		// Bootstrap divides the .row into 12 .col-* classes, so .col-xs-6 is like getting two 50% columns.
		var columns = $("<div class='container'><div class='row'><div class='col-xs-6' id='col1'></div><div class='col-xs-6' id='col2'></div></div></div>");

		// Textbox to Filter on Player Name Column
		var col_name = wrapInput("Report Name", "input", "");

		var col_desc = wrapInput("Description", "input", "");

		var col_group = wrapInput("Group(s)", "input", "");

		var btnFilter = document.createElement("input");
		btnFilter.type = "button";
		btnFilter.value = "Search";
	
		var txtReportName = $("#reportName");
		var txtReportDesc = $("#reportDescription");

	
		var btnNewReport = document.createElement("input");
		btnNewReport.type = "button";
		btnNewReport.value = "New Report";
		$(btnNewReport).click(function () {
		
			var newname = prompt("Enter New Report Name");
		
			$.ajax({
				"url": "reportData.php"
				,"method": "GET"
				,"contentType": "json"
				,"data": {"reportType": "New_Report"
					, "col_report_name": newname}
				,"success": function (d)
				{
					$(btnNewReport).text("Saved!");
					window.setTimeout(function () { $(btnNewReport).text("New Report"); });
				}
				,"error": function (e)
				{
					$(btnNewReport).text("Error.");
					console.error("Error creating group:", e);
				}
			});						
		});
	
		var applyFilter = function()
		{

			var params = {
				"reportType": reportId
				,"col_report_name": $(col_name).find("input").val()
				,"col_report_description": $(col_desc).find("input").val()
			};
			loadTableData(params, function () {
				$("#pageContainer tr").each(function (index) {
					var col_id = $(this).find("td").eq(0).text();

					$("#reportScript").val($(this).find("td").eq(4).text());
					$("#reportName").val($(this).find("td").eq(1).text());
					$("#reportDescription").val($(this).find("td").eq(2).text());
										
					$(this).off("click").click(function () {
						loadReportGroupsData($(this).find("td").eq(0).text());
						$("#currentReportGroups").empty().text("Loading...");
						$("#availableReportGroups").empty().text("Loading...");
						$("#btnReportSaveChanges").text("Save Changes");
						$("#btnReportRemove").off("click").click(function () {
							var remButton = $(this);
							remButton.text("Removing...");
							$.ajax({
								"url": "reportData.php"
								,"method": "GET"
								,"contentType": "json"
								,"data": {"reportType": "Delete_Report", "col_id": col_id
									}
								,"success": function (d)
								{
									remButton.text("Removed!");
									window.setTimeout(function () { $("#dlgReportGroups").modal("hide"); applyFilter(); }, 600);
								}
								,"error": function (e)
								{
									remButton.text("Error.");
									console.error("Error deleting group.:", e);
								}
							});
						});
						$("#btnReportSaveChanges").off("click").click(function () {
							var saveButton = $(this);
							saveButton.text("Saving...");
						
							var cgEl = $("#currentReportGroups li");
							var curGroups = "";
							for (var i=0; i<cgEl.length; i++)
							{
								var gr = $(cgEl[i]).find("span").eq(0).text();
								curGroups += $(cgEl[i]).find("span").eq(0).text();
								if (i < cgEl.length - 1)
								{
									curGroups += ",";
								}
							}
							var reportScript  = $("#reportScript").val();
						
							$.ajax({
								"url": "reportData.php"
								,"method": "GET"
								,"contentType": "json"
								,"data": {"reportType": "Report_Set_Groups", "col_id": col_id
									, "col_group_names": curGroups
									, "col_report_filename": reportScript
									, "col_report_name": $(txtReportName).val() 
									, "col_report_description": $(txtReportDesc).val() }
								,"success": function (d)
								{
									saveButton.text("Saved!");
									window.setTimeout(function () { $("#dlgReportGroups").modal("hide"); applyFilter(); }, 600);
								}
								,"error": function (e)
								{
									saveButton.text("Error.");
									console.error("Error building Report Management tab.:", e);
								}
							});
						
						});
						$("#dlgReportGroups").modal();
					});
				});
			});
		}
	
		$(btnFilter).click(function () {
			applyFilter();
		});

	
		// Add the controls created above to the DOM.
		$(columns).find("#col1").append($("<h2>" + report + "</h2>"));

		$(columns).find("#col1").append(col_name);
		$(columns).find("#col1").append(col_desc);

		$(columns).find("#col1").append("<hr>");

		$(columns).find("input[type=text]").keypress(function (evt) {
			if (evt.keyCode == 13)
			{
				applyFilter();
			}
		});

		$(columns).find("#col1").append(btnFilter);
		$(columns).find("#col2").append(btnNewReport);
	
		$(rparams).append(columns);
	}

	function groupParams(report, reportId)
	{

		var rparams = document.querySelector("#reportParams");

		// Bootstrap divides the .row into 12 .col-* classes, so .col-xs-6 is like getting two 50% columns.
		var columns = $("<div class='container'><div class='row'><div class='col-xs-6' id='col1'></div><div class='col-xs-6' id='col2'></div></div></div>");

		// Textbox to Filter on Group Name Column
		var col_group = wrapInput("Group(s)", "input", "");

		var btnFilter = document.createElement("input");
		btnFilter.type = "button";
		btnFilter.value = "Search";

		var btnNewGroup = document.createElement("input");
		btnNewGroup.type = "button";
		btnNewGroup.value = "New Group";
	


												
		var applyFilter = function()
		{

			var params = {
				"reportType": reportId
				,"col_group_name": $(col_group).find("input").val()
			};
			loadTableData(params, function () {
				$("#pageContainer tr").each(function (index) {
					var col_id = $(this).find("td").eq(0).text();
					$("#groupName").val($(this).find("td").eq(1).text());
					var col_group_name = $("#groupName").val();
					
					$(this).off("click").click(function () {
						loadReportGroupsData($(this).find("td").eq(0).text());
					
						$("#btnGroupRemove").off("click").click(function () {
							var remButton = $(this);
							remButton.text("Removing...");
							$.ajax({
								"url": "reportData.php"
								,"method": "GET"
								,"contentType": "json"
								,"data": {"reportType": "Delete_Group", "col_id": col_id
									}
								,"success": function (d)
								{
									remButton.text("Removed!");
									window.setTimeout(function () { $("#dlgGroupSettings").modal("hide"); applyFilter(); }, 600);
								}
								,"error": function (e)
								{
									remButton.text("Error.");
									console.error("Error deleting group.:", e);
								}
							});
						});
					
						$("#btnGroupSaveChanges").text("Save Changes");
						$("#btnGroupSaveChanges").off("click").click(function () {
							var saveButton = $(this);
							saveButton.text("Saving...");

							$.ajax({
								"url": "reportData.php"
								,"method": "GET"
								,"contentType": "json"
								,"data": {"reportType": "Update_Groups", "col_id": col_id
									, "col_group_name": col_group_name
									}
								,"success": function (d)
								{
									saveButton.text("Saved!");
									window.setTimeout(function () { $("#dlgGroupSettings").modal("hide"); applyFilter(); }, 600);
								}
								,"error": function (e)
								{
									saveButton.text("Error.");
									console.error("Error building Report Management tab.:", e);
								}
							});
						});
						$("#dlgGroupSettings").modal();
					});
				});
			});
		}
	
		$(btnNewGroup).click(function () {
		
			var newname = prompt("Enter New Group Name");
		
			$.ajax({
				"url": "reportData.php"
				,"method": "GET"
				,"contentType": "json"
				,"data": {"reportType": "Users_New_Group"
					, "col_group_name": newname}
				,"success": function (d)
				{
					$(btnNewGroup).val("Saved!");
					window.setTimeout(function () { $(btnNewGroup).val("New Group"); applyFilter(); }, 600);
				}
				,"error": function (e)
				{
					$(btnNewGroup).val("Error.");
					console.error("Error creating group:", e);
				}
			});						
		});
	
		$(btnFilter).click(function () {
			applyFilter();
		});

	
		// Add the controls created above to the DOM.
		$(columns).find("#col1").append($("<h2>" + report + "</h2>"));

		$(columns).find("#col1").append(col_group);

		$(columns).find("#col1").append("<hr>");

		$(columns).find("input[type=text]").keypress(function (evt) {
			if (evt.keyCode == 13)
			{
				applyFilter();
			}
		});

		$(columns).find("#col1").append(btnFilter);

		$(columns).find("#col2").append("<hr>");
		$(columns).find("#col2").append(btnNewGroup);
						
		$(rparams).append(columns);
	
		applyFilter();
	}
	
	function historyParams(report, reportId)
	{
		var rparams = document.querySelector("#reportParams");

		// Bootstrap divides the .row into 12 .col-* classes, so .col-xs-6 is like getting two 50% columns.
		var columns = $("<div class='container'><div class='row'><div class='col-xs-6' id='col1'></div><div class='col-xs-6' id='col2'></div></div></div>");

		// Textbox to Filter on Message Column
		var col_command = wrapInput("Log Command", "input", "");

		// Textbox to Filter on Channel Column
		var col_arguments = wrapInput("Arguments", "input", "");


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
				"reportType": reportId
				,"col_command": $(col_command).find("input").val()
				,"col_arguments": $(col_arguments).find("input").val()
				,"col_timestamp_from": $(col_timestamp).find("#datefrom").val()
				,"col_timestamp_to": $(col_timestamp).find("#dateto").val()
			}
			loadTableData(params);
		}

		$(btnFilter).click(function () {
			applyFilter();
		});

		// Add the controls created above to the DOM.
		$(columns).find("#col1").append($("<h2>" + report + "</h2>"));

		$(columns).find("#col1").append(col_command);
		$(columns).find("#col1").append(col_arguments);

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
	}

