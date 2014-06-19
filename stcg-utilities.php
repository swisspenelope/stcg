<?php

function alertMessage($message, $severity, $destination="stcg-error-messages.php", $obj=true)
{
	$_SESSION['message'] = "<DIV id='err' style='color: red;'><BR />". $message . "<BR /><BR /></DIV>";

	error_log($message . "\n");
	switch ($severity)
	{
		case SEV_CRITICAL :
			redirect_to('stcg-error-messages.php');
			break;
		case SEV_WARNING :
			redirect_to($destination);
			break;
		case SEV_INFO :
			break;
		case SEV_DEBUG:
		    if(!$obj)
		    {
                        error_log(print_r($obj));
		    }
		    break;
	}
}

function langNumToText($input) {
	$textLang = ' ';
	switch ($input) {
		case 1 :
			$textLang = "English only";
			break;
		case 2 :
			$textLang = "Uniquement le fran&ccedil;ais";
			break;
		case 3 :
			$textLang = "Both: pref. English";
			break;
		case 4 :
			$textLang = "Les deux: pr&eacute;f. fran&ccedil;ais";
			break;
	}
	return $textLang;
}

function locationNumToText($input) {
	$textRegion = ' ';
	switch ($input) {
		case 1 :
			$textRegion = "Geneva area";
			break;
		case 2 :
			$textRegion = "Neighboring France";
			break;
		case 3 :
			$textRegion = 'La C&ocirc;te';
			break;
	}
	return $textRegion;
}

function interestNumToText($input) {
	$textInterest = " ";
	switch ($input) {
	    case 0 :
	        $textInterest = "Unknown/Pas connu";
	        break;
	    case 1 :
			$textInterest = "The homeless/Sans abris";
			break;
		case 2 :
			$textInterest = "The elderly/Les aînés";
			break;
		case 3 :
			$textInterest = "Refugees/Réfugiés";
			break;
		case 4 :
			$textInterest = "The handicapped/Personnes handicapées";
			break;
		case 5 :
			$textInterest = "Children/Les enfants";
			break;
		case 6 :
			$textInterest = "Ecology/L'ecologie";
			break;
		case 7 :
			$textInterest = "Any of these options/N'importe laquelle de ces options";
			break;
		case 8 :
		    $textInterest = "Newsletter/Bulletin";
		    break;
	}
	return $textInterest;
}

function makeInterestStringFromArray($inputArray)
{
	$stringInts = "";
	foreach ($inputArray as $value)
	{
		$stringInts .= $value['interest_id'];
	}
	return $stringInts;
}

function dayNumtoText($input) {
	$textDay = " ";
	switch ($input) {
		case 4 :
			$textDay = "Thursday";
			break;
		case 5 :
			$textDay = "Friday";
			break;
		case 6 :
			$textDay = "Saturday";
			break;
		case 7 :
			$textDay = "Sunday";
			break;
	}
	return $textDay;
}

function activityArray()
{
	$array = array(
	0,5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100);
	return $array;
}

function printSingOrPlural($input) {
	$printString = "activity";
	if ($input > 1) {
		$printString = "activities";
	}
	return $printString;
}

function printProjectLeader($input)
{
	$pl = "";
	if ($input == 1 )
	{
		$pl = "Project leader";
	}
	return $pl;
}

function printNoShowIllness($input)
{
	$noShowIll = "";
	if ($input == 1 )
	{
		$noShowIll = "No show with excuses";
	}
	return $noShowIll;
}

function printNoShowNone($input)
{
	$noShowNone = "";
	if ($input == 1 )
	{
		$noShowNone = "No show, no warning";
	}
	return $noShowNone;
}

function redirect_to( $location = NULL )
{
	if ($location != NULL)
	{
		header("Location: {$location}");
		exit;
	}
}



?>