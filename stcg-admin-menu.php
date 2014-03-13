<?php
require_once 'header.php';
?>
<title>Administration Menu</title>   
</head>
<body>
<h2> Admin Menu - Administrator of STCG only</h2>
<form>
    <div>  
        <fieldset>
        <p><a href="stcg-list-members.php">List of  signed-up members to  (with sorting of columns)</a></p>         
        <p><a href="stcg-list-event-activities-with-members.php">List of  members at  Events, from  start of STCG</a></p>
	<p><a href="stcg-input-member-to-find-history.php">View  Event/Activity history of one  member</a></p>
	</fieldset>
    </div>
    <br /><br />
    <div>
        <fieldset>
        <p><a href="stcg-input-interest-to-find-members.php">Find all members sharing a particular interest</a></p>
        <p><a href="stcg-input-language-to-find-members.php">Find all  members  speaking a chosen language</a></p>
        </fieldset>
    </div>
    <br /><br />
    <div>
        <fieldset>
	<p><a href="stcg-organize-latest-event-activities-members.php">Organize signups at latest Event</a></p>
        </fieldset> 
    </div>
</form>
</body>