<?php
require_once 'header.php';

/********************************* CALL GET ALL INTERESTS *******************************/
$connectionObject = connect();
$allInterests = getAllInterests($connectionObject);

/****************************************************************************************/
?>
<title>Enter interest to find members</title>
</head>
<body>
<form id="interest_id" method="POST" action="stcg-list-interest-shared-by-all-members.php">
<h3>Select one from the list of interests below:</h3>

<table style="font-size: 12px; width: 50%;">
<!-- 1..7 interests allowed -->
<?php 
	foreach ($allInterests as $value)
	{
?>
<tr>
<td style="width: 5%;"><input type="radio" name="interests" id="<?php echo $value['interest_id']; ?>" value="<?php echo $value['interest_id']; ?>"></td>
<td style="width: 50%;"><label for="interests"><?php echo interestNumToText($value['interest_id']); ?></label></td>
</tr>
<?php
//End loop through interests 
	}
?>
</table>
<div>
<br /><br />
<input type="submit" value="Continue"></div>
</form>
<?php
    include_once 'footer.php';
?>