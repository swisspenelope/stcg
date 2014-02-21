<?php
require_once 'header.php';

/********************************* CALL GET ALL INTERESTS *******************************/
$connectionObject = connect();
$allInterests = getAllInterests($connectionObject);

/****************************************************************************************/
?>
<HTML>
<HEAD>
<TITLE>Enter interest to find members</TITLE>
</HEAD>
<BODY>
<FORM ID="INTEREST_ID" METHOD="POST" ACTION="stcg-list-interest-shared-by-all-members.php">
<H3>Select one from the list of interests below:</H3>

<TABLE STYLE="font-size: 12px; WIDTH: 50%;">
<!-- 1..7 interests allowed -->
<?php 
	foreach ($allInterests as $value)
	{
?>
<TR>
<TD style="width: 5%;"><INPUT TYPE="RADIO" NAME="interests" ID="<?php echo $value['interest_id']; ?>" VALUE="<?php echo $value['interest_id']; ?>"></TD>
<TD style="width: 50%;"><LABEL for="interests"><?php echo interestNumToText($value['interest_id']); ?></LABEL></TD>
</TR>
<?php
//End loop through interests 
	}
?>
</TABLE>
<DIV>
<BR /><BR />
<INPUT TYPE="SUBMIT" VALUE="Continue"></DIV>
</FORM>
</BODY>
</HTML>