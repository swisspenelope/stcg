<?php
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

/* Only one Event should be active at any time, open set to 1 in `event` table. */

/****************************************************************************************/

?>
<title>
Volunteer email and password entry page
</title>
<?php
//form that receives password input and leads to page listing all this member's details

?>
<!-- /***************************************************************************************/ -->
<script type="text/javascript" charset="utf-8" src="scripts/stcg-vol1.js" async></script>
</head>
<body>
<?php
//var_dump($event);
?>
<h2>Returning volunteer login page</h2>
<h2 class="fre">Page de login pour nos bénévoles existants</h2>
<form id="RETMEM" name="RETMEM" method='post' action='stcg-vol2-authenticated.php'>
<fieldset>
<p>Please enter your email address and password, then click <strong>Log in</strong>.</p>
<p class="fre">Veuillez saisir votre adresse courriel et votre mot de passe, puis cliquer sur <strong>Log in</strong>.</p>
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
            <td>
                <input title="Enter your password.  Saisir votre mot de passe." type="password" id="pwd1" name="pwd1" size="25" onchange="if(this.value != '') {var em = document.getElementById('email').value; var newString = em + ' ' + this.value; callAjax('checkLoginPwd', newString, this.id);}">
                <input type="checkbox" id="valid_pwd1" disabled name="valid_pwd1"><div id="rsp_pwd1"><!-- --></div>
            </td>
        </tr>
	<tr>
            <td colspan="3">
        <p>If you have forgotten your password, it is either your first name in lowercase (by default), or if that doesn't work, email the webmaster at <a href="mailto: postmaster@servethecitygeneva.ch">postmaster@servethecitygeneva.ch</a> for a new one.</p><p class="fre">Si vous avez oublié votre mot de passe, il est par défault votre prénom en minuscule. Si cela ne marche pas, envoyer un courriel au webmaster à <a href="mailto: postmaster@servethecitygeneva.ch">postmaster@servethecitygeneva.ch</a> pour en recevoir un nouveau.</p>
            </td>
        </tr>
</table>    
<div>
<input type='button' id='back' name='back' value='Back to Home Page'>
<input type='button' id='login' name='login' value='Log in'></div>
</fieldset>
</form>
<!-- /***************************************************************************************/ -->
    <?php
    include_once 'footer.php';
    ?>