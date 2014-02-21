<?php
header("Content-Type: text/html;charset=utf-8");

require_once 'stcg-utilities.php';
require_once 'stcg-data-layer.php';

?>
<HTML>
<HEAD>
<script src="scripts/custom.js" type="text/javascript"></script>
<script src="scripts/jquery-ui-1.10.3/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/jquery.tablesorter.js" type="text/javascript"></script> 
<TITLE>History of Activities by Member</TITLE>
<link rel ="stylesheet" type="text/css" href="css/theme.css">
<link rel ="stylesheet" type="text/css" href="css/structure.css">
<link rel ="stylesheet" type="text/css" href="css/form.css">
</HEAD>
<BODY>
<FORM ID="EVENT_ID" METHOD="POST" ACTION="stcg-list-member-with-past-activities.php">
<H3>To list all the Activities a member has taken part in, use the name search below:</H3>
<P>(You can search for <B>part of the last name</B> or <B>part of the first name</B> if you are not sure of spelling,</P>
<P>Example: simply type "Jo" in the Last Name box if the name is "Johanssen".)</P> 
<DIV>Last Name&nbsp;&nbsp;<INPUT type="text" name="lastName" size="50px"></DIV><BR />
<DIV>First Name&nbsp;<INPUT type="text" name="firstName" size="50px"></DIV><BR />
<DIV><INPUT TYPE="SUBMIT" VALUE="Continue"></DIV>
</FORM>
</BODY>
</HTML>