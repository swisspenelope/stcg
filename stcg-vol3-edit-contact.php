<?php
/*
session handing added for security 2014.01.29
*/
require_once 'header.php';

/************** CALL GET ALL CONTACT DETAILS ************* DO SESSION CHECK *************/
/****************************************************************************************/
$connectionObject = connect();
$allInterests = getAllInterestsButUnknown($connectionObject);
if (!isset($_SESSION['user']))
{
	redirect_to("stcg-vol1-login.php");
}
/****************************************************************************************/
?>

<TITLE>
Change personal details of authenticated member
</TITLE>
<script type="text/javascript">
$(document).ready(function ()
{
	$('input:checkbox[name=valid_source]').attr('checked',true);
	$('input:checkbox[name=valid_first]').attr('checked',true);
	$('input:checkbox[name=valid_last]').attr('checked',true);
	$('input:checkbox[name=valid_email]').attr('checked',true);

   $("#back").click(function ()
	{
        window.location.href="stcg-vol2-authenticated.php";
	});

	$('#submit').on('click', function (event)
	{
		checkForm(this);
	});
});

</script>
<script type="text/javascript">
function callAjax(method, value, target)
{
    if(encodeURIComponent)
	{
	    var req = new AjaxRequest();
	    var params = "method=" + method + "&value=" + encodeURIComponent(value) + "&target=" + target;
	    req.setMethod("POST");
	    req.loadXMLDoc('stcg-ajax-validation.php', params);
   }
}
</script>
<script type="text/javascript">
////THIS CHECKFORM IS FOR EDITING EXISTING DATA ONLY
function checkForm(form)
{
	var isOk = true;
 	if (document.forms["CHANGE_CONTACT_DATA"].elements["source"].value == "")
	{
	    alert("Please enter a brief word about where you heard of us.");
	    document.forms["CHANGE_CONTACT_DATA"].elements["source"].focus();

		isOk = false;
	    return;
	}

	  if (document.forms["CHANGE_CONTACT_DATA"].elements["first"].value == "")
	  {
	    alert("Please enter your First Name.");
	    document.forms["CHANGE_CONTACT_DATA"].elements["first"].focus();
		isOk = false;
	    return;
	  }

	  if(document.forms["CHANGE_CONTACT_DATA"].elements["last"].value == "")
	  {
	    alert("Please enter your Last Name.");
	    document.forms["CHANGE_CONTACT_DATA"].elements["last"].focus();
	    isOk = false;
	    return;
	  }

	  if(document.forms["CHANGE_CONTACT_DATA"].elements["email"].value == "" || !document.forms["CHANGE_CONTACT_DATA"].elements["valid_email"].checked)
	  {
	    alert("Please enter a valid Email address.");
	    document.forms["CHANGE_CONTACT_DATA"].elements["email"].focus();
	    isOk = false;
	    return;
	  }
		document.forms["CHANGE_CONTACT_DATA"].elements["submit"].disabled=true;
		document.forms["CHANGE_CONTACT_DATA"].elements["submit"].value='Sending, please wait ...';
		updateMemberContactDetails();
 }
////////// SAVE TO DB USING AJAX /////////////////////////
function myCallback(response)
{
	if (response == 1)//if by error they make no change then click SEND
	{
		alert("Your changes have been accepted! / Vos modifications ont été faites!");
		window.location.href="stcg-vol2-authenticated.php";
	}
	else if (response == 0)
	{
		alert("You did not change any information! / Vous n'avez rien changé!");
	}
}

function myCallbackError(jqXHR, textStatus, errorThrown )
{
		alert("The system was unable to make your changes. <br /><br />Please contact webmaster@servethecitygeneva.ch so that we can solve the problem. /" +
				"<br /><br />Le système n'a pas pu faire vos modifications. Veuillez contacter webmaster@servethecitygeneva.ch pour que nous puissions résoudre ce problème.\n" +
		textStatus + " " + errorThrown);
}

function updateMemberContactDetails()
{

		var data = "source=" + document.getElementById('source').value + "&last=" + document.getElementById('last').value + "&first=" + document.getElementById('first').value +
"&email=" + document.getElementById('email').value + "&org=" + document.getElementById('org').value +
"&phone=" + document.getElementById('phone').value + "&memId=" + document.getElementById('memId').value;
//alert(data);
        $.ajax({
            dataType: 'json',
            url: 'stcg-json-responses.php?fct=updateMemberContactDetails',
            data: data,
    		cache: false,
    		success: myCallback,
    		error: myCallbackError
    	});
}
</script>
</HEAD>
<BODY>
<?php
//FORM THAT ENABLES MEMBER TO CHANGE INCORRECT PERSONAL DATA IN DATABASE
?>
<!-- /****************************************************************************************/ -->
<FORM NAME="CHANGE_CONTACT_DATA" ID="CHANGE_CONTACT_DATA" METHOD="POST" ACTION="non-Ajax form handler">
	<DIV><H3>To make a change, highlight the incorrect text in the box, then type the right information in its place.</H3>
	<P>When you have finished making corrections, click the <b>SAVE</b> button.</P></DIV>
	<BR />
	<P><?php if (isset($msg))echo $msg;?></P>
		<FIELDSET style = "border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
			<LEGEND>&nbsp;<SPAN CLASS = "eng"><B>Your contact details</B></SPAN>&nbsp;/&nbsp;<SPAN CLASS = "fre"><B>Vos coordonnées</B></SPAN>&nbsp;</LEGEND>
			<TABLE border="0">
				<TR>
					<TD style="width: 40%;"><SPAN CLASS="eng">How did you hear about us?</SPAN> / <SPAN CLASS="fre">Comment avez-vous découvert STCG?</SPAN></TD>
					<TD>*</TD>
					<TD><TEXTAREA ID="source" NAME="source" title="Please enter a brief word about where you heard of us. Veuillez nous dire brièvement comment vous nous avez découvert." COLS="25" ROWS="3" onclick="valid_source.checked = false;" onchange="valid_source.checked = true;"><?php echo $_SESSION['source']; ?></TEXTAREA>
					<input type="checkbox" disabled name="valid_source"></TD>
					<TD></TD>
				</TR>
				<TR>
					<TD><SPAN CLASS = "eng">First Name</SPAN> / <SPAN CLASS = "fre">Prénom</SPAN></TD>
					<TD>*</TD>
					<TD><INPUT TYPE="text" title="Your first name.  Votre prénom." ID="first" NAME="first" SIZE="30" VALUE="<?php echo $_SESSION['first']; ?>" onclick="valid_first.checked = false;" onchange="valid_first.checked = true;">
					<input type="checkbox" disabled name="valid_first"></TD>
					<TD></TD>
				</TR>
				<TR>
					<TD><SPAN CLASS = "eng">Last Name</SPAN> / <SPAN CLASS = "fre">Nom de famille</SPAN></TD>
					<TD>*</TD>
					<TD><INPUT TYPE="text" title="Your last name.  Votre nom de famille." ID="last" NAME="last" SIZE="30" VALUE="<?php echo $_SESSION['last']; ?>" onclick="valid_last.checked = false;" onchange="this.value = this.value.replace(/^\s+|\s+$/g, ''); valid_last.checked = this.value;">
					<input type="checkbox" disabled name="valid_last"></TD>
					<TD></TD>
				</TR>
				<TR>
					<TD><SPAN CLASS = "eng">Email</SPAN> / <SPAN CLASS = "fre">Courriel</SPAN></TD>
					<TD>*</TD>
					<TD><INPUT TYPE="text" title="Your email address.  Votre adresse courriel." NAME="email" id="email" SIZE="25" VALUE="<?php echo $_SESSION['email']; ?>" onclick="valid_email.checked = false;" onchange="callAjax('checkEmail', this.value, this.id);">
					<input type="checkbox" disabled name="valid_email"></input></TD>
					<TD><div id="rsp_email"><!-- --></div></TD>
				</TR>
				<TR>
					<TD><SPAN CLASS = "eng">Phone number</SPAN> / <SPAN CLASS = "fre">Numéro de tél</SPAN></TD>
					<TD></TD>
					<TD><INPUT TYPE="text" title="Phone number" NAME="phone" ID="phone" VALUE="<?php echo $_SESSION['phone']; ?>" SIZE="25">
					<!--input type="checkbox" id="valid_phone" disabled name="valid_phone"></input></TD>
					<TD><div id="rsp_phone"><!-- --></div--></TD>
				</TR>
				<TR>
					<TD><SPAN CLASS = "eng">Organization, Association</SPAN> / <SPAN CLASS = "fre">Organisation, Association</SPAN></TD><TD></TD>
					<TD COLSPAN = "3"><INPUT TYPE="text" id="org" NAME="org" SIZE="30" VALUE="<?php echo $_SESSION['org']; ?>"></TD>
				</TR>
				<TR>
					<TD COLSPAN="4"><INPUT TYPE="hidden" name="memId" ID="memId" value="<?php echo $_SESSION['memberId']; ?>"></TD>
				</TR>
				</TABLE>
			</FIELDSET>
		<DIV>
		<BR />
			<DIV><INPUT TYPE="button" ID="back" NAME="back" VALUE="Back to Volunteer Account"><INPUT TYPE="button" ID="submit" NAME="submit" VALUE="SAVE"></DIV>
			<DIV><SPAN CLASS = "eng">&nbsp;&nbsp;Please click SAVE once only, then wait for the message!</SPAN>
				<BR />
				<SPAN CLASS = "fre">&nbsp;&nbsp;Veuillez cliquer une fois sur SAVE, et ensuite attendre le message!</SPAN>
			</DIV>
		</DIV>
	</FORM><!-- END CHANGE CONTACT DATA FORM -->