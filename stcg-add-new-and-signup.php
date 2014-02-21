<?php
include_once 'header.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php

/*********************** CALL GET LOCATIONS, INTERESTS AND LANGUAGES ********************/
$connectionObject = connect();

$allLocations = getAllLocations($connectionObject);
$allLanguages = getAllLanguages($connectionObject);
$allInterests = getAllInterestsButUnknown($connectionObject);

/****************************************************************************************/
?>
<title>Add Volunteer and Go to Event</title>

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
	insertMemberInDatabase(myCallback);
 }

////////// NEW APPROACH: SAVE TO DB USING AJAX /////////////////////////
function myCallback(response)
{
	if (response > 0)
	{
		alert("Thank you for your registration! / Merci de votre inscription!");
		window.location.href="stcg-new-vol6-signup-from-new.php#top";
	}
}

function myCallbackError(jqXHR, textStatus, errorThrown )
{
/*		alert("The system was unable to process your registration. Please contact postmaster@servethecitygeneva.ch so that we can solve the problem. /" +
				"Le système n'a pas pu complèter votre inscription. Veuillez contacter postmaster@servethecitygeneva.ch pour que nous puissions résoudre ce problème.\n" +
		textStatus  + " " + errorThrown);*/
		alert("Thank you! / Merci! " + textStatus + " " + errorThrown);//JSON parse unexepcted char in the errorThrown)
		window.location.href="stcg-new-vol6-signup-from-new.php#top";
}
/*Error callback is called on http errors, but also if JSON parsing on the response fails.
This is what's probably happening if response code is 200 but you still are thrown to error callback.*/

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
<body>
	<!--p>you are now in the page stcg-add-new-and-signup</p-->
	<table>
		<tr>
			<td><h3 class="eng">Sign up with Serve The City Geneva</h3></td>
			<td><h3 class="fre">Inscrivez-vous chez Serve The City
					Geneva</h3></td>
		</tr>
		<tr>
			<td><p class="eng">
					<b>* means this information<br />MUST be entered
					</b>
				</p></td>
			<td><p class="fre">
					<b>* signifie que cette information<br />est OBLIGATOIRE
					</b>
				</p></td>
		</tr>
	</table>
	<input type="button" id="back" value="Back"></input>
	<br />
	<br />
	<!-- /****************************************************************************************/-->
	<form name="NEWMEM" id="NEWMEM" method="post"
		action="non-Ajax form handler">
		<fieldset
			style="border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
			<legend>
				&nbsp;<span class="eng"><b>Your contact details</b></span>&nbsp;/&nbsp;<span
					class="fre"><b>Vos coordonnées</b></span>&nbsp;
			</legend>

			<table border="0">
				<tr>
					<td style="width: 40%;"><span class="eng">How did you
							hear about us?</span> / <span class="fre">Comment avez-vous
							découvert STCG?</span></td>
					<td style="width: 2%;">*</td>
					<td style="width: 10%;"><textarea id="source" name="source"
							title="Please enter a brief word about where you heard of us.  Veuillez nous dire brièvement comment vous nous avez découvert."
							cols="30" rows="3"></textarea></td>
				</tr>
				<tr>
					<td><span class="eng">First Name</span> / <span class="fre">Prénom</span></td>
					<td>*</td>
					<td><input type="text" title="Your first name.  Votre prénom."
						id="first" name="first" size="30"
						onchange="this.value = this.value.replace(/^\s+|\s+$/g, ''); valid_first.checked = this.value;" />
						<input type="checkbox" disabled="disabled" name="valid_first" /></td>
				</tr>
				<tr>
					<td><span class="eng">Last Name</span> / <span class="fre">Nom
							de famille</span></td>
					<td>*</td>
					<td><input type="text"
						title="Your last name.  Votre nom de famille." id="last"
						name="last" size="30"
						onchange="this.value = this.value.replace(/^\s+|\s+$/g, ''); valid_last.checked = this.value;" />
						<input type="checkbox" disabled="disabled" name="valid_last" /></td>
				</tr>
				<tr>
					<td><span class="eng">Email</span> / <span class="fre">Courriel</span></td>
					<td>*</td>
					<td><input type="text"
						title="Your email address.  Votre adresse courriel." name="email"
						id="email" size="25"
						onchange="if(this.value != '') {callAjax('checkEmail', this.value, this.id);}" />
						<input type="checkbox" id="valid_email" disabled="disabled"
						name="valid_email"></input>
						<div id="rsp_email">
							<!-- -->
						</div></td>
				</tr>
				<tr>
					<td><span class="eng">Create a password</span> / <span
						class="fre">Créér un mot de passe</span></td>
					<td style="width: 2%;">*</td>
					<td><input title="Create a password.  Créér un mot de passe."
						type="password" id="pwd1" name="pwd1" size="25"
						onchange="if(this.value != '') {callAjax('checkPwd1', this.value, this.id);}" />
						<input type="checkbox" id="valid_pwd1" disabled="disabled"
						name="valid_pwd1"></input>
						<div id="rsp_pwd1">
							<!-- -->
						</div></td>
				</tr>
				<tr>
					<td style="width: 40%;"><span class="eng">Repeat your
							password</span> / <span class="fre">Mot de passe encore</span></td>
					<td style="width: 2%;">*</td>
					<td><input
						title="Repeat the password you created above.  Répéter le mot de passe créé ci-dessus."
						type="password" id="pwd2" name="pwd2" size="25"
						onchange="if(this.value != '') {
					var pword1 = document.getElementById('pwd1').value; var newString = pword1 + ' ' + this.value; callAjax('checkPwd2', newString, this.id);}" />
						<input type="checkbox" id="valid_pwd2" disabled="disabled"
						name="valid_pwd2"></input>
						<div id="rsp_pwd2">
							<!-- -->
						</div></td>
				</tr>
				<tr>
					<td style="width: 40%;"><span class="eng">Phone number</span>
						/ <span class="fre">Numéro de tél.</span></td>
					<td>&nbsp;</td>
					<td><input type="text" title="Phone number" id="phone"
						name="phone" size="25" />
					</td>
				</tr>
				<tr>
					<td style="clear: both; width: 40%;"><span class="eng">Organization,
							Association</span> / <span class="fre">Organisation, Association</span></td>
					<td>&nbsp;</td>
					<td colspan="2"><input type="text" id="org" name="org"
						size="30" /></td>
				</tr>
			</table>
		</fieldset>
		<br />
		<br />

		<fieldset
			style="border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
			<legend>
				&nbsp;<span class="eng"><b>Your preferences</b></span>&nbsp;/&nbsp;<span
					class="fre">Vos préférences<b></b></span>&nbsp;
			</legend>
			<table border="0">
				<tr>
					<td colspan="3">*&nbsp;<span class="eng">Where do you
							live?</span> / <span class="fre">Où habitez-vous?</span></td>
				</tr>
				<tr>
					<!-- only one region allowed -->
					<?php
					foreach ($allLocations as $value)
					{
				?>
					<td style="width: 21%;">&nbsp;</td>
					<td style="width: 2%;"><input type="radio"
						id="<?php echo $value['location_id']; ?>" name="region"
						value="<?php echo $value['location_id']; ?>" /></td>
					<td style="width: 30%;"><label for="region"><?php echo $value['location_description']; ?></label></td>
				</tr>
				<?php
					}//End loop through regions
				?>
				<tr>
					<td colspan="3"><br />
					<br />*&nbsp;<span class="eng">Your language preference</span> / <span
						class="fre">Votre langue préférée</span></td>
				</tr>
				<tr>
					<!-- only one language allowed -->
					<?php
					foreach ($allLanguages as $value)
					{
				?>
					<td style="width: 21%;">&nbsp;</td>
					<td style="width: 2%;"><input type="radio" name="lang"
						id="<?php echo $value['id']; ?>"
						value="<?php echo $value['id']; ?>" /></td>
					<td style="width: 30%;"><label for="lang"><?php echo $value['lang']; ?></label></td>
				</tr>

				<?php
					}//End loop through languages
				?>
			</table>
			<br />
			<br />

			<div style="width: 100%;">
				*&nbsp;<span class="eng">Which of the following areas are you
					interested in volunteering for? You can check several boxes, or
					simply "Any of these options"</span> / <span class="fre">Lesquelles
					des options suivantes vous intéresseraient comme bénévole? Vous
					pouvez en sélectionner plusieurs, ou tout simplement "N'importe
					laquelle"</span>
			</div>

			<br />
			<table style="font-size: 12px;">
				<!-- 1..7 interests allowed -->
				<?php
				foreach ($allInterests as $value)
				{
			?>
				<tr>
					<td style="padding-left: 20px; width: 5%;"><input
						type="checkbox" name="interests" id="interests"
						value="<?php echo $value['interest_id']; ?>"></input></td>
					<td style="width: 50%;"><label for="interests"><?php echo interestNumToText($value['interest_id']); ?></label></td>
				</tr>
				<?php
				//End loop through interests
					}
				?>
				<tr>
					<td colspan="2"><span class="eng"><font size="1">Everyone
								who signs up receives the newsletter automatically.</font></span> / <span
						class="fre"><font size="1">Toute personne qui s'inscrit recevra
							automatiquement le bulletin.</font>
					</span></td>
				</tr>
			</table>
		</fieldset>
		<br />
		<br />
		<fieldset
			style="border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
			<div style="clear: both; width: 40%; FLOAT: LEFT;">
				<span class="eng">Any other information? Comments? Questions?</span>
				/ <span class="fre">D'autres informations? Des commentaires
					ou questions?</span>
			</div>
			<div>
				<textarea id="comments" name="comments" cols="30" rows="3"></textarea>
			</div>
			<br />
			<br />
		</fieldset>
		<div style="padding-top: 10px">
			<table>
				<tr>
					<td><input type="button" id="submit" name="submit"
						value="SEND"></input></td>
					<td><span class="eng">&nbsp;&nbsp;Please click SEND
							once only, then wait for the message!</span> <br /> <span class="fre">&nbsp;&nbsp;Veuillez
							cliquer une fois sur SEND, et ensuite attendre le message!</span></td>
				</tr>
				<tr>
					<td colspan="2"><br /> <span class="eng">If you have
							any problems with this signup form, please contact <a
							href="mailto: postmaster@servethecitygeneva.ch">postmaster@servethecitygeneva.ch</a>.
					</span></td>
				</tr>
				<tr>
					<td colspan="2"><br /> <span class="fre">Si vous avez
							quelque problème que ce soit avec ce formulaire, veuillez
							contacter <a href="mailto: postmaster@servethecitygeneva.ch">postmaster@servethecitygeneva.ch</a>.
					</span></td>
				</tr>
			</table>
		</div>
	</form>
	<div id="answer"></div>
</body>
</html>