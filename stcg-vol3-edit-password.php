<?php
/*
session handing added for security 2014.01.29
*/

require_once 'header.php';
require_once 'stcg-password-handling.php';

/****************************** DO SESSION CHECK ****************************************/
if (!isset($_SESSION['user']))
{
	redirect_to("stcg-vol1-login.php");
}
/****************************************************************************************/
?>
<TITLE>
Change password of authenticated member
</TITLE>
<script type="text/javascript">
$(document).ready(function ()
{
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
		  if(document.forms["CHANGE_PASSWORD"].elements["pwd1"].value == "" || !document.forms["CHANGE_PASSWORD"].elements["valid_pwd1"].checked)
	  {
		  alert("Please enter a password.");
		  document.forms["CHANGE_PASSWORD"].elements["pwd1"].focus();
		  isOk = false;
	return;
	  }

	  if(document.forms["CHANGE_PASSWORD"].elements["pwd2"].value == "" || !document.forms["CHANGE_PASSWORD"].elements["valid_pwd2"].checked)
	  {
		    alert("Please type your password a second time.");
		    document.forms["CHANGE_PASSWORD"].elements["pwd2"].focus();
		    isOk = false;
		return;
	  }

	if ( document.forms["CHANGE_PASSWORD"].elements["pwd1"].value != document.forms["CHANGE_PASSWORD"].elements["pwd2"].value )
	{
		alert("Your new password entries do not match! Please try again!");
		document.forms["CHANGE_PASSWORD"].elements["pwd2"].focus();
		isOk = false;
		return;
	}
	document.forms["CHANGE_PASSWORD"].elements["submit"].disabled=true;
	document.forms["CHANGE_PASSWORD"].elements["submit"].value='Sending, please wait ...';
	updateMemberPassword();
 }
////////// SAVE TO DB USING AJAX /////////////////////////
function myCallback(response)
{
	if (response == 1)
	{
		alert("Your change has been accepted! / Votre modification a été faite!");
		window.location.href="stcg-vol2-authenticated.php";
	}
}

function myCallbackError(jqXHR, textStatus, errorThrown )
{
		alert("The system was unable to make your changes. Please contact webmaster@servethecitygeneva.ch so that we can solve the problem. /" +
				"Le système n'a pas pu faire vos modifications. Veuillez contacter webmaster@servethecitygeneva.ch pour que nous puissions résoudre ce problème.\n" +
		textStatus + " "
		+ errorThrown);
}

function updateMemberPassword()
{
		var data = "pass=" + document.getElementById('pwd2').value + "&memId=" + document.getElementById('memId').value;

        $.ajax({
            dataType: 'json',
            url: 'stcg-json-responses.php?fct=updateMemberPassword',
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
//FORM THAT ENABLES MEMBER TO CHANGE YOUR PASSWORD IN DATABASE
?>
<!-- /****************************************************************************************/ -->
<FORM NAME="CHANGE_PASSWORD" ID="CHANGE_PASSWORD" METHOD="POST" ACTION="non-Ajax form handler">
	<DIV><H3>To change your password, highlight the hidden current password in the box, then type the new one in its place.</H3>
		<P>When you have finished, click the <b>SAVE</b> button.</P></DIV>
		<BR />
		<P><?php if (isset($msg))echo $msg;?></P>
			<FIELDSET style = "border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
					<LEGEND>&nbsp;<SPAN CLASS = "eng"><B>Password change</SPAN>&nbsp;/&nbsp;<SPAN CLASS = "fre">Changement de mot de passe</B></SPAN>&nbsp;</LEGEND>
					<TABLE STYLE="font-size: 12px;">
						<TR>
							<TD><SPAN CLASS = "eng">New password</SPAN> / <SPAN CLASS = "fre">Nouveau mot de passe</SPAN></TD><TD style="width: 2%;">*</TD>
							<TD>
							<INPUT title="Create a password.  Créér un mot de passe." TYPE="password" id="pwd1" NAME="pwd1" SIZE="25"
							onchange="if(this.value != '') {callAjax('checkPwd1', this.value, this.id);}">
							<input type="checkbox" id="valid_pwd1" disabled name="valid_pwd1"></input>
							</TD>
							<TD><div id="rsp_pwd1"><!-- --></div></TD>
							<TD></TD>
						</TR>
						<TR>
							<TD style="width: 40%;"><SPAN CLASS = "eng">Repeat new password</SPAN> / <SPAN CLASS = "fre">Nouveau mot de passe encore</SPAN></TD>
							<TD style="width: 2%;">*</TD>
							<TD>
							<INPUT title="Repeat the password you created above.  Répéter le mot de passe créé ci-dessus." type="password" id="pwd2" name="pwd2" SIZE="25"
							onchange="if(this.value != '') {var pword1 = document.getElementById('pwd1').value; var newString = pword1 + ' ' + this.value; callAjax('checkPwd2', newString, this.id);}">
							<input type="checkbox" id="valid_pwd2" disabled name="valid_pwd2"></input>
							</TD>
							<TD><div id="rsp_pwd2"><!-- --></div></TD>
						</TR>
						<TR>
							<TD COLSPAN="4"><INPUT TYPE="HIDDEN" name="memId" ID="memId" value="<?php echo $_SESSION['memberId']; ?>"></TD>
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
	</FORM><!-- END CHANGE PASSWORD FORM -->