<?php
require_once 'header.php';

if (!$_POST['lastName']) 
{
	header("Location: stcg-input-member-to-find-history.php");
	exit;
}
else
{
	$memberLast = $_POST['lastName'];
	if (isset ($_POST['firstName']))
		$memberFirst = $_POST['firstName'];
}
?>
	<pre>
	<?php
	//print_r($JSONactHistory);
	?>
	</pre>
<title>Member Activity History</title>
<script type="text/javascript"> 
$(document).ready(function () 
{
	$("#back").click(function () {
        window.location="/stcg/site/stcg-input-member-to-find-history.php";
    });
	
	$("#admin").click(function () {
        window.location="/stcg/site/stcg-admin-menu.php";
    });
});
</script>
</head>
<body>
    <pre>
<?php 
/********************* CALL GET MEMBER AND GET ALL ACTIVITIES FUNCTIONS ******************/
$connectionObject = connect();
$actHistory = getJSONMembersAndPastActivities($connectionObject, $memberLast, $memberFirst);

/****************************************************************************************/	
//var_dump($actHistory);//JSON returns an array of objects [  {}, {}, {} ]
?>
</pre>
    <pre>
    <?php
$JSONactHistory = json_decode($actHistory, true);
//var_dump($JSONactHistory);
?>
</pre>
<?php

$msg ="";
if ($memberFirst == "")
	$memberFirst = "Nothing";

if ($memberLast == "")
	$memberLast = "Nothing";

if (!empty($JSONactHistory))
{
	echo "<H3>List of previous Events/Activities for your search items: " . $memberFirst . " and " . $memberLast . "</H3>";
	foreach ($JSONactHistory as $value)
	{
		$textToPass = $value['name_first'] . " " . $value['name_last'] . ", " . $value['name'] . ", " . $value['activity_short_code'] . " " . $value['activity_name'];
		
		echo "<div><input type='text' value='$textToPass'name='activitydetails' size='150px' disabled></div>";
	} 
}
else
{
	$msg = "There is no history of past Events for search terms: " . $memberFirst . " and " . $memberLast . ".";
}
?>
    <fieldset style = "border: solid #AAAAAA 1px; width: 90%; padding: 20px; padding-top: 20px;">
<div style="FLOAT: LEFT;" id = jqxgrid1></div>	
</fieldset>
<h3><?php echo $msg; ?></h3>
<br /><br />
<input type="button" id="back" value="Back">
<br /><br />
<input type="button" id="admin" value="Back to Admin Menu">
</body>
<?php
include_once 'footer.php';
?>