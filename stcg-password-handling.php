<?php
/*
Changelog:
01.2014 handling for the new validation cases added stcg-ajax-validation.php
*/

require 'password_compat-master/lib/password.php';
require_once 'stcg-data-layer.php';

/* password_verify returns 1 if true, no output if false */

function do_crypt($inputPassword)
{
	//echo $inputPassword . "\n";
	$crypted_password = password_hash($inputPassword, PASSWORD_DEFAULT);
	//echo $crypted_password ."\n";
	return $crypted_password;
}

function emailVerify($email)
{
	$connectionObject = connect();
	$thisMember = getThisMemberByEmail($connectionObject, $email);
	if (!$thisMember)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function existingPwdVerify($email, $inputPwd)
{
	$connectionObject = connect();
	$thisMember = getThisMemberByEmail($connectionObject, $email);
	if (password_verify($inputPwd, $thisMember['password']))
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**************************************************************************************************************/

function pwdVerify($inputPwd, $memberId)
{
	$connectionObject = connect();
	$thisMember = getThisMemberByMemberId($connectionObject, $memberId);

	if (password_verify($inputPwd, $thisMember['password']))
	{
		return true;
	}
	else
	{
		return false;
	}
}
//echo do_crypt('pene');
?>