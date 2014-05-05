<?php
/*
session handing added for security 2014.01.29
*/
require_once 'header.php';
session_destroy();
/*
if (isset($_SESSION['user']))
{
	session_destroy();
	//echo "session has been destroyed.";
}*/

/******************** CALL GET EVENT FUNCTION TO DISPLAY LATEST EVENT ******************/
/****************************************************************************************/
$connectionObject = connect();
$event = getLatestEvent($connectionObject);

/* Only one Event may be active at any time, the latest Event by #ID in `event` table. */

/****************************************************************************************/

?>
<title>
Volunteer email and password entry page
</title>
<?php
//form that receives password input and leads to page listing all this member's details

?>
<!-- /***************************************************************************************/ -->
<script type="text/javascript" charset="utf-8">
$(document).ready(function ()
{
	$("#email").focus();

	$("#back").click(function ()
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
	$("#login").click(function ()
	{
		var isOK = true;
		if(document.forms["RETMEM"].elements["email"].value == "" || !document.forms["RETMEM"].elements["valid_email"].checked)
		  {
			document.forms["RETMEM"].elements["email"].focus();
			isOk = false;
			return;
		  }

		  if(document.forms["RETMEM"].elements["pwd1"].value == "" || !document.forms["RETMEM"].elements["valid_pwd1"].checked)
		  {
			  document.forms["RETMEM"].elements["pwd1"].focus();
			  isOk = false;
			return;
		  }

		  location.replace("stcg-vol2-authenticated.php");
	  });
/*	if ($("#valid_email").is(':checked') && $("#valid_pwd1").is(':checked'))
	{
		document.getElementById("login").setAttribute("disabled", false);
	}
	else
	{
		document.getElementById("login").setAttribute("disabled", true);
	}*/
});

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
</head>
<body>
<?php
//var_dump($event);
?>
<h2>Returning volunteer login page</h2>
<h2 class="fre">Page de login pour nos bénévoles existants</h2>
<form id="RETMEM" name="RETMEM" method='post' action='stcg-vol2-authenticated.php'>
<fieldset>
<p>Please enter your email address and password, then click <strong>Log in to see your private information</strong>.</p>
<p class="fre">Veuillez saisir votre adresse courriel et votre mot de passe, puis cliquer sur <strong>Log in to see your private information</strong>.</p>
<table>
	<tr>
		<td><span class = "eng">Email</span> / <span class = "fre">Courriel</span></td>
		<td style="width: 2%;">*</td>
		<td><input type="text" title="Your email address.  Votre adresse courriel." placeholder = "Enter a valid email address" name="email" id="email" size="32" onchange="if(this.value != '') {callAjax('checkLoginEmail', this.value, this.id);}">
		<input type="checkbox" id="valid_email" disabled name="valid_email"></input>
			<div id="rsp_email"><!-- --></div></td>
	</tr>
	<tr>
	<td><span class = "eng">Password</span> / <span class = "fre">Mot de passe</span></td>
	<td style="width: 2%;">*</td>
	<td><input title="Enter your password.  Saisir votre mot de passe." type="password" id="pwd1" name="pwd1" size="25" onchange="if(this.value != '') {var em = document.getElementById('email').value; var newString = em + ' ' + this.value; callAjax('checkLoginPwd', newString, this.id);}">
	<input type="checkbox" id="valid_pwd1" disabled name="valid_pwd1">
		<div id="rsp_pwd1"><!-- --></div></td>
</tr>
</table>    
<div><input type='button' id='login' name='login' value='Log in to see your private information'>
<input type='button' id='back' name='back' value='Back to Home Page'></div>
</fieldset>
</form>
<!-- /***************************************************************************************/ -->
<?php
//end login form
?>