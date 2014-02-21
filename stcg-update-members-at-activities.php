<?php
include_once 'header.php';

/*********************** CALL GET LOCATIONS, INTERESTS AND LANGUAGES ********************/
$connectionObject = connect();

$events = getJSONAllEventDetails($connectionObject);
$jsonEvents = json_decode($events, true);
?>

<script type="text/javascript">
var memId = "";
var actId ="";
var memChosen = "";
var actChosen = "";

$(document).ready(function ()
{
	$("#event").prepend("<option label='Please choose ...' selected='selected'></option>");
	$("#activity").prepend("<option label='Please choose ...' selected='selected'></option>");
	$("#member").prepend("<option label='Please choose ...' selected='selected'></option>");
	$("#send").hide();


	$("#event").change (function ()
	{
		//get the selected value
	    var eventId = $(this).val();
	    alert(eventId);
	    var selectAct = $("#activity");
	    $.ajax({
	        dataType: 'json',
	        type: 'GET',
            url: 'stcg-json-responses.php?fct=getJSONAllActivitiesAtEvent',
            data: "eventId="+eventId,
    		cache: false,
 			success: function(data)
			{
 				$("#activity").empty();
 				$("#activity").prepend("<option label='Please choose ...' selected='selected'></option>");
				if ($.isArray(data))
				{
					$.each(data, function (index, value)
		    	    {
						// Loop through your results in jQuery and modify your HTML elements
						selectAct.append($('<option></option>',
						{
					        value: value['activity_id'],
					        text : value['activity_name']
					    }));
			        });
				}
			},
    		error: function(xhr, status, error)
	        {
				alert("Data not sent!");
	            //console.log("Data not sent!");
	        }
	    });
	});

	$("#activity").change (function ()
	{
		var selectMem = $("#member");
		//actId = $(this).val();
		actId = $("#activity").find(":selected").val();
		actChosen = $("#activity").find(":selected").text();
		alert(actId + " " + actChosen);
		$.ajax({
	        dataType: 'json',
	        type: 'GET',
	        url: 'stcg-json-responses.php?fct=getJSONAllMembers',
			cache: false,
			success: function(data)
			{
				$("#member").empty();
				$("#member").prepend("<option label='Please choose ...' selected='selected'></option>");
				if ($.isArray(data))
				{
					$.each(data, function (index, value)
		    	    {
						// Loop through your results in jQuery and modify your HTML elements
						selectMem.append($('<option></option>',
						{
							value: value['id'],
					        text : value['name_first'] + " "  + value['name_last']
				    	}));
		    	    });
				}
			},
			error: function(xhr, status, error)
	        {
				alert("Data not sent!");
	            //console.log("Data not sent!");
	        }
	    });
	});

	$("#member").change (function ()
	{
		memId = $("#member").find(":selected").val();
		memChosen = $("#member").find(":selected").text();
		alert(memId + " " + memChosen);
		$("#summary").val("Add " + memId + " " + memChosen + " to " + actId + " " + actChosen + " ?");
		$("#send").show();
	});

	$("#send").click (function ()
	{
		addMemberToActivity();
	});
});

function addMemberToActivity()
{
    	var data = "memId=" + memId +  "&actId=" + actId;
    	alert(data);
        $.ajax({
            dataType: 'json',
            url: 'stcg-json-responses.php?fct=addMemberToActivitySelected',
            data: data,
    		cache: false,
    		success: myCallback,
    		error: myCallbackError
    	});
}
//////////CALLBACK - SUCCESS /////////////////////////
function myCallback(response)
{
//ajax call returns one or more rows inserted
	if (response > 0)
	{
		//alert("Your change was added to the database successfully!");
		$("#summary").val('');
		window.top.location="http://www.servethecitygeneva.ch/index.php?page_id=3367";
	}
}

////////// CALLBACK - FAILURE /////////////////////////
/*Error callback is called on http errors, but also if JSON parsing on the response fails.
This is what's probably happening if response code is 200 but you still are thrown to error callback.*/
function myCallbackError(jqXHR, textStatus, errorThrown )
//ajax call returns any kind of error
{
/*		alert("The system was unable to process your registration. Please contact postmaster@servethecitygeneva.ch so that we can solve the problem. /" +
				"Le système n'a pas pu complèter votre inscription. Veuillez contacter postmaster@servethecitygeneva.ch pour que nous puissions résoudre ce problème.\n" +
		textStatus  + " " + errorThrown);*/
		$("#summary").val('');
		alert("There was a problem: " + textStatus + " " + errorThrown);//JSON parse unexpected char in the errorThrown)
		window.top.location="http://www.servethecitygeneva.ch/index.php?page_id=3369";

}
</script>
</head>
<body>
<div style="float: left;">
<select id ="event" name="event">
<?php
foreach ($jsonEvents as $value)
{

?>
<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>

<?php
}//End loop through regions
?>
</select>
<select id ="activity" name="activity"></select>
<select id="member" name="member"></select>
<div id="summaryDiv" style="float: left;">
<input type="text" id="summary" name="summary" disabled size="100"></input>
<input id="send" name="send" type="button" value="SEND TO DATABASE"></input>
</div>
</div>