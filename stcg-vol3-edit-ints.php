<?php

include_once 'header.php';

$selectedInts = $_SESSION['ints'];//array of int nums is $selectedInts
$numSelected = $_SESSION['numberOfInts'];//int number of selected ints in the array

/************ CALL GET ALL INTERESTS ******************** DO SESSION CHECK *************/
/****************************************************************************************/
$connectionObject = connect();
$allInterests = getAllInterestsButUnknown($connectionObject);

if (!isset($_SESSION['user']))
{
	redirect_to("stcg-vol1-login.php");
}

/****************************************************************************************/
$_SESSION['countAll'] = count($allInterests);//all the interests from interests reference table
$_SESSION['allInts'] = makeInterestStringFromArray($allInterests);//string of all int nums, 1-8

?>
<TITLE>
Change interest areas of authenticated member
</TITLE>
<script type="text/javascript">
//var theInts = [];
//var intString = "";

$(document).ready(function ()
{
   $("#back").click(function ()
	{
        window.location.href="stcg-vol2-authenticated.php";
	});

 $("input[name='ints']").on('click', function()
	{
		if ( $(this).val() == 7)
		{
			var doCheck = $(this).is(':checked');
			//:checked is either false or true
			$("input[name='ints']").each(function()
			{
				$(this).prop('checked', doCheck);
			});
		}
		else if ($(this).val() < 7)
		{
			$( "input[name='ints']:eq(7)" ).prop('checked', true);
		}
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

	if (!$("input[name=ints]:checked").val() > 0)
	  {
			alert("Please select your areas of interest.");
			isOk = false;
		return;
	  }
	  else
	  {
                document.forms["CHANGE_VOL_INTS"].elements["submit"].disabled=true;
		document.forms["CHANGE_VOL_INTS"].elements["submit"].value='Sending, please wait ...';	
                updateThisMemberInts();
	  }
}

////////// SAVE TO DB USING AJAX /////////////////////////
function myCallback(response)
{
	if (response == 1)
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

function updateThisMemberInts()//sends an array
{
		var ints="";
		$("input[name='ints']:checked").each(function()
		{
			//if you don't use "push", js forgets that this is an array.
			ints=ints+"&ints[]="+$(this).val();
		});

    	var data = "memId=" + document.getElementById('memId').value + ints;

        $.ajax({
            dataType: 'json',

			url: 'stcg-json-responses.php?fct=updateThisMemberInterests',
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
<FORM NAME="CHANGE_VOL_INTS" ID="CHANGE_VOL_INTS" METHOD="POST" ACTION="non-Ajax form handler">
	<FIELDSET style ="border: solid black 1px; width: 92%; padding: 20px; padding-top: 20px">
		<LEGEND>&nbsp;<SPAN CLASS = "eng"><B>Interests</SPAN>&nbsp;/&nbsp;<SPAN CLASS = "fre">Intérêts</B></SPAN>&nbsp;</LEGEND>
		<TABLE>
			<TR><!--ROW 1 TITLE-->
				<TD COLSPAN="4">&nbsp;*&nbsp;<SPAN CLASS = "eng">
				Which of the following areas are you interested in volunteering for?</SPAN> / <SPAN CLASS = "fre">Lesquelles des options suivantes vous intéresseraient comme bénévole?</SPAN></TD>
			</TR>
			<TR>
				<TD COLSPAN="4" STYLE="FONT-SIZE: 11px;">
					You can check several boxes, or simply <b>Any of these options</b> / <SPAN CLASS = "fre">Vous pouvez en sélectionner plusieurs, ou tout
					simplement <b>N'importe laquelle</b></SPAN>
				</TD>
			</TR>
			<TR><!--ROW 2 DETAILS-->
				<TD><!--COL 1 EXISTING INTERESTS-->
					<SELECT style="width: 200px;" MULTIPLE DISABLED name="interests[]" size="<?php echo $numSelected; ?>">
					<?php for($i = 0; $i < $numSelected; $i++)
					{
					?>
					<OPTION id ="<?php echo $i; ?>"><?php echo interestNumToText($_SESSION['stringInts'][$i]); ?></OPTION>
					<?php
					}
					?>
					</SELECT>
				</TD>
				<TD><!--COL 2 TABLE OF LABELS AND VALUES-->
					<TABLE><!-- 1..7 interests allowed OR newsletter only -->
					<?php
						foreach ($allInterests as $value)
						{
					?>
					<TR>
						<TD STYLE="80%"><LABEL for="interests"><?php echo interestNumToText($value['interest_id']); ?></LABEL></TD>
						<TD STYLE="20%"><INPUT TYPE="CHECKBOX" NAME="ints" ID="ints" VALUE="<?php echo $value['interest_id']; ?>"></TD>
					</TR>
					<?php
						//End loop through interests
					}
					?>
					</TABLE>
				</TD>
			</TR>
			<TR>
				<TD COLSPAN="4"><INPUT TYPE="HIDDEN" name="memId" ID="memId" value="<?php echo $_SESSION['memberId']; ?>"></TD>
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
	</FORM>
	<!-- /****************************************************************************************/ -->
	<?php
	//END CHANGE PERSONAL INTERESTS FORM
	?>