<?php
require_once 'stcg-config.php';
require_once 'stcg-utilities.php';
require_once 'stcg-data-layer.php';
require_once 'stcg-password-handling.php';
session_start();

$connectionObject = connect();

if (isset($_GET['fct']))
{
	if ($_GET['fct'] == 'endSession')
	{
		session_destroy();
		$result = true;
	}

	//call get members at event - used in Signups received grid on left
	if ($_GET['fct'] == 'getJSONMembersAtEvent')
	{
		if (isset($_GET['eventId']))
		{
			$result = getJSONMembersAtEvent($connectionObject, $_GET['eventId']);
			echo $result;
		}
	}
        
	//call get members assigned by organizer - used in Signups right-hand grid
	if ($_GET['fct'] == 'getJSONMembersAssignedToActivities')
	{
		if (isset($_GET['eventId']))
		{
			$result = getJSONMembersAssignedToActivities($connectionObject, $_GET['eventId']);
			echo $result;
		}
	}
        
        if ($_GET['fct'] == 'updateSelectedActivity')
        {
            if (isset($_GET['actId']))
            {
                $result = updateSelectedActivity($connectionObject, $_GET['activity_id'], $_GET['activity_name'], $_GET['activity_desc'], $_GET['activity_short_code'], $_GET['capacity'], $_GET['date'], $_GET['project_leader'], $_GET['open']);
                echo $result;
            }
        }
        
        if ($_GET['fct'] == 'insertNewActivityToNewEvent')
        {
            $result = insertNewActivityToNewEvent($connectionObject, $_GET['activity_name'], $_GET['activity_desc'], $_GET['activity_short_code'], $_GET['capacity'], $_GET['date'], $_GET['project_leader'], $_GET['open'], $_GET['evId']);
                echo $result;
        }
        
        if ($_GET['fct'] == 'insertMemberActivities')
        {
            $result = insertMemberActivities($connectionObject, $_GET['memId'], $_GET['acts']);
            echo $result;
        }
           
	//call get activity short-codes of all activities in the event - used in drop-down list on right
	if ($_GET['fct'] == 'getJSONAllSCsAtEvent')
	{
            if (isset($_GET['eventId']))
            {
                    $result = getJSONAllSCsAtEvent($connectionObject, $_GET['eventId']);
                    echo $result;
            }
	}
	//call get members in member_activity_selected table, for one activity - used in Activity grid on right
	if ($_GET['fct'] == 'getJSONSelectedMembersAtActivity')
	{
		if (isset($_GET['actId']) && (isset($_GET['eventId'])))
		{
			$result = getJSONSelectedMembersAtActivity($connectionObject, $_GET['eventId'], $_GET['actId']);
			echo $result;
		}
	}
	//call update members in member_activity_selected table, for one activity - used in Activity grid on right
	if ($_GET['fct'] == 'updateAllSelectedMembersAtActivity')
	{
		$result = updateAllSelectedMembersAtActivity($connectionObject, $_GET['actId'], $_GET['memId']);
		echo $result;
	}
        
        if ($_GET['fct'] == 'updateSelectedFlagforEvent')
        {
		$result = updateSelectedFlagforEvent($connectionObject, $_GET['actId'], $_GET['rowId']);
		echo $result;
	}
        
        if ($_GET['fct'] == 'updateDeletedFlagforEvent')
        {
		$result = updateDeletedFlagforEvent($connectionObject, $_GET['actId'], $_GET['rowId']);
		echo $result;
	}

	if ($_GET['fct'] == 'insertNewMemberDetails')
	{
		$result = insertNewMemberDetails($connectionObject, $_GET['last'], $_GET['first'], $_GET['org'], $_GET['email'], do_crypt($_GET['pass']), $_GET['phone'], $_GET['source'], $_GET['comments'], $_GET['region'], $_GET['lang'], $_GET['ints'], $_GET['acts']);
		echo $result;

		if ($result)
		{
		/*************************************** SET UP EMAIL VARIABLES *************************************/
			$volTo = $_GET['email'];//repeat volunteer's email back at them
			$volSubject = "Your registration as a volunteer / Votre inscription comme bénévole";//tell volunteer what the subject is
			$volAckString = "<p>Your registration with Serve The City Geneva has been successful! <br /><br/>We look forward to seeing you at our next Event. / <span class='fre'>Votre inscription à Serve The City Geneva a été effectué. <br /><br/>Nous nous réjouissons de vous rencontrer lors de notre action prochaine.</span></p>";

			//orgTo = 'EMAIL_ORG'. ', ' ;// note the comma
			//$orgTo = 'gvannatter@aol.com';
			$orgTo = ', ' . 'swisspenelope@gmail.com';
			$orgSubject = 'New registration with STCG / Nouvelle inscription chez STCG';
			$volNameString = $_GET['first'] . " " . $_GET['last'] . " has registered as a volunteer.<br /><br />";//for organizer to know who volunteered
			$volDetailsString = "<b>How you found STCG:&nbsp;&nbsp;</b>" . $_GET['source'] . "<br /><br />
			<b>First Name:&nbsp;&nbsp;</b>" . $_GET['first'] . "<br /><br />
			<b>Last Name:&nbsp;&nbsp;</b>" . $_GET['last'] . "<br /><br />
			<b>Organization:&nbsp;&nbsp;</b>" . $_GET['org'] . "<br /><br />
			<b>Email:&nbsp;&nbsp;</b>" . $_GET['email'] . "<br /><br />
			<b>Phone:&nbsp;&nbsp;</b>" . $_GET['phone'] . "<br /><br />
			<b>Comments:&nbsp;&nbsp;</b>" . $_GET['comments'];

			$orgMessage = "<html><body>" . $volNameString . $volDetailsString . "</body></html>";
			$volMessage = "<html><body>" . $volAckString . $volDetailsString . "</body></html>";
			/*************************************************************************************************/
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=utf8_general_ci' . "\r\n";
				$headers .= 'From: info@servethecitygeneva.ch' . "\r\n";
				$headers .= 'Reply-To: info@servethecitygeneva.ch' . "\r\n";
			/********************************** DO SPAMCHECK ***************************************/
			function spamcheck($field)
			{
			  //filter_var() sanitizes the e-mail
			  //address using FILTER_SANITIZE_EMAIL
			  $field=filter_var($field, FILTER_SANITIZE_EMAIL);

		  //filter_var() validates the e-mail address using FILTER_VALIDATE_EMAIL
		  if(filter_var($field, FILTER_VALIDATE_EMAIL))
			{
			return TRUE;
			}
		  else
			{
			return FALSE;
			}
		}

			if (isset($_GET['email']))//if "email" is filled out, proceed
			{
			  //check if the email address is invalid
					$mailcheck = spamcheck($_GET['email']);
					if ($mailcheck==FALSE)
					{
						//do nothing echo "Invalid input";
					}
					else
					{//send email
						/********************************** DO EMAIL TO VOLUNTEER ***************************************/
						mail($volTo,$volSubject,$volMessage,$headers);//email volunteer
						/********************************** DO EMAIL TO ORGANIZERS ***************************************/
						mail($orgTo,$orgSubject,$orgMessage,$headers);//email organizers
						/*************************************************************************************************/
					}
			}
			else
			{
				  //if "email" is not filled out
				  //do nothing echo "Email error.";
			}
			/*************************************************************************************************/
		}
	}

	if ($_GET['fct'] == 'getJSONAllActivitiesAtEvent')
	{
		$result = getJSONAllActivitiesAtEvent($connectionObject, $_GET['eventId']);
		echo $result;
	}
	if ($_GET['fct'] == 'getJSONAllMembers')
	{
		$result = getJSONAllMembers($connectionObject);
		echo $result;
	}
	if ($_GET['fct'] == 'getJSONMembersByInterest')
	{
			$result = getJSONMembersByInterest($connectionObject, $_GET['intId']);
			echo $result;
	}

	if ($_GET['fct'] == 'getJSONMembersByLanguage')
	{
		$result = getJSONMembersByLanguage($connectionObject, $_GET['langId']);
		echo $result;
	}

	if ($_GET['fct'] == 'updateThisMemberDetails')
	{
		$result = updateThisMemberDetails($connectionObject, $_GET['last'], $_GET['first'], $_GET['org'], $_GET['email'], do_crypt($_GET['pass']), $_GET['phone'], $_GET['source'], $_GET['comments'], $_GET['region'], $_GET['lang']);
		echo $result;
	}

	if ($_GET['fct'] == 'updateMemberContactDetails')
	{
		$result = updateMemberContactDetails($connectionObject, $_GET['source'], $_GET['last'], $_GET['first'], $_GET['email'], $_GET['org'], $_GET['phone'], $_GET['memId']);
		echo $result;
	}

	if ($_GET['fct'] == 'updateMemberPassword')
	{
		$result = updateMemberPassword($connectionObject, do_crypt($_GET['pass']), $_GET['memId']);
		echo $result;
	}

	if ($_GET['fct'] == 'updateThisMemberLangReg')
	{
		$result = updateThisMemberLangReg($connectionObject, $_GET['lang'], $_GET['region'], $_GET['memId']);
		echo $result;
	}

	if ($_GET['fct'] == 'updateThisMemberInterests')
	{
		$result = updateThisMemberInterests($connectionObject, $_GET['memId'], $_GET['ints']);
		echo $result;
	}

	if ($_GET['fct'] == 'updateThisMemberInts')
	{
		$result = updateThisMemberInts($connectionObject, $_GET['memId'], $_GET['ints']);
		echo $result;
	}

	if ($_GET['fct'] == 'addMemberToActivitySelected')
	{
            $result = addMemberToActivitySelected($connectionObject, $_GET['memId'], $_GET['actId']);
            echo $result;
	}
        
        if ($_GET['fct'] == 'deleteMemberFromActivitySelected')
        {
            $result = deleteMemberFromActivitySelected($connectionObject, $_GET['memId'], $_GET['actId']);
	    echo $result;
        }

	if ($_GET['fct'] == 'getSongs')
	{
		$result = getSongs($connectionObject);
		echo $result;
	}
}
else
{
	echo "Did not get fct";
}
?>