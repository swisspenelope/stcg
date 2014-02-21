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
<HTML>
<HEAD>
<TITLE>Member Activity History</TITLE>
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
</HEAD>
<BODY>
<?php 
/********************* CALL GET MEMBER AND GET ALL ACTIVITIES FUNCTIONS ******************/
$connectionObject = connect();
$actHistory = getJSONMembersAndPastActivities($connectionObject, $memberLast, $memberFirst);

/****************************************************************************************/	

$JSONactHistory = json_decode($actHistory, true);

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
		
		echo "<DIV><INPUT TYPE='text' VALUE='$textToPass'NAME='activityDetails' SIZE='150px' DISABLED></DIV>";
	} 
}
else
{
	$msg = "There is no history of past Events for search terms: " . $memberFirst . " and " . $memberLast . ".";
}
?>

<H3><?php echo $msg; ?></H3>
<BR /><BR />
<INPUT TYPE="BUTTON" ID="back" VALUE="Back"></INPUT>
<BR /><BR />
<INPUT TYPE="BUTTON" ID="admin" VALUE="Back to Admin Menu"></INPUT>
</BODY>
</HTML>