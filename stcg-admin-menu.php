<?php
require_once 'stcg-config.php';
header("Content-Type: text/html;charset=utf-8");

require_once 'stcg-config.php';
require_once 'stcg-utilities.php';
require_once 'stcg-data-layer.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
    <title>Administration Menu</title>   
</head>
<body>
<h2>The Admin Menu - Administrator of STCG only</h2>
<TABLE>
<TR>
<TD><H3><a href="stcg-list-all-members.php">List of all signed-up members to date (with sorting of columns)</a></H3>
</TD>
</TR>
<TR>
<TD><H3><a href="stcg-organize-latest-event-activities-members.php">Organize signups</a></H3>
</TD>
</TR>
<TR>
<TD><H3><a href="stcg-input-member-to-find-history.php">View the Event/Activity history of one member</a></H3></TD>
</TR>
<TR>
<TD><H3><a href="stcg-input-interest-to-find-members.php">See all members sharing a particular interest</a></H3></TD>
</TR>
<TR>
<TD><H3><a href="stcg-input-language-to-find-members.php">See all members that speak a chosen language</a></H3></TD>
</TR>
<TR>
<TD><H3><a href="stcg-list-event-activities-with-members.php">See all members at all Events - from the start of STCG</a></H3></TD>
</TR>
</TABLE>
</body>
</html>