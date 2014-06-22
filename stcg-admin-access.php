<?php
// plays the role of input member name
require_once 'header.php';
?>
<body>
    <form Id="member_details" method="post" action="stcg-edit-member-details.php">
        <fieldset style = "border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
            <legend style="font-size: 22px;">Administrator only: change a volunteer's data in database</legend>
    
            <p>(You can search for <b>part of the last name</b> or <b>part of the first name</b> if you are not sure of spelling,</p>
            <p>Example: simply type "Jo" in the Last Name box if the name is "Johanssen".</p>
            <p>You don't have to type something in both boxes. You can leave First Name empty.)</p> 
            <div>Last Name&nbsp;&nbsp;<input type="text" id="lastName" name="lastName" size="50"></div><br />
            <div>First Name&nbsp;<input type="text" id="firstName" name="firstName" size="50"></div><br />
            <div><input type="submit" value="Continue"></div>
        </fieldset>
    </form>
    
<?php
include_once 'footer.php';
?>
