<?php
require_once 'header.php';
?>
<title>Administration Menu</title>   
</head>
<body>
<h2> Admin Menu - Administrator of STCG only</h2>
<form>
    <div>  
        <fieldset style = "border: solid black 1px; width: 40%; padding: 20px; padding-top: 20px">
        <p><a href="stcg-list-all-members.php">List of signed-up members to date (with sorting of columns)</a></p> 
        <p><a href="stcg-list-members-and-details.php">List of members and their details</a></p>      
        <p><a href="stcg-list-event-activities-with-members.php">List of  members at all Events, from  start of STCG</a></p>
	<p><a href="stcg-input-member-to-find-history.php">View  Event/Activity history of one  member</a></p>
	</fieldset>
    </div>
    <br /><br />
    <div>
        <fieldset style = "border: solid black 1px; width: 40%; padding: 20px; padding-top: 20px">
        <p><a href="stcg-input-interest-to-find-members.php">Find all members sharing a particular interest</a></p>
        <p><a href="stcg-input-language-to-find-members.php">Find all  members  speaking a chosen language</a></p>
        </fieldset>
    </div>
    <br /><br />
    <div>
        <fieldset style = "border: solid black 1px; width: 40%; padding: 20px; padding-top: 20px">
	<p><a href="stcg-organize-latest-event-activities-members.php">Organize signups at latest Event</a></p>
        </fieldset> 
    </div>
    <br /><br />
    <div>
        <fieldset style = "border: solid black 1px; width: 40%; padding: 20px; padding-top: 20px">
	<p><a href="stcg-vol1-login.php">Change a member's personal details in database (Gary only)</a></p>
        </fieldset> 
    </div>
</form>
</body>