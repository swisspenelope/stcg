<?php
include_once 'header.php';
?>
<body>
<form Id="EVENT_ID" method="post" action="stcg-list-member-with-past-activities.php">
<h3>To list all the Activities a member has taken part in, use the name search below:</h3>
<p>(You can search for <b>part of the last name</b> or <b>part of the first name</b> if you are not sure of spelling,</p>
<p>Example: simply type "Jo" in the Last Name box if the name is "Johanssen".)</p> 
<div>Last Name&nbsp;&nbsp;<input type="text" name="lastName" size="50"></div><br />
<div>First Name&nbsp;<input type="text" name="firstName" size="50"></div><br />
<div><input type="submit" value="Continue"></div>
</form>
<?php
include_once 'footer.php';
?>