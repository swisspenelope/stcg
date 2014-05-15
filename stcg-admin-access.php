<?php
// plays the role of input member name
require_once 'header.php';
?>
<body>
    <form Id="member_details" method="post" action="stcg-list-members-and-details.php">
        <p>(You can search for <b>part of the last name</b> or <b>part of the first name</b> if you are not sure of spelling,</p>
        <p>Example: simply type "Jo" in the Last Name box if the name is "Johanssen".)</p> 
        <div>Last Name&nbsp;&nbsp;<input type="text" id="lastName" name="lastName" size="50"></div><br />
        <div>First Name&nbsp;<input type="text" id="firstName" name="firstName" size="50"></div><br />
        <div><input type="submit" value="Continue"></div>
    </form>
<?php
include_once 'footer.php';
?>
