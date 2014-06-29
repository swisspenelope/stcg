<?php
require_once 'header.php';

if (!isset($_SESSION['user']))
{
    redirect_to("stcg-vol1-login.php");
}
else
{
    /*************************** call get member with this unique email *********************/
    /****************************************************************************************/

    $connectionObject = connect();
    $member = getThisMemberByEmail($connectionObject, $_SESSION['user']);
    //var_dump($member);
    
    if (isset ($member['id']))
            $_SESSION['memberId'] = $member['id'];
    if (isset ($member['name_first']))
            $_SESSION['first'] = $member['name_first'];
    if (isset ($member['name_last']))
            $_SESSION['last'] = $member['name_last'];
    if (isset ($member['organization']))
            $_SESSION['org'] = $member['organization'];
    if (isset ($member['email']))
            $_SESSION['email'] = $member['email'];
    if (isset ($member['password']))
            $_SESSION['password'] = $member['password'];
    if (isset ($member['phone']))
            $_SESSION['phone'] = $member['phone'];
    if (isset ($member['source']))
            $_SESSION['source'] = $member['source'];

    if ($member['organization'] == NULL)
            $member['organization'] = "None";
    if ($member['phone'] == NULL)
            $member['phone'] = "None";
    
    $_SESSION['user'] = $_SESSION['email'];
}
?>
<title>
Volunteer authenticated
</title>
<script type="text/javascript">
$(document).ready(function ()
{
    $("#back").click(function ()
    {
    window.location.href="stcg-vol1-login.php";
    });

    $("#returnToHome").click(function ()
    {
        $.ajax({
                type: "GET",
                url: "stcg-json-responses.php?fct=endSession",
                //data: dataString,
                success: function(response)
                {
                        //alert("session destroyed on exit");
                        top.location.href="http://www.servethecitygeneva.ch";
                }
        });
    });
        
    $("#signup").click(function ()
    {
        //alert(<?php echo $_SESSION['memberId'] ?>);
        window.location.href="stcg-signup-for-event.php?memId=" + <?php echo $_SESSION['memberId'] ?>;
    });
    
    
    <?php if ($_SESSION['user'] === ADMIN || $_SESSION['user'] === SUB_ADMIN)
    {
        ?>
        document.getElementById("access").setAttribute("style","visibility: visible");
        <?php
    }
        ?>
});
</script>
<?php
/********************* call get member interests with this unique email *****************/
/****************************************************************************************/
$ints = getThisMemberInterests($connectionObject, $_SESSION['user']);

/****************************************************************************************/
$_SESSION['ints'] = $ints;
$_SESSION['numberOfInts'] = count($ints);

/**convert the returned array of interests to a string with makeInterestStringFromArray.
 * this is vital for populating the multi-select box that displays them. */
$_SESSION['stringInts'] = makeInterestStringFromArray($ints);
?>
</head>
<body>
<?php
    if ($member) 
    {
?>
    <h2>Welcome back, <?php echo $_SESSION['first'] . " " . $_SESSION['last'] ?>!</h2>
    <h3>Your volunteer account / <span class='fre'>Votre compte de bénévole</span></h3>
<?php
    }
    else
    {
        echo "Some weird error occurred.";//ask JD what might trigger this to happen, if anything...
    }
?>
<?php
/*****************************************************************************************/
//form that displays member's current personal data from db and leads to edit data forms
?>
    <div style="width: 92%; padding: 20px; padding-top: 5px; clear: both;">
        Click this button to go straight to the signup page for our latest Event <input type="button" id="signup" value="Signup to Event" />
    </div>    
	<form name="change_contact" method="post" action="stcg-vol3-edit-contact.php">
		<fieldset style = "border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
			<legend>&nbsp;<span class = "eng"><b>Your contact details</b></span>&nbsp;/&nbsp;<span class = "fre"><b>Vos coordonnées</b></span>&nbsp;</legend>
				<div><input type="submit" value="Change contact details"></div>
				<table>
					<tr>
						<td style="width: 50%;">Where did you hear of us?</td>
						<td class="right-align"><input disabled type="text" name="source" size="40px" value="<?php echo $_SESSION['source']; ?>"></td>
					</tr>
					<tr>
						<td>First name</td>
						<td class="right-align"><input disabled type="text" name="first" value="<?php echo $_SESSION['first']; ?>"></td>
					</tr>
					<tr>
						<td>Last name</td>
						<td class="right-align"><input disabled type="text" name="last" value="<?php echo $_SESSION['last']; ?>"></td>
					</tr>
					<tr>
						<td>Organization<br />(or sponsoring association)</td>
						<td class="right-align"><input disabled type="text" name="org" value="<?php echo $_SESSION['org']; ?>"></td>
					</tr>
					<tr>
						<td>Email address</td>
						<td class="right-align"><input disabled type="text" size="40px" name="email" value="<?php echo $_SESSION['email']; ?>" ></td>
					</tr>
					<tr>
						<td>Phone number</td>
						<td class="right-align"><input disabled type="text" name="phone" value="<?php echo $_SESSION['phone']; ?>"></td>
					</tr>
					<tr>
						<td colspan="2"><input type="hidden" name="memId" id="memId" value="<?php echo $_SESSION['memberId']; ?>"></td>
					</tr>
				</table>
				<div><input type="submit" value="Change contact details"></div>
			</fieldset>
	</form>
	<br /><br />
	<form name="change_password" method="post" action="stcg-vol3-edit-password.php">
		<fieldset style = "border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
			<legend>&nbsp;<span class = "eng"><b>Your password</b></span>&nbsp;/&nbsp;<span class = "fre"><b>Votre mot de passe</b></span>&nbsp;</legend>
				<input type="hidden" name="memId" id="memId" value="<?php echo $_SESSION['memberId']; ?>">
				<div><input type="submit" value="Change password"></div>
		</fieldset>
	</form>
	<br /><br />
	<form name="change_lang_reg" method="post" action="stcg-vol3-edit-lang-reg.php">
	<?php
	if (isset ($member['language_id']))
	$_SESSION['language_id'] = $member['language_id'];
	if (isset ($member['location_id']))
	$_SESSION['location_id'] = $member['location_id'];
	?>
		<fieldset style = "border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
			<legend>&nbsp;<span class = "eng"><b>Your language and where you live</b></span>&nbsp;/&nbsp;<span class = "fre"><b>Votre langue et où vous habitez</b></span>&nbsp;</legend>
				<table>
					<tr>
						<td>Preferred language / Langue préférée</td>
						<td class="right-align"><input disabled type="text" name="lang" value="<?php echo langNumToText($_SESSION['language_id']); ?>"></td>
					</tr>
					<tr>
						<td>Region you live in / Où vous habitez</td>
						<td class="right-align"><input disabled type="text" name="region" value="<?php echo locationNumToText($_SESSION['location_id']); ?>"></td>
					</tr>
					<tr>
						<td colspan="2"><input type="hidden" name="memId" id="memId" value="<?php echo $_SESSION['memberId']; ?>"></td>
					</tr>
				</table>
				<div><input type="submit" value="Change language or region"></div>
			</fieldset>
	</form>
			<br /><br />
	<form name="change_interests" method="post" action="stcg-vol3-edit-ints.php">
		<fieldset style = "border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
		<legend>&nbsp;<span class = "eng"><b>Your interests</b></span>&nbsp;/&nbsp;<span class = "fre"><b>Vos intérêts</b></span>&nbsp;</legend>
				<table>
					<tr>
						<td>Your interests / Vos intérêts<td>
						<td class="right-align"><select style="width: 300px;" multiple disabled name="interest" size="<?php echo $_SESSION['numberOfInts']; ?>">
						<?php for($i = 0; $i < $_SESSION['numberOfInts']; $i++)
						{
						?>
						<option id ="<?php echo $i; ?>"><?php echo interestNumToText($_SESSION['stringInts'][$i]); ?></option>
						<?php
						}
						?>
						</select></td>
					</tr>
					<tr>
						<td colspan="2"><input type="hidden" name="memId" id="memId" value="<?php echo $_SESSION['memberId']; ?>"></td>
					</tr>
				</table>
				<div><input type="submit" value="Change interests"></div>
		</fieldset>
	</form>
	<!-- /*****************************************************************************************/ -->
<?php
//end current personal data form
?>
<div><input type="button" id="returnToHome" name="returnToHome" value="Return to Home Page"></div>
<div id="access" style="visibility: hidden; width: 40%; padding: 20px; padding-top: 5px; clear: both;">
    <?php include 'stcg-admin-access.php'; ?>
</div>
<?php
    include 'footer.php';
?>