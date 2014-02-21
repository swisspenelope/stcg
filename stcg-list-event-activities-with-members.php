<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>

<script src="scripts/jquery-ui-1.10.3/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/jquery.tablesorter.js" type="text/javascript"></script>
<TITLE>Event Activities per Member</TITLE>
<link rel ="stylesheet" type="text/css" href="css/theme.css">
<link rel ="stylesheet" type="text/css" href="css/structure.css">
<link rel ="stylesheet" type="text/css" href="css/form.css">
<link rel ="stylesheet" type="text/css" href="css/themes/blue/style.css">
</HEAD>
<?php
header("Content-Type: text/html;charset=utf-8");

require_once 'stcg-utilities.php';
require_once 'stcg-data-layer.php';

/********************* CALL GET ALL EVENTS ******************************************/
$connectionObject = connect();
$event = getJSONAllEventDetails($connectionObject);
$jEvent = json_decode($event, true);
?>
<pre>
<?php
	//print_r($jEvent);
?>
</pre>
<?php

for ($i = 0; $i < count($jEvent); $i++)//outer loop for Events
{
	/* Note that array jMemEvent below starts at 0, but the Event ID column starts at 1
	 * hence counter below is $i + 1
	 */
	$memEvent = getJSONMembersAtEvent($connectionObject, $i+1);
	$jMemEvent = json_decode($memEvent, true);
?>
<BODY><pre>
<?php
//print_r($jMemEvent);
?>
</pre>
<?php

	/*************************************************************************************/
	//print_r($memEvent);
	echo "<DIV STYLE='FLOAT: LEFT; WIDTH: 900px; OVERFLOW-Y: SCROLL' NAME='membersAtEvent'>";
	echo "<H3>Event #" . $jEvent[$i]['id'] . ",&nbsp;&nbsp;&nbsp;" . $jEvent[$i]['name'] . "</H3>";
	if (empty($jMemEvent))
	{
		echo "There are no records for this Event!";
	}
	else
	{
		echo "<TABLE ID ='ALL-MEMBERS-ACTS'>";

		//echo "Event number is now " . $jEvent[$i]['id'];
		echo "<THEAD><TR>
			<TH>Event</TH>
			<TH>Act Id</TH>
			<TH>Activity</TH>
			<TH>Short code</TH>
			<TH>Mem Id</TH>
			<TH>First name</TH>
			<TH>Last name</TH></TR></THEAD>";

		echo "<TBODY STYLE='FONT-SIZE: 12px;'>";
		$j = 0;

			foreach ($jMemEvent as $memValue)//innermost loop that builds the list
			{
				echo "<TR>";
				echo "<TD>" . $memValue['event_id'] . "</TD>";
				echo "<TD>" . $memValue['activity_id'] . "</TD>";
				echo "<TD>" . $memValue['activity_name'] . "</TD>";
				echo "<TD>" . $memValue['activity_short_code']  . "</TD>";
				echo "<TD>" . $memValue['id'] . "</TD>";
				echo "<TD>" . $memValue['name_first'] . "</TD>";
				echo "<TD>" . $memValue['name_last'] . "</TD>";
				echo "</TR>";
				$j++;

			}//end innermost loop
		echo "</TBODY></TABLE>";
		echo "<div><b>Total: " . $j . " volunteers.</b></div></DIV>";
	}//end if records exist
}//end loop Events
	?>
</BODY>
</HTML>