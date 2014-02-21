<?php
require_once 'header.php';

/********************************* CALL GET ALL INTERESTS *******************************/
$connectionObject = connect();
$allLangs = getAllLanguages($connectionObject);

/****************************************************************************************/
?>
<HTML>
<HEAD>
<TITLE>Enter language to find members</TITLE>
</HEAD>
<BODY>
<FORM ID="INTEREST_ID" METHOD="POST" ACTION="stcg-list-language-shared-by-all-members.php">
<H3>Select one from the list of language preferences below:</H3>

<TABLE STYLE="font-size: 12px; WIDTH: 30%;">
<!-- 1 of 4 language prefs allowed -->
<?php 
	foreach ($allLangs as $value)
	{
?>
<TR>
<TD style="width: 5%;"><INPUT TYPE="RADIO" NAME="lang" ID="<?php echo $value['id']; ?>" VALUE="<?php echo $value['id']; ?>"></TD>
<TD style="width: 50%;"><LABEL for="lang"><?php echo langNumToText($value['id']); ?></LABEL></TD>
</TR>
<?php
//End loop through langs 
	}
?>
</TABLE>
<DIV>
<BR /><BR />
<INPUT TYPE="SUBMIT" VALUE="Continue"></DIV>
</FORM>
</BODY>
</HTML>