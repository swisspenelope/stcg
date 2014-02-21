<?php
/*
Changelog:
01.2014 new validation cases added
23.01.2014 set $_SESSION['user'] in checkLoginPassword case
*/

require_once 'stcg-config.php';
require_once 'stcg-data-layer.php';
include_once 'scripts/class.xmlResponse.php';
require_once 'stcg-password-handling.php';

session_start();

// check that all POST variables have been set
if(!isset($_POST['method']) || !$method = $_POST['method']) exit;
if(!isset($_POST['value']) || !$value = $_POST['value']) exit;
if(!isset($_POST['target']) || !$target = $_POST['target']) exit;

$value = $_POST['value'];
$id = $_POST['target'];

$passed = false;
$retval = '';

switch($method)
{
	case 'checkEmail':
		$format = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+/";
		if (!preg_match($format, $value))
		{
			$retval = "Please enter a valid email address.";
		}
		else
		{
			$passed = true;
		}
		break;

		case 'checkRegistrationEmail':
		$format = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+/";
		if (!preg_match($format, $value))
		{
			$retval = "Not a valid email address! Please delete what you typed and type it again.";
		}
		else
		{
			if(!emailVerify($value))
			{
				$passed = true;
			}
			else
			{
				$retval = "Sorry! This email address is in our database already! If you don't know your password, please email postmaster@servethecitygeneva.ch to get a new one.";
			}
		}
		break;

	case 'checkLoginEmail':
		$format = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+/";
		if (!preg_match($format, $value))
		{
			$retval = "Not a valid email address! Please delete what you typed and type it again.";
		}
		else
		{
			if(!emailVerify($value))
			{
				$retval = "We can find no one with this email address. Please try another, or register as a new volunteer.";
			}
			else
			{
				$passed = true;
			}
		}
		break;

		case 'checkLoginPwd':
		$entireLogin = explode(" ", $value);
			if (!existingPwdVerify($entireLogin[0], $entireLogin[1]))
			{
				$retval = "Wrong password. Please delete what you typed and try again.";
			}
			else
			{
				$passed = true;
				$_SESSION['user'] = $entireLogin[0];
			}
			break;

		case 'checkPwd1':
			$pass1 = $value;
			$passed = true;
			break;


		case 'checkPwd2':
			$newVal = explode(" ", $value);
			if ( $newVal[0] != $newVal[1] )
			{
				$retval = "Your second password does not match the first. Please try again.";
			}
			else
			{
				$passed = true;
			}
			break;

		case 'checkOldPwd':
			$newVal = explode(" ", $value);
			if (!password_verify($newVal[1], $newVal[0]))
			{
				$retval = "This is not your current password. Please try again.";
			}
			else
			{
				$passed = true;
			}
			break;

	default: exit;
}

$xml = new xmlResponse();
$xml->start();

// set the response text
$xml->command('setcontent',
		array('target' => "rsp_$target", 'content' => htmlentities($retval))
);

if($passed)
{
	// set the message colour to green and the checkbox to checked

	$xml->command('setstyle',
			array('target' => "rsp_$target", 'property' => 'color', 'value' => 'green')
	);
	$xml->command('setproperty',
			array('target' => "valid_$target", 'property' => 'checked', 'value' => 'true')
	);

}
else
{
	// set the message colour to red, the checkbox to unchecked and focus back on the field

	$xml->command('setstyle',
			array('target' => "rsp_$target", 'property' => 'color', 'value' => 'red')
	);
	$xml->command('setproperty',
			array('target' => "valid_$target", 'property' => 'checked', 'value' => 'false')
	);
	$xml->command('focus', array('target' => $target));
}

$xml->end();
?>