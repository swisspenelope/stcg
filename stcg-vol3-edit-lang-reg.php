<?php

require_once 'header.php';

/************ CALL GET ALL LOCS AND LANGS **************** DO SESSION CHECK *************/
/****************************************************************************************/
$connectionObject = connect();

$allLocations = getAllLocations($connectionObject);

$allLanguages = getAllLanguages($connectionObject);

if (!isset($_SESSION['user']))
{
	redirect_to("stcg-vol1-login.php");
}
/****************************************************************************************/
?>

<TITLE>
Change language or region of authenticated member
</TITLE>
<script type="text/javascript">
var theRegion = 0;
var theLang = 0;

$(document).ready(function ()
{
	$("#back").click(function ()
	{
        window.location.href="stcg-vol2-authenticated.php";
	});


	$("input:radio[name=region]").click(function()
	{
		//alert($(this).val());
		theRegion = $(this).val();
	});

	$("input:radio[name=lang]").click(function()
	{
		//alert($(this).val());
		theLang = $(this).val();
	});

	$('#submit').on('click', function (event)
	{
		checkForm(this);
	});
});
</script>

<script type="text/javascript">
////THIS CHECKFORM IS FOR EDITING EXISTING DATA ONLY
function checkForm(form)
{
 	var isOk = true;

	  if (!$("input[name=region]:checked").val() > 0)
	  {
			alert("Please select your geographical region.");
			isOk = false;
		return;
	  }

	  if (!$("input[name=lang]:checked").val() > 0)
	  {
			alert("Please select your language preference.");
			isOk = false;
		return;
	  }
		document.forms["CHANGE_VOL_LANGREG"].elements["submit"].disabled=true;
		document.forms["CHANGE_VOL_LANGREG"].elements["submit"].value='Sending, please wait ...';
		updateThisMemberLangReg();
 }
////////// SAVE TO DB USING AJAX /////////////////////////
function myCallback(response)
{
	if (response == 1)//changes 1 table
	{
		alert("Your changes have been made! / Vos modifications ont été faites!");
		window.location.href="stcg-vol2-authenticated.php";
	}
}

function myCallbackError(jqXHR, textStatus, errorThrown )
{
		alert("The system was unable to make your changes. Please contact webmaster@servethecitygeneva.ch so that we can solve the problem. /" +
				"Le système n'a pas pu faire vos modifications. Veuillez contacter webmaster@servethecitygeneva.ch pour que nous puissions résoudre ce problème.\n" +
		textStatus + " " + errorThrown);
}

function updateThisMemberLangReg()
{
		var data = "lang=" + theLang + "&region=" + theRegion + "&memId=" + document.getElementById('memId').value;
//alert(data);

        $.ajax({
            dataType: 'json',
            url: 'stcg-json-responses.php?fct=updateThisMemberLangReg',
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
//FORM THAT ENABLES MEMBER TO CHANGE INCORRECT LANG OR REGION DATA IN DATABASE
?>
<!-- /****************************************************************************************/ -->
<FORM NAME="CHANGE_VOL_LANGREG" ID="CHANGE_VOL_LANGREG" METHOD="POST" ACTION="non-Ajax form handler">
	<DIV><H3>To make a change, highlight the incorrect text in the box, then type the right information in its place.</H3>
	<P>When you have finished making corrections, click the <b>Save all my changes</b> button.</P></DIV>
	<BR />
			<FIELDSET style ="border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
				<LEGEND>&nbsp;<SPAN CLASS = "eng"><B>Language and Region</SPAN>&nbsp;/&nbsp;<SPAN CLASS = "fre">Langue et Région</B></SPAN>&nbsp;</LEGEND>
				<TABLE>
					<TR>
						<TD COLSPAN="4"><INPUT TYPE="HIDDEN" name="memId" ID="memId" value="<?php echo $_SESSION['memberId']; ?>"></TD>
					</TR>
					<TR><!--ROW 1 TITLE-->
						<TD COLSPAN="4">&nbsp;*&nbsp;<SPAN CLASS = "eng">Your language preference</SPAN> / <SPAN CLASS = "fre">Votre langue préférée</SPAN></TD>
					</TR>
					<TR><!--ROW 2 DETAILS-->
						<TD STYLE="WIDTH: 40%;"><!--COL 1 OLD LANG-->
							<INPUT type="text" DISABLED NAME="existingLang" ID="lang" SIZE = "25" value="<?php echo langNumToText($_SESSION['language_id']) ?>">
						</TD>
						<!-- only one language allowed -->
						<TD STYLE="WIDTH: 60%"><!--COL 2 TABLE OF LABELS AND VALUES-->
						<TABLE>
						<?php
						foreach ($allLanguages as $value)
						{
						?>
						<TR>
							<TD STYLE="80%"><LABEL for="lang"><?php echo $value['lang']; ?></LABEL></TD>
							<TD STYLE="20%"><INPUT TYPE="RADIO" NAME="lang" ID="<?php echo $value['id']; ?>" VALUE="<?php echo $value['id']; ?>"></TD>
						</TR>
						<?php
						}//End loop through languages
						?>
						</TABLE>
						</TD>
					</TR>
					<TR><!--ROW 1 TITLE-->
						<TD COLSPAN="4">&nbsp;*&nbsp;<SPAN CLASS = "eng">Where do you live?</SPAN> / <SPAN CLASS = "fre">Où habitez-vous?</SPAN></TD>
					</TR>
					<TR><!--ROW 2 DETAILS-->
						<TD><!--COL 1 OLD REGION-->
							<INPUT type="text" DISABLED name="existingRegion" SIZE = "25" value="<?php echo locationNumToText($_SESSION['location_id']); ?>">
						</TD>
						<!-- only one region allowed -->
						<TD><!--COL 2 TABLE OF LABELS AND VALUES-->
						<TABLE>
						<?php
						foreach ($allLocations as $value)
						{
						?>
						<TR>
							<TD STYLE="80%"><LABEL for="region"><?php echo $value['location_description']; ?></LABEL></TD>
							<TD STYLE="20%"><INPUT TYPE="RADIO" id ="<?php echo $value['location_id']; ?>" NAME="region" VALUE="<?php echo $value['location_id']; ?>"></TD>
						</TR>
						<?php
						}//End loop through regions
						?>
						</TABLE>
						</TD>
					</TR>
				</TABLE>
			</FIELDSET><BR />
		<DIV>
			<DIV><INPUT TYPE="button" ID="back" NAME="back" VALUE="Back to Volunteer Account"><INPUT TYPE="button" ID="submit" NAME="submit" VALUE="SAVE"></DIV>
			<DIV><SPAN CLASS = "eng">&nbsp;&nbsp;Please click SAVE once only, then wait for the message!</SPAN>
				<BR />
				<SPAN CLASS = "fre">&nbsp;&nbsp;Veuillez cliquer une fois sur SAVE, et ensuite attendre le message!</SPAN>
			</DIV>
		</DIV>
	</FORM><!-- END CHANGE LANG OR REG FORM -->