<?php
include_once 'header.php';

/*********************** CALL GET LOCATIONS, INTERESTS AND LANGUAGES ********************/
$connectionObject = connect();

$allLocations = getAllLocations($connectionObject);
$allLanguages = getAllLanguages($connectionObject);
$allInterests = getAllInterestsButUnknown($connectionObject);

/****************************************************************************************/
?>
<title>
New Volunteer Signup page
</title>
<script type="text/javascript">
var theRegion = 0;
var theLang = 0;
var theInts = [];
var intString = "";

//after a recaptcha error, it goes back to before document ready. if ints is not
//reinitted below after document ready, ints simply adds to itself - bug
$(document).ready(function ()
{
	$("#back").click(function ()
	{
        	window.top.location="http://www.servethecitygeneva.ch";
	});

    $("input[name='interests']").on('click', function()
	{
		if ( $(this).val() == 7)
		{
			var doCheck = $(this).is(':checked');
			//:checked is set to false or true
			$("input[name='interests']").each(function()
			{
				$(this).prop('checked', doCheck);
			});
		}
		else if ($(this).val() < 7)
		{
			$( "input[name='interests']:eq(7)" ).prop('checked', true);
		}
	});

	$('#submit').on('click', function (event)
	{
		theRegion = $('input[name=region]:checked', '#NEWMEM').val();
		theLang = $('input[name=lang]:checked', '#NEWMEM').val();

		$("input[name='interests']:checked").each(function()
			{
				//if you don't use "push", js forgets that this is an array.
				theInts.push($(this).val());
			});
		intString = theInts.toString();
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
function checkForm(form)
{
 	var isOk = true;
 	if (document.forms["NEWMEM"].elements["source"].value == "")
	  {
	    alert("Please enter a brief word about where you heard of us.");
	    document.forms["NEWMEM"].elements["source"].focus();
		isOk = false;
	return;
	}

	  if (document.forms["NEWMEM"].elements["first"].value == "")
	  {
	    alert("Please enter your First Name.");
	    document.forms["NEWMEM"].elements["first"].focus();
		isOk = false;
	return;
	  }

	  if(document.forms["NEWMEM"].elements["last"].value == "")
	  {
	    alert("Please enter your Last Name.");
	    document.forms["NEWMEM"].elements["last"].focus();
	    isOk = false;
	return;
	  }

	  if(document.forms["NEWMEM"].elements["email"].value == "" || !document.forms["NEWMEM"].elements["valid_email"].checked)
	  {
	    alert("Please enter a valid Email address.");
	    document.forms["NEWMEM"].elements["email"].focus();
	    isOk = false;
	return;
	  }

	  if(document.forms["NEWMEM"].elements["pwd1"].value == "" || !document.forms["NEWMEM"].elements["valid_pwd1"].checked)
	  {
		  alert("Please enter a password.");
		  document.forms["NEWMEM"].elements["pwd1"].focus();
		  isOk = false;
	return;
	  }

	  if(document.forms["NEWMEM"].elements["pwd2"].value == "" || !document.forms["NEWMEM"].elements["valid_pwd2"].checked)
	  {
		    alert("Please type your password a second time.");
		    document.forms["NEWMEM"].elements["pwd2"].focus();
		    isOk = false;
		return;
	  }

	  if (!$("input[name='region']:checked").val() > 0)
	  {
			alert("Please select your geographical region.");
			isOk = false;
		return;
	  }

	  if (!$("input[name='lang']:checked").val() > 0)
	  {
			alert("Please select your language preference.");
			isOk = false;
		return;
	  }

	  if (!$("input[name='interests']:checked").val() > 0)
	  {
			alert("Please select at least one area of interest.");
			isOk = false;
		return;
	  }
	//isRecaptchaOK();
	document.forms["NEWMEM"].elements["submit"].disabled=true;
	document.forms["NEWMEM"].elements["submit"].value='Sending, please wait ...';
	insertMemberInDatabase(myCallback);
 }

////////// CALLBACK - SUCCESS /////////////////////////
function myCallback(response)
{
//ajax call returns one or more rows inserted
	if (response > 0)
	{
		//alert("Thank you for your registration! / Merci de votre inscription!");
		window.top.location="http://www.servethecitygeneva.ch/index.php?page_id=3292";
	}
}

////////// CALLBACK - FAILURE /////////////////////////
/*Error callback is called on http errors, but also if JSON parsing on the response fails.
This is what's probably happening if response code is 200 but you still are thrown to error callback.*/
function myCallbackError(jqXHR, textStatus, errorThrown )
//ajax call returns any kind of error
{
/*		alert("The system was unable to process your registration. Please contact postmaster@servethecitygeneva.ch so that we can solve the problem. /" +
				"Le système n'a pas pu complèter votre inscription. Veuillez contacter postmaster@servethecitygeneva.ch pour que nous puissions résoudre ce problème.\n" +
		textStatus  + " " + errorThrown);*/
		//alert("Thank you! / Merci! " + textStatus + " " + errorThrown);//JSON parse unexpected char in the errorThrown)
		window.top.location="http://www.servethecitygeneva.ch/index.php?page_id=3299";

}

function insertMemberInDatabase()
{
    	var data = "source=" + document.getElementById('source').value + "&last=" + document.getElementById('last').value + "&first=" + document.getElementById('first').value +
"&email=" + document.getElementById('email').value + "&org=" + document.getElementById('org').value +
"&pass=" + document.getElementById('pwd2').value + "&phone=" + document.getElementById('phone').value +
"&comments=" + document.getElementById('comments').value + "&region=" + theRegion + "&lang=" + theLang + "&ints=" + intString;
        $.ajax({
            dataType: 'json',
            url: 'stcg-json-responses.php?fct=insertNewMemberDetails',
            data: data,
    		cache: false,
    		success: myCallback,
    		error: myCallbackError
    	});
}
</script>
</head>
<BODY>
<TABLE>
	<TR>
		<TD><H3 CLASS = "eng">Sign up with Serve The City Geneva</H3></TD>
		<TD><H3 CLASS = "fre">Inscrivez-vous chez Serve The City Geneva</H3></TD>
	</TR>
	<TR>
		<TD><P CLASS = "eng"><B>* means this information<br/>MUST be entered</B></P></TD>
		<TD><P CLASS = "fre"><B>* signifie que cette information<br/>est OBLIGATOIRE</B></P></TD>
	</TR>
</TABLE>
<INPUT TYPE="BUTTON" ID="back" VALUE="Back"></INPUT>

<!-- /****************************************************************************************/-->
<FORM name= "NEWMEM" ID = "NEWMEM" METHOD="POST" ACTION="non-Ajax form handler">
		<FIELDSET style = "border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
			<LEGEND>&nbsp;<SPAN CLASS = "eng"><B>Your contact details</B></SPAN>&nbsp;/&nbsp;<SPAN CLASS = "fre"><B>Vos coordonnées</B></SPAN>&nbsp;</LEGEND>

			<TABLE border="0">
				<TR>
					<TD style="width: 40%;"><SPAN CLASS="eng">How did you hear about us?</SPAN> / <SPAN CLASS="fre">Comment avez-vous découvert STCG?</SPAN></TD>
					<TD style="width: 2%;">*</TD>
					<TD style="width: 10%;"><TEXTAREA ID="source" NAME="source" title="Please enter a brief word about where you heard of us.  Veuillez nous dire brièvement comment vous nous avez découvert." COLS="30" ROWS="3"></TEXTAREA></TD>
				</TR>
				<TR>
					<TD><SPAN CLASS = "eng">First Name</SPAN> / <SPAN CLASS = "fre">Prénom</SPAN></TD>
					<TD>*</TD>
					<TD><INPUT TYPE="text" title="Your first name.  Votre prénom." ID="first" NAME="first" SIZE="30" onchange="this.value = this.value.replace(/^\s+|\s+$/g, ''); valid_first.checked = this.value;">
					<input type="checkbox" disabled name="valid_first"></TD>
				</TR>
				<TR>
					<TD><SPAN CLASS = "eng">Last Name</SPAN> / <SPAN CLASS = "fre">Nom de famille</SPAN></TD>
					<TD>*</TD>
					<TD><INPUT TYPE="text" title="Your last name.  Votre nom de famille." ID="last" NAME="last" SIZE="30" onchange="this.value = this.value.replace(/^\s+|\s+$/g, ''); valid_last.checked = this.value;">
					<input type="checkbox" disabled name="valid_last"></TD>
				</TR>
				<TR>
					<TD><SPAN CLASS = "eng">Email</SPAN> / <SPAN CLASS = "fre">Courriel</SPAN></TD>
					<TD>*</TD>
					<TD><INPUT TYPE="text" title="Your email address.  Votre adresse courriel." NAME="email" id="email" SIZE="25" onchange="if(this.value != '') {callAjax('checkRegistrationEmail', this.value, this.id);}">
					<input type="checkbox" id="valid_email" disabled name="valid_email"></input>
					<div id="rsp_email"><!-- --></div></TD>
				</TR>
				<TR>
					<TD><SPAN CLASS = "eng">Create a password</SPAN> / <SPAN CLASS = "fre">Créér un mot de passe</SPAN></TD>
					<TD style="width: 2%;">*</TD>
					<TD><INPUT title="Create a password.  Créér un mot de passe." TYPE="password" id="pwd1" NAME="pwd1" SIZE="25" onchange="if(this.value != '') {callAjax('checkPwd1', this.value, this.id);}">
					<input type="checkbox" id="valid_pwd1" disabled name="valid_pwd1"></input>
					<div id="rsp_pwd1"><!-- --></div></TD>
				</TR>
				<TR>
					<TD style="width: 40%;"><SPAN CLASS = "eng">Repeat your password</SPAN> / <SPAN CLASS = "fre">Mot de passe encore</SPAN></TD>
					<TD style="width: 2%;">*</TD>
					<TD><INPUT title="Repeat the password you created above.  Répéter le mot de passe créé ci-dessus." type="password" id="pwd2" name="pwd2" SIZE="25" onchange="if(this.value != '') {
					var pword1 = document.getElementById('pwd1').value; var newString = pword1 + ' ' + this.value; callAjax('checkPwd2', newString, this.id);}">
					<input type="checkbox" id="valid_pwd2" disabled name="valid_pwd2"></input>
					<div id="rsp_pwd2"><!-- --></div></TD>
				</TR>
				<TR>
					<TD style="width: 40%;"><SPAN CLASS = "eng">Phone number</SPAN> / <SPAN CLASS = "fre">Numéro de tél.</SPAN></TD>
					<TD>&nbsp;</TD>
					<TD><INPUT TYPE="text" title="Phone number" id="phone" NAME="phone" SIZE="25"><!--onchange="if(this.value != '')
					{callAjax('checkPhone', this.value, this.id);}"-->
					<!--input type="checkbox" id="valid_phone" disabled name="valid_phone"></input>
					<div id="rsp_phone"><!-- --></div--></TD>
				</TR>
				<TR>
					<TD style="clear: both; width: 40%;"><SPAN CLASS = "eng">Organization, Association</SPAN> / <SPAN CLASS = "fre">Organisation, Association</SPAN></TD>
					<TD>&nbsp;</TD>
					<TD COLSPAN = "2"><INPUT TYPE="text" id="org" NAME="org" SIZE="30"></TD>
				</TR>
			</TABLE>
		</FIELDSET><br /><br />

		<FIELDSET style ="border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
			<LEGEND>&nbsp;<SPAN CLASS = "eng"><B>Your preferences</SPAN>&nbsp;/&nbsp;<SPAN CLASS = "fre">Vos préférences</B></SPAN>&nbsp;</LEGEND>
			<TABLE border="0">
				<TR>
					<TD COLSPAN="3">*&nbsp;<SPAN CLASS = "eng">Where do you live?</SPAN> / <SPAN CLASS = "fre">Où habitez-vous?</SPAN></TD></TR>
				<TR>
				<!-- only one region allowed -->
				<?php
					foreach ($allLocations as $value)
					{
				?>
					<TD style="width: 21%;">&nbsp;</TD>
					<TD style="width: 2%;"><INPUT TYPE="RADIO" id ="<?php echo $value['location_id']; ?>" NAME="region" VALUE="<?php echo $value['location_id']; ?>"></TD>
					<TD style="width: 30%;"><LABEL for="region"><?php echo $value['location_description']; ?></LABEL></TD>
				</TR>
				<?php
					}//End loop through regions
				?>
				<TR>
					<TD COLSPAN="3"><BR /><BR />*&nbsp;<SPAN CLASS = "eng">Your language preference</SPAN> / <SPAN CLASS = "fre">Votre langue préférée</SPAN></TD></TR>
				<TR>
				<!-- only one language allowed -->
				<?php
					foreach ($allLanguages as $value)
					{
				?>
					<TD style="width: 21%;">&nbsp;</TD>
					<TD style="width: 2%;"><INPUT TYPE="RADIO" NAME="lang" ID="<?php echo $value['id']; ?>" VALUE="<?php echo $value['id']; ?>"></TD>
					<TD style="width: 30%;"><LABEL for="lang"><?php echo $value['lang']; ?></LABEL></TD>
				</TR>

				<?php
					}//End loop through languages
				?>
			</TABLE><BR /><BR />

			<DIV style="width: 100%;">*&nbsp;<SPAN CLASS = "eng">Which of the following areas are you interested in volunteering for? You can check several boxes,
			or simply "Any of these options"</SPAN> / <SPAN CLASS = "fre">Lesquelles des options suivantes vous intéresseraient comme bénévole? Vous pouvez en sélectionner plusieurs, ou tout
			simplement "N'importe laquelle"</SPAN></DIV>

			<BR />
			<TABLE STYLE="font-size: 12px;">
			<!-- 1..7 interests allowed -->
			<?php
				foreach ($allInterests as $value)
				{
			?>
				<TR>
					<TD style="padding-left: 20px; width: 5%;">
					<INPUT TYPE="CHECKBOX" NAME="interests" ID="interests" VALUE="<?php echo $value['interest_id']; ?>"></INPUT></TD>
					<TD style="width: 50%;"><LABEL for="interests"><?php echo interestNumToText($value['interest_id']); ?></LABEL></TD>
				</TR>
				<?php
				//End loop through interests
					}
				?>
				<TR>
					<TD COLSPAN="2"><font size="1"><SPAN CLASS = "eng">Everyone who signs up receives the newsletter automatically.</font></SPAN> /
					<SPAN CLASS = "fre"><font size="1">Toute personne qui s'inscrit recevra automatiquement le bulletin.</font></SPAN></TD>
				</TR>
			</TABLE>
		</FIELDSET><BR /><BR />
		<FIELDSET style = "border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
			<DIV style="clear: both; width: 40%; FLOAT: LEFT;"><SPAN CLASS = "eng">Any other information? Comments? Questions?</SPAN> /
				<SPAN CLASS = "fre">D'autres informations? Des commentaires ou questions?</SPAN>
			</DIV>
			<DIV><TEXTAREA ID="comments" NAME="comments" COLS="30" ROWS="3"></TEXTAREA></DIV>
			<BR /><BR />
		</FIELDSET>
		<DIV style="padding-top: 10px">
		<TABLE>
			<TR>
				<TD><INPUT TYPE="button" ID="submit" NAME="submit" VALUE="SEND"></INPUT></TD>
				<TD><SPAN CLASS = "eng">&nbsp;&nbsp;Please click SEND once only, then wait for the message!</SPAN>
				<BR />
				<SPAN CLASS = "fre">&nbsp;&nbsp;Veuillez cliquer une fois sur SEND, et ensuite attendre le message!</SPAN></TD>
			</TR>
			<TR>
				<TD COLSPAN="2"><BR/>
					<SPAN CLASS = "eng">If you have any problems with this registration form, please contact <a href="mailto: postmaster@servethecitygeneva.ch">info@servethecitygeneva.ch</a>.</SPAN>
				</TD>
			</TR>
			<TR>
				<TD COLSPAN="2"><BR/>
					<SPAN CLASS = "fre">Si vous avez quelque problème que ce soit avec ce formulaire, veuillez contacter <a href="mailto: postmaster@servethecitygeneva.ch">info@servethecitygeneva.ch</a>.</SPAN>
				</TD>
			</TR>
		</TABLE>
		</DIV>
</FORM>