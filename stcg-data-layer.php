<?php

include_once 'stcg-config.php';
require_once 'stcg-utilities.php';

//MYSQL FUNCTIONS

function connect()
{

	$opt = array(
			PDO::ATTR_EMULATE_PREPARES => false,
			PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION
	);

//creates a new PDO database connection object
	$dbstring="mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
	$db = new PDO($dbstring,DB_USERNAME,DB_PWD,$opt);

//	$db = new PDO("mysql:host=" . "mysql.servethecitygeneva.ch" . ";dbname=" . "servethecitygenevach1" . ";charset=" . DB_CHARSET,"adminstcg","1f1te111",$opt);
	if (isset ($db))
	{
		alertMessage('datalayer | connect | success', SEV_INFO);
		return $db;
	}
	else
	{
		alertMessage('datalayer | connect | failed | dbstring:'+$dbstring , SEV_INFO);
		alertMessage("Database connection failed.",SEV_CRITICAL);
	}
}
/*************************************************************************************************************************************/
/********************************************* SELECTS ********************************************************************************/
function getAllLanguages($PDOdbObject)
{
	try
	{
		$getAllLanguagesSQL = "SELECT * FROM `language_pref` ORDER BY id;";
		$get = $PDOdbObject->query($getAllLanguagesSQL);
		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
	}
	catch (PDOException $e)
	{
		echo "There was a problem getting all the possible languages.";
		echo $e->getMessage();
	}
	return $rows;
}

function getAllLocations($PDOdbObject)
{
	try
	{
		$getAllLocationsSQL = "SELECT * FROM `location` ORDER BY location_id;";
		$get = $PDOdbObject->query($getAllLocationsSQL);
		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
	}
	catch (PDOException $e)
	{
		echo "There was a problem getting all the possible regions.";
		echo $e->getMessage();
	}
	return $rows;
}

//Get all interests in the interests table
//USED ON SIGNUP WIZARD PAGE STCG-VOL5-UPDATE.PHP
function getAllInterests($PDOdbObject)
{
	try
	{
		$getAllInterestsSQL = "SELECT `interest_id` FROM `interest` ORDER BY `interest_id`";
		$get = $PDOdbObject->query($getAllInterestsSQL);
		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
	}
	catch (PDOException $e)
	{
		echo "There was a problem getting all the possible interests.";
		echo $e->getMessage();
	}
	return $rows;
}

//USED ON ALL PAGES VIEWED BY THE PUBLIC
function getAllInterestsButUnknown($PDOdbObject)
{
    try
    {
        $getAllInterestsSQL = "SELECT `interest_id` FROM `interest` WHERE `interest_id` > 0 ORDER BY `interest_id`";
        $get = $PDOdbObject->query($getAllInterestsSQL);
        $rows = $get->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e)
    {
        echo "There was a problem getting all the possible interests.";
        echo $e->getMessage();
    }
    return $rows;
}

//USED IN LIST-ALL-MEMBERS VIA JSON-RESPONSES
function getJSONAllMembers($PDOdbObject)
{
	try
	{
		$getAllMembersSQL = "SELECT `id`,`name_first`,`name_last`,`organization`,`email`,`active`,`phone`,`comments`, `location_id`, `language_id` FROM `member` WHERE `active` = 1 ORDER BY `id`";
		$get = $PDOdbObject->query($getAllMembersSQL);
		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($rows);
	}
	catch (PDOException $e)
	{
		echo "There was a problem getting all the members.";
		echo $e->getMessage();
	}
	return $json;
}
//used for admin access to selected user accounts via login of admin and sub-admin       
function getJSONSelectedMembers($PDOdbObject, $first, $last)
{
    try
    {
        $allMembersSQL = "SELECT `id`, `name_first`, `name_last`, `organization`,`email`, `phone`,`source`,`comments`, `language_id`, `location_id` FROM `member` WHERE `active` = 1 AND member.name_first LIKE :first AND member.name_last LIKE :last";
        $getMatches = $PDOdbObject->prepare($allMembersSQL);
        //$params = array("%$first%", "%$last%");
        
        $first = $first."%";
        $last = $last."%";
        
// Bind the parameter
        $getMatches->bindParam(':first', $first, PDO::PARAM_STR);
        $getMatches->bindParam(':last', $last, PDO::PARAM_STR);
        
        $getMatches->execute();
        $rows = $getMatches->fetchAll();
        //$getMatches->execute(array('%$last%', '%$first%'));  
    }
    catch (PDOException $e)
    {
        echo "There was a problem getting all members with this search query.";
        echo $e->getMessage();
    }
    $json=json_encode($rows);
    return $json;
}

//USED ON SIGNUP WIZARD PAGE STCG-VOL1.PHP
//USED ON DATABASE ADMIN PAGE ORGANIZE-LATEST-EVENT-ACTIVITES-MEMBERS.PHP
function getLatestEvent($PDOdbObject)
{
	try
	{
		$latestEventSQL = "SELECT * FROM `event` WHERE `open` = 1 ORDER BY `id`";
                       // . "DESC LIMIT 1"; THIS IS A TEST TO SEE WHAT HAPPENS WHEN 2 ARE OPEN
		$get = $PDOdbObject->prepare($latestEventSQL);//prepare also returns PDO object
		$get->execute(array());

		$get->bindColumn('id', $id);
		$get->bindColumn('name', $name);
		$get->bindColumn('date', $date);

		while ($result = $get->fetch(PDO::FETCH_BOUND))
		{
                    $event = array(
                            "id" => $id,
                            "name" => $name,
                            "date" => $date
                            );
		}//end while

	}//end try
	catch(PDOException $e)
	{
		echo "There was a problem getting the latest Event.";
		echo $e->getMessage();
	}
	return $event;
}

//USED ON THIS PAGE IN insertNewMemberDetails, VIA ADD-NEW-MEMBER
function getLatestMember($PDOdbObject)
{
	try
	{
		$latestMemberSQL = "SELECT MAX( id ) AS `id`, `name_first`, `name_last` FROM member LIMIT 1";
		$get = $PDOdbObject->prepare($latestMemberSQL);//prepare also returns PDO object
		$get->execute(array());

		$get->bindColumn('id', $id);
		$rows = $get->fetch(PDO::FETCH_ASSOC);

	}
	catch(PDOExceptionn $e)
	{
		echo "There was a problem getting the latest Member.";
		echo $e->getMessage();
	}
	//return $id;
	return $rows;
}

//USED ON SIGNUP WIZARD PAGE STCG-VOL2.PHP AND EDITVOL2
function getThisMemberByEmail($PDOdbObject, $emailIn)
{
	try
	{
		$getMemberByEmailSQL = "SELECT member.id, member.name_first, member.name_last, member.organization,
		member.email, member.password, member.phone, member.source, member.language_id, member.location_id
		FROM member
		WHERE member.email = ? AND member.active = ?;";

		$get = $PDOdbObject->prepare($getMemberByEmailSQL);
		$get->execute(array($emailIn, 1));

		$get->bindColumn('id', $memberId);
		$get->bindColumn('name_first', $first);
		$get->bindColumn('name_last', $last);
		$get->bindColumn('organization', $org);
		$get->bindColumn('email', $email);
		$get->bindColumn('password', $password);
		$get->bindColumn('phone', $phone);
		$get->bindColumn('source', $source);
		$get->bindColumn('language_id', $lang);
		$get->bindColumn('location_id', $loc);

		while ($result = $get->fetch(PDO::FETCH_BOUND))
		{
			$member = array(
				"id" => $memberId,
				"name_first" => $first,
				"name_last" => $last,
				"organization" => $org,
				"email" => $email,
				"password" => $password,
				"phone" => $phone,
				"source" => $source,
				"language_id" => $lang,
				"location_id" => $loc);
		}//end while

		if (isset($member))
		{
			return $member;
		}
	}//end try
	catch(PDOException $e)
	{
		echo "Could not find a member with this email address.";
		echo $e->getMessage();
	}
}

//USED ON SIGNUP WIZARD PAGE STCG-VOL4.PHP AND EDITVOL4
//check this  /////////////////////USED ON SIGNUP WIZARD PAGE STCG-VOL5-MEMBER-DETAILS-UPDATED.PHP
function getThisMemberDetailsByMemberId($PDOdbObject, $memberIdIn)
{
	try
	{
		$thisMemberByMemberIdSQL = "SELECT member.id, member.name_first, member.name_last, member.organization, member.email, member.password, member.phone, member.source, member_language.language_id, member_location.location_id
		FROM member, member_language, member_location
		WHERE member.id = member_language.member_id AND member.id = member_location.member_id
		AND member.id = ? AND member.active = ?;";

		$get = $PDOdbObject->prepare($thisMemberByMemberIdSQL);//prepare also returns PDO object
		$get->execute(array($memberIdIn, 1));

		$get->bindColumn('id', $memberIdIn);
		$get->bindColumn('name_last', $last);
		$get->bindColumn('name_first', $first);
		$get->bindColumn('organization', $org);
		$get->bindColumn('email', $email);
		$get->bindColumn('password', $password);
		$get->bindColumn('phone', $phone);
		$get->bindColumn('source', $source);
		$get->bindColumn('language_id', $lang);
		$get->bindColumn('location_id', $loc);

		while ($result = $get->fetch(PDO::FETCH_BOUND))
		{
			$member = array(
				"id"=> $memberIdIn,
				"name_last" => $last,
				"name_first" => $first,
				"organization" => $org,
				"email" => $email,
				"password" => $password,
				"phone" => $phone,
				"source" => $source,
				"language_id" => $lang,
				"location_id" => $loc);
		}//end while
		if (isset($member))
		{
			return $member;
		}
	}//end try
	catch(PDOException $e)
	{
		echo "There was a problem getting this member.";
		echo $e->getMessage();
	}
}

//USED IN PASSWORD-HANDLING
function getThisMemberByMemberId($PDOdbObject, $memberIdIn)
{
	try
	{
		$thisMemberByMemberIdSQL = "SELECT member.id, member.name_first, member.name_last, member.organization, member.email, member.password, member.phone
		FROM member
		WHERE member.id = ? AND member.active = ?;";

		$get = $PDOdbObject->prepare($thisMemberByMemberIdSQL);//prepare also returns PDO object
		$get->execute(array($memberIdIn, 1));

		$get->bindColumn('id', $memberIdIn);
		$get->bindColumn('name_last', $last);
		$get->bindColumn('name_first', $first);
		$get->bindColumn('organization', $org);
		$get->bindColumn('email', $email);
		$get->bindColumn('password', $password);
		$get->bindColumn('phone', $phone);

		while ($result = $get->fetch(PDO::FETCH_BOUND))
		{
			$member = array(
					"id"=> $memberIdIn,
					"name_last" => $last,
					"name_first" => $first,
					"organization" => $org,
					"email" => $email,
					"password" => $password,
					"phone" => $phone);
		}//end while
		if (isset($member))
		{
			return $member;
		}
	}//end try
	catch(PDOException $e)
	{
		echo "There was a problem getting this member.";
		echo $e->getMessage();
	}
}

//NOT USED FOR NOW
function getThisMemberAtThisActivity($PDOdbObject, $memberName, $activityCode)
{
	try
	{
		$thisMemberAtThisActivitySQL = "SELECT member_activity.member_id, member_activity.activity_id
			FROM `member`, member_activity`, `activity`
			WHERE member.name_last = ?
			AND activity.activity_short_code = ?
			AND member.id = member_activity.member_id
			AND activity.activity_id = member_activity.activity_id";

		//ORDER BY member.name_last";

		$get = $PDOdbObject->prepare($thisMemberAtThisActivitySQL);//prepare also returns PDO object
		$get->execute(array($memberName, $activityCode));
		$get->bindColumn('id', $id);
		$get->bindColumn('activity_id', $activity_id);

		while ($result = $get->fetch(PDO::FETCH_BOUND))
		{
			$memberAct = array(
				"id" => $id,
				"activity_id" => $activity_id
			);
		}//end while

	}//end try
	catch(PDOException $e)
	{
		echo "There was problem getting this member and activity.";
		$e->getMessage();
	}
	return $memberAct;
}

//NOT USED FOR NOW
//Get all activities associated with one identified member and one identified Event
function getJSONThisMembersActivities($PDOdbObject, $memberId, $eventId)
{
	try
	{
		$getMemberActivitiesSQL = "SELECT member_activity.activity_id FROM member_activity, activity
				WHERE member_activity.member_id = :memberId
				AND member_activity.activity_id = activity.activity_id
				AND activity.event_id = :eventId";

		$get = $PDOdbObject->prepare($getMemberActivitiesSQL);
		$get->execute(array(
				':memberId' => $memberId,
				':eventId' => $eventId
		));

		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($rows);
		return $json;
	}
	catch (PDOException $e)
	{
		echo "There was problem getting this person's Activities.";
		echo $e->getMessage();
		return $e->getMessage();
	}
}
/////////////////////////////////////////////////////////////CHECK THAT THIS WORKS /////////////////////////////////////////
//USED ON SIGNUP WIZARD PAGE STCG-VOL4.PHP AND EDITVOL-4
//Get all interests associated with one identified member
function getThisMemberInterests($PDOdbObject, $memberEmailIn)
{
	try
	{
		$thisMemberInterestsSQL = "SELECT member_interest.interest_id FROM member, member_interest
			WHERE member.id = member_interest.member_id
			AND member.email = ?";
		$get = $PDOdbObject->prepare($thisMemberInterestsSQL);
		$get->execute(array($memberEmailIn));
		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e)
	{
		echo "There was problem connecting to database.";
		echo $e->getMessage();
	}
	return $rows;
}
//NOT USED FOR NOW
function getActNameForActId($PDOdbObject, $actId)
{
	try
	{
		$getActNameSQL = "SELECT `activity_name` FROM `activity` WHERE `activity_id` = :act_id";
		$get = $PDOdbObject->prepare($getActNameSQL);
		$get->bindParam(':act_id', $actId, PDO::PARAM_INT);
		//bindParam(':activityId', $activityId, PDO::PARAM_INT);
		//$get->fetchColumn();
		$result = $get->execute();
	}
	catch (PDOException $e)
	{
		echo "There was a problem getting this Activity's Name.";
		echo $e->getMessage();
	}
	return $result;
}

//NOT USED FOR NOW
function getEventDetails($PDOdbObject, $eventId)
{
	try
	{
		$getThisEventSQL = "SELECT * FROM event WHERE id = ?";
		$get = $PDOdbObject->prepare($getThisEventSQL);
		$get->execute(array($eventId));

		$get->bindColumn('name', $name);
		$get->bindColumn('date', $date);
		$get->bindColumn('open', $open);

		while ($result = $get->fetch(PDO::FETCH_BOUND))
		{
				$event = array(
				"name" => $name,
				"date" => $date,
				"open" => $open,
			);
		}//end while

	}//end try
	catch (PDOException $e)
	{
		echo "There was a problem getting this Event.";
		echo $e->getMessage();
	}
	return $event;
}

//LIST EVENT ACTIVITIES WITH MEMBERS
function getJSONAllEventDetails($PDOdbObject)
{
	try
	{
		$getAllEventsSQL = "SELECT * FROM event";
		$get = $PDOdbObject->prepare($getAllEventsSQL);

		$get->execute(array());

		$get->bindColumn('id', $id);
		$get->bindColumn('name', $name);
		$get->bindColumn('date', $date);

		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($rows);

	}//end try
	catch (PDOException $e)
	{
		echo "There was a problem getting all the Events.";
		echo $e->getMessage();
	}
	return $json;
}

//UNUSED
function getActIdForActShortCode($PDOdbObject, $eventId, $actSC)
{
	try
	{
		$actIdSQL = "SELECT `activity_id` FROM `activity` WHERE event_id = $eventId AND activity_short_code =" . "'" .$actSC ."'";
		$get = $PDOdbObject->query($actIdSQL);
		$rows = $get->fetch(PDO::FETCH_ASSOC);
	}
	catch (PDOException $e)
	{
		echo "There was a problem getting this Activity's Id.";
		echo $e->getMessage();
	}
	return $rows;
}

/*//AND member.active = 1
function getMembersAtEvent($PDOdbObject, $eventId)
{
	try
	{
		$membersAtEventSQL = "SELECT member.id, member.name_last, member.name_first, event.name, activity.activity_id, activity.activity_short_code, activity.activity_name, activity.capacity, member_activity.no_show_illness, member_activity.no_show_no_warning
			FROM `member` , `member_activity` , `event` , `activity`
			WHERE member.id = member_activity.member_id
			AND activity.event_id = event.id
			AND activity.activity_id = member_activity.activity_id
			AND event.id = ?";

		$get = $PDOdbObject->prepare($membersAtEventSQL);//prepare also returns PDO object
		$get->execute(array($eventId));

		$get->bindColumn('id', $memberId);
		$get->bindColumn('name_first', $first);
		$get->bindColumn('name_first', $last);
		$get->bindColumn('name', $eventName);
		$get->bindColumn('activity_id', $actid);
		$get->bindcolumn('activity_short_code', $actSC);
		$get->bindColumn('activity_name', $actName);
		$get->bindColumn('capacity', $capName);
		$get->bindColumn('no_show_illness', $noShowIll);
		$get->bindColumn('no_show_no_warning', $noShowNone);

		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($rows);

	}//end try
	catch(PDOException $e)
	{
		echo "There was a problem getting this Event with its members.";
		echo $e->getMessage();
	}
return $rows;
}
*/
//USED ON DATABASE ADMIN PAGE ORGANIZE-LATEST-EVENT-ACTIVITES-MEMBERS.PHP
// LIST-EVENT-ACTIVITIES-WITH-MEMBERS VIA JSON-RESPONSES
//FROM `member`, `member_activity`, `activity`
function getJSONMembersAtEvent($PDOdbObject, $eventId)
{
	try
	{
		$membersAtEventSQL = "SELECT member.id, member.name_last, member.name_first, member.email, member.phone, activity.event_id, activity.activity_id, activity.activity_short_code, activity.activity_name, activity.capacity, activity.project_leader, member_activity.comments, member_activity.selected
			FROM `member`, `member_activity`, `activity`
			WHERE member.id = member_activity.member_id
			AND activity.activity_id = member_activity.activity_id
			AND activity.event_id = ?";

		$get = $PDOdbObject->prepare($membersAtEventSQL);//prepare also returns PDO object
		$get->execute(array($eventId));

		$get->bindColumn('id', $memberId);
		$get->bindColumn('name_last', $last);
		$get->bindColumn('name_first', $first);
		$get->bindColumn('email', $email);
		$get->bindColumn('phone', $phone);

		$get->bindColumn('event_id', $eventId);
		$get->bindColumn('activity_id', $actId);
		$get->bindcolumn('activity_short_code', $actSC);
		$get->bindColumn('activity_name', $actName);
		$get->bindColumn('capacity', $capName);
		$get->bindColumn('project_leader', $pl);
                $get->bindColumn('comments', $comments);
		$get->bindColumn('selected', $sel);

		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($rows);

	}//end try
	catch(PDOException $e)
	{
		echo "There was a problem getting this Event with its members.";
		echo $e->getMessage();
	}
	return $json;
}

function getJSONMembersAssignedToActivities($PDOdbObject, $eventId)
{
	try
	{
		$membersAssignedSQL = "SELECT member.id, member.name_last, member.name_first, activity.activity_short_code, activity.activity_name, activity.capacity, activity.project_leader
FROM `member` , `member_activity_selected` , `event` , `activity`
WHERE member.id = member_activity_selected.member_id
AND activity.activity_id = member_activity_selected.activity_id
AND activity.event_id = event.id
AND event.id = ?";

		$get = $PDOdbObject->prepare($membersAssignedSQL);//prepare also returns PDO object
		$get->execute(array($eventId));

		$get->bindColumn('id', $memberId);
		$get->bindColumn('name_last', $last);
		$get->bindColumn('name_first', $first);
		$get->bindcolumn('activity_short_code', $actSC);
		$get->bindColumn('activity_name', $actName);
		$get->bindColumn('capacity', $capName);
		$get->bindColumn('project_leader', $pl);

		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($rows);

	}//end try
	catch(PDOException $e)
	{
		echo "There was a problem getting this the members assigned to Activities.";
		echo $e->getMessage();
	}
	return $json;
}


// USED IN LIST-MEMBER-WITH-PAST-ACTIVITIES
function getJSONMembersAndPastActivities ($PDOdbObject, $memberLastName, $memberFirstName)
{
	try
	{
		$membersPastActivitiesSQL = "SELECT member.id, member.name_last, member.name_first, event.name, member_activity_selected.activity_id, activity.activity_name, activity.activity_short_code
		FROM `member`, `event`,`activity`, `member_activity_selected`
		WHERE member.id = member_activity_selected.member_id
		AND activity.event_id = event.id
		AND activity.activity_id = member_activity_selected.activity_id
		AND member.name_last LIKE ?
		AND member.name_first LIKE ?
		ORDER BY activity.activity_id DESC";

		$params = array("$memberLastName%", "$memberFirstName%");
		$get = $PDOdbObject->prepare($membersPastActivitiesSQL);//prepare also returns PDO object
		$get->execute($params);

		//$query->execute(array('value%'));
		$get->bindColumn('id', $memberId);
		$get->bindColumn('name_first', $first);
		$get->bindColumn('name_last', $last);
		$get->bindColumn('name', $eventName);
		$get->bindColumn('activity_id', $actid);
		$get->bindColumn('activity_name', $actName);
		$get->bindcolumn('activity_short_code', $actSC);

		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($rows);

	}//end try
	catch(PDOException $e)
	{
		echo "There was a problem getting this members past Activities.";
		echo $e->getMessage();
	}
	return $json;
}

//UNUSED
function getJSONSelectedMembersAtEvent($PDOdbObject, $eventId)
{
	try
	{
		$selectedMembersAtEventSQL = "SELECT member.id, member.name_last, member.name_first, event.name, activity.activity_id, activity.activity_short_code, activity.activity_name, activity.capacity, member_activity_selected.project_leader, member_activity_selected.no_show_illness, member_activity_selected.no_show_no_warning
		FROM `member`, `member_activity_selected`, `event`, `activity`
		WHERE member.id = member_activity_selected.member_id
		AND activity.activity_id = member_activity_selected.activity_id
		AND activity.event_id = event.id
		AND event.id = ?";

		$get = $PDOdbObject->prepare($selectedMembersAtEventSQL);//prepare also returns PDO object
		$get->execute(array($eventId));

		$get->bindColumn('id', $memberId);
		$get->bindColumn('name_first', $first);
		$get->bindColumn('name_first', $last);
		$get->bindColumn('name', $eventName);
		$get->bindColumn('activity_id', $actid);
		$get->bindcolumn('activity_short_code', $actSC);
		$get->bindColumn('activity_name', $actName);
		$get->bindColumn('capacity', $capName);
		$get->bindColumn('project_leader', $pl);
		$get->bindColumn('no_show_illness', $nsi);
		$get->bindColumn('no_show_no_warning', $nsnw);

		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($rows);

	}//end try
	catch(PDOException $e)
	{
		echo "There was a problem getting this Event with volunteers assigned.";
		echo $e->getMessage();
	}
	return $json;
}

//AVAILABLE IN JSON-RESPONSES BUT UNUSED
function getJSONSelectedMembersAtActivity($PDOdbObject, $eventId, $actId)
{
	try
	{
		$selectedMembersAtActivitySQL = "SELECT member.id, member.name_last, member.name_first, member.email, member.phone, event.name, activity.activity_id, activity.activity_short_code, activity.activity_name, activity.capacity, activity.project_leader
			FROM `member` , `member_activity_selected` , `event` , `activity`
			WHERE member.id = member_activity_selected.member_id
			AND activity.event_id = event.id
			AND activity.activity_id = member_activity_selected.activity_id
			AND event.id = ?
			AND activity.activity_id = ?";

		$get = $PDOdbObject->prepare($selectedMembersAtActivitySQL);//prepare also returns PDO object
		$get->execute(array($eventId, $actId));

		$get->bindColumn('id', $memberId);
		$get->bindColumn('name_first', $first);
		$get->bindColumn('email', $email);
		$get->bindColumn('phone', $phone);
		$get->bindColumn('name', $eventName);
		$get->bindColumn('activity_id', $actid);
		$get->bindcolumn('activity_short_code', $actSC);
		$get->bindColumn('activity_name', $actName);
		$get->bindColumn('capacity', $capName);
		$get->bindColumn('project_leader', $pl);

		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($rows);
	}//end try
	catch(PDOException $e)
	{
		echo "There was a problem getting this selected Activity with its members.";
		$e->getMessage();
		echo $e;
	}
	return $json;
	//return $rows;
}

//UNUSED
function getJSONMembersAtActivity($PDOdbObject, $eventId, $actId)
{
	try
	{
		$membersAtActivitySQL = "SELECT member.id, member.name_last, member.name_first, event.name, activity.activity_id, activity.activity_short_code, activity.activity_name, activity.capacity
			FROM `member` , `member_activity` , `event` , `activity`
			WHERE member.id = member_activity.member_id
			AND activity.event_id = event.id
			AND activity.activity_id = member_activity.activity_id
			AND event.id = ?
			AND activity.activity_id = ?";

		$get = $PDOdbObject->prepare($membersAtActivitySQL);//prepare also returns PDO object
		$get->execute(array($eventId, $actId));

		$get->bindColumn('id', $memberId);
		$get->bindColumn('name_first', $first);
		$get->bindColumn('name_last', $last);
		$get->bindColumn('name', $eventName);
		$get->bindColumn('activity_id', $actid);
		$get->bindcolumn('activity_short_code', $actSC);
		$get->bindColumn('activity_name', $actName);
		$get->bindColumn('capacity', $capName);

		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($rows);
	}//end try
	catch(PDOException $e)
	{
		echo "There was a problem getting this Activity with its members.";
		$e->getMessage();
		echo $e;
	}
	return $json;
	//return $rows;
}

//USED ON SIGNUP WIZARD PAGE STCG-VOL6.PHP AND DO-ADD-EVENT-WITH JQWIDGET BOTH VIA JSON-RESPONSES
function getJSONAllActivitiesAtEvent($PDOdbObject, $eventId)
{
	try
	{
		$thisEventActivitiesSQL = "SELECT * FROM activity WHERE `event_id`=?";
		$get = $PDOdbObject->prepare($thisEventActivitiesSQL);
		$get->execute(array($eventId));

		$get->bindColumn('activity_id', $id);
		$get->bindColumn('activity_name', $name);
		$get->bindColumn('activity_desc', $desc);
		$get->bindColumn('activity_short_code', $sc);
		$get->bindColumn('event_id', $eventId);
		$get->bindColumn('capacity', $cap);
		$get->bindColumn('date', $date);

		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($rows);
	}//end try
	catch(PDOException $e)
	{
		echo "There was a problem getting Activities for this Event.";
		echo $e->getMessage();
	}
	return $json;
}

//UNUSED
function getJSONAllActivityIdsAtEvent($PDOdbObject, $eventId)
{
	try {
		$thisEventActIdsSQL = "SELECT activity_id, activity_name FROM activity WHERE `event_id`=?";
		$get = $PDOdbObject->prepare($thisEventActIdsSQL);
		$get->execute(array($eventId));
		$get->bindColumn('activity_id', $id);
		$get->bindColumn('activity_name', $name);

		$rows = $get->fetchAll(PDO::FETCH_ASSOC);

		$json=json_encode($rows);
	}
	catch(PDOException $e)
	{
		echo "There was a problem getting Activity Ids for this Event.";
		echo $e->getMessage();
	}
	return $json;
}

//AVAILABLE IN JSON-RESPONSES BUT UNUSED
function getJSONAllSCsAtEvent($PDOdbObject, $eventId)
{
	try
	{
		$thisEventSCsSQL = "SELECT `activity_short_code`, `activity_id`, `capacity`, `project_leader`  FROM activity WHERE `event_id`= ?";
		$get = $PDOdbObject->prepare($thisEventSCsSQL);
		$get->execute(array($eventId));
		$get->bindColumn('activity_short_code', $act_short_code);
		$get->bindColumn('activity_id', $actId);
		$get->bindColumn('capacity', $capacity);
		$get->bindColumn('project_leader', $project_leader);

		$rows = $get->fetchAll(PDO::FETCH_ASSOC);

		$json=json_encode($rows);
		return $json;
	}
	catch (PDOException $e)
	{
		echo "There was a problem getting the Activity short codes.";
		echo $e->getMessage();
	}
}

//UNUSED
function getBoundLatestEvent($connectionObject)
{
	$sql = "SELECT * FROM `event` ORDER BY id DESC LIMIT 1";
	try{
		$eventRequest = $connectionObject->prepare($sql);
		$eventRequest->bindColumn('id', $eventId);
		$eventRequest->bindColumn('name', $eventName);
		$eventRequest->execute();

		while ($eventResult = $eventRequest->fetch(PDO::FETCH_BOUND))
		{
			$event = array(
			"id" => $eventId,
			"name" => $eventName,
			);
		}//end while
		return $event;
	}//end try
	catch (PDOException $e)
	{
		echo "Catch error from fetch BOUND event table query " . $e->getMessage();
	}// end catch
}//end function getBoundLatestEvent

//UNUSED FOR NORMAL PROCESSING, ONLY DURING DEV
function getMemberPasswords($PDOdbObject)
{
	try
	{
		$getAll = $PDOdbObject->prepare("SELECT `id`, `password` FROM `member`");
		$getAll->execute();
		$rows = $getAll->fetchAll(PDO::FETCH_ASSOC);
		$json=json_encode($rows);
		return $json;
		//return $rows;
	}
	catch (PDOException $e)
	{
		echo $e->getMessage();
	}
}

//USED ON LIST-INTEREST-SHARED-BY-ALL-MEMBERS VIA JSON-RESPONSES
function getJSONMembersByInterest($PDOdbObject, $intId)
{
	try
	{
		$getMemByIntSQL = "SELECT `id`, `name_first`, `name_last`,`email` FROM `member`, `member_interest`
				WHERE member_interest.member_id = member.id
		        AND member.active = 1
				AND interest_id = ?";

		$get = $PDOdbObject->prepare($getMemByIntSQL);
		$get->execute(array($intId));

		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
	}
	catch (PDOException $e)
	{
		echo "There was a problem getting all the members with this interest.";
		echo $e->getMessage();
	}
	return json_encode($rows);
}

//USED ON LIST-LANGUAGE-SHARED-BY-ALL-MEMBERS VIA JSON-RESPONSES
function getJSONMembersByLanguage($PDOdbObject, $langId)
{
	try
	{
		$getMemByLangSQL = "SELECT `id`, `name_first`, `name_last`, `email`
			FROM `member` , `member_language`
			WHERE `member_language`.`member_id` = `member`.`id`
			AND `member`.`active`= 1
			AND `member_language`.`language_id` = ?";

		$get = $PDOdbObject->prepare($getMemByLangSQL);
		$get->execute(array($langId));

		$rows = $get->fetchAll(PDO::FETCH_ASSOC);
	}
	catch (PDOException $e)
	{
		echo "There was a problem getting all the members with this language preference.";
		echo $e->getMessage();
	}
	return json_encode($rows);
}

/************************************************ UPDATES *********************************************************************************/

//USED ON STCG-EDIT-MEMBER-DETAILS PAGE FOR ADMINS ONLY, VIA JSON-RESPONSES
function updateThisMemberDetails($PDOdbObject, $last, $first, $org, $email, $phone, $source, $comments, $loc, $lang, $memberIdIn)
{
	try
	{
		$updateMemberSQL = "UPDATE `member`
		SET member.name_last=?, member.name_first=?, member.organization=?, member.email=?,                  member.phone=?, member.source=?, member.comments=?, member.location_id=?, member.language_id=?   
		WHERE member.id = ?";
		$update = $PDOdbObject->prepare($updateMemberSQL);

		$update->execute(array(
				$last,
				$first,
				$org,
				$email,
				$phone,
                                $source,
                                $comments,
				$loc,
                                $lang,
				$memberIdIn
		));

		$affected_rows = $update->rowCount();
		return $affected_rows;
	}
	catch(PDOException $e)
	{
		echo "There was a problem updating your details.";
		echo $e->getMessage();
	}
}

function updateMemberContactDetails($PDOdbObject, $source, $last, $first, $email, $org, $phone, $memberIdIn)
{
	try
	{
		$updateMemberSQL = "UPDATE `member`
		SET source=?, name_last=?, name_first=?, email=?, organization=?, phone=?
		WHERE id = ?";
		$update = $PDOdbObject->prepare($updateMemberSQL);

		$update->execute(array(
				$source,
				$last,
				$first,
				$email,
				$org,
				$phone,
				$memberIdIn
		));
		$affected_rows = $update->rowCount();
		return $affected_rows;
		}
	catch(PDOException $e)
	{
		echo "There was a problem updating your contact details.";
		echo $e->getMessage() . " updateMemberSQL";//SQLSTATE[HY093]: Invalid parameter number updateMemberSQL
	}
}

function updateMemberPassword($PDOdbObject, $pass, $memId)
{
	try
	{
		$updatePasswordSQL = ("UPDATE `member` SET `password` = ? WHERE `id` = ?" );
		$updPwd = $PDOdbObject->prepare($updatePasswordSQL);
		$updPwd->execute(array(
				$pass,
				$memId
		));
		$affected_rows = $updPwd->rowCount();
		return $affected_rows;
	}
	catch (PDOException $e)
	{
		echo "There was a problem could not Update Password.";
		echo $e->getMessage();
	}
}

function updateThisMemberLangReg($PDOdbObject, $lang, $loc, $memberIdIn)
{
	try
	{
		$updateMemberSQL = "UPDATE `member`
		SET member.language_id=?, member.location_id=?
		WHERE member.id = ?";
		$update = $PDOdbObject->prepare($updateMemberSQL);

		$update->execute(array(
				$lang,
				$loc,
				$memberIdIn
		));

		$affected_rows = $update->rowCount();
		return $affected_rows;
	}
	catch(PDOException $e)
	{
		echo "There was a problem updating your language and/or region.";
		echo $e->getMessage() . " updateMemberSQL";//SQLSTATE[HY093]: Invalid parameter number updateMemberSQL
	}
}


//This function is in fact a DELETE followed by an INSERT
//USED ON SIGNUP WIZARD PAGE STCG-VOL5-MEMBER-DETAILS-UPDATED.PHP
function updateThisMemberInterests($PDOdbObject, $memberId, $interests)
{
	if ($interests != 0)
	{
		try
		{
			//begin transaction
			$PDOdbObject->beginTransaction();

			$delInts = "DELETE FROM `member_interest` WHERE `member_id` = $memberId";
			$PDOdbObject->exec($delInts);

			$intId = 0;
			$upInt = $PDOdbObject->prepare(  "INSERT INTO member_interest (`member_id`,`interest_id`) VALUES ($memberId, :interest_id)" );
			$upInt->bindParam(':interest_id', $intId, PDO::PARAM_INT);

			foreach($interests as $intId)
			{
				$upInt->execute();
			}
			//commit
			$PDOdbObject->commit();
			$affected_rows = $upInt->rowCount();
			return $affected_rows;
		}
		catch (PDOException $e)
		{
			echo "There was a problem - rolling back the Update Interests transaction.";
			//rollback transaction
			$PDOdbObject->rollBack();
			echo $e->getMessage();
		}
	}//more than zero interests changed

}

//UNUSED
function updateActivities($PDOdbObject, $activity_name,$activity_desc,$activity_short_code,$event_id,$capacity, $date,$open=1)
{
	try
	{
		$updateActSQL = $PDOdbObject->prepare("UPDATE `activity`
								SET `activity_name`=?,`activity_desc`=?,`activity_short_code`=?,`event_id`=?,
								`capacity`=?, `date`=?,`open`=?
								WHERE 'activity_id'=?");
		$update = $PDOdbObject->prepare($updateActSQL);
		$update->execute();

		$update->bindValue(":activity_name", $activity_name, PDO::PARAM_STR);
		$update->bindValue(":activity_desc", $activity_desc, PDO::PARAM_STR);
		$update->bindValue(":activity_short_code", $activity_short_code, PDO::PARAM_STR);
		$update->bindValue(":event_id", $event_id, PDO::PARAM_INT);
		$update->bindValue(":capacity", $capacity, PDO::PARAM_INT);
		$update->bindValue(":date", $date, PDO::PARAM_STR);
		$update->bindValue(":open", $open, PDO::PARAM_INT);

/*		if(!$act[activity_id]>0)
		{
			$upActivitySQL = $PDOdbObject->prepare(  "INSERT INTO activity (`activity_name`,`activity_desc`,`activity_short_code`,`event_id`,
								`capacity`,`day_of_week',`date`,`open`) VALUES (:activity_id,:activity_name,:activity_desc,:activity_short_code,:event_id:,
								:capacity,:day_of_week,:date,:open)" );

			$upActivitySQL->execute();
		}
		else{*/

		$affected_rows = $update->rowCount();
		$json=json_encode($affected_rows);
		return $json;
	}
	catch (PDOException $e)
	{
		echo "There was a problem - rolling back this transaction.";
		//rollback transaction
		$PDOdbObject->rollBack();
		echo $e->getMessage();
		return false;
	}
}

///USED ON EVENT-ORGANIZE PAGE
//THIS IS A DELETE FOLLOWED BY AN INSERT
function updateAllSelectedMembersAtActivity($PDOdbObject, $changedActId, $selectedMembers)
{
    try
    {
        //begin transaction
        $PDOdbObject->beginTransaction();

        //delete all members at event-activity
        $delMemAct = $PDOdbObject->prepare(  "DELETE FROM `member_activity_selected` WHERE `activity_id` = :act_id" );


        $delMemAct->bindValue(":act_id", $changedActId, PDO::PARAM_INT);

        $delMemAct->execute();

        //insert new members
        if ($selectedMembers > 0)
        {
            $insMemAct = $PDOdbObject->prepare("INSERT INTO `member_activity_selected` (`member_id`,`activity_id`) VALUES (:member_id, :act_id)" );

            $insMemAct->bindParam(':act_id', $changedActId, PDO::PARAM_INT);
            $insMemAct->bindParam(':member_id', $memberId, PDO::PARAM_INT);

            foreach($selectedMembers as $memberId)
            {
                //$insMemAct->bindValue(":member_id", $memberId, PDO::PARAM_INT);
                $insMemAct->execute();
            }
        }
        //commit
        $PDOdbObject->commit();
        return true;
    }
    catch (PDOException $e)
    {
        //echo "There was a problem - rolling back this transaction.";
        alertMessage($e->getMessage(),SEV_DEBUG);
        //rollback transaction
        $PDOdbObject->rollBack();
        //echo $e->getMessage();
        return false;
    }
}

///make it possible for this to update flag for several members in one act.
function updateSelectedFlagforEvent($PDOdbObject, $activityId, $memberId)
{
    try
    {    
        $setFlagSQL = "UPDATE `member_activity` SET `selected`=1 WHERE `activity_id`=:activity_id AND `member_id`=:member_id";
        $setFlag = $PDOdbObject->prepare($setFlagSQL);
        $setFlag->bindParam(':activity_id', $activityId, PDO::PARAM_INT);
        $setFlag->bindParam(':member_id', $memberId, PDO::PARAM_INT);
        $setFlag->execute();
        $affected_rows = $setFlag->rowCount();
    }
    catch (PDOException $e) 
    {
        echo $e->getMessage();
    }
	return $affected_rows;
}

function updateDeletedFlagforEvent($PDOdbObject, $activityId, $memberId)
{
    try
    {    
        $setFlagSQL = "UPDATE `member_activity` SET `selected`=0 WHERE `activity_id`=:activity_id AND `member_id`=:member_id";
        $setFlag = $PDOdbObject->prepare($setFlagSQL);
        $setFlag->bindParam(':activity_id', $activityId, PDO::PARAM_INT);
        $setFlag->bindParam(':member_id', $memberId, PDO::PARAM_INT);
        $setFlag->execute();
        $affected_rows = $setFlag->rowCount();
    }
    catch (PDOException $e) 
    {
        echo $e->getMessage();
    }
    return $affected_rows;
}

//UNUSED
function updateThisPwd($PDOdbObject, $pass, $id)
{
	try
	{
		$updateSQL = "UPDATE `member` SET `password` = '$pass' WHERE `id` = '$id'";
		//echo $id . "    " . $pass . "\n";
		$updatePwd = $PDOdbObject->prepare($updateSQL);
		$updatePwd->execute();
	}
	catch (PDOException $e)
	{
		echo $e->getMessage();
	}
}

//UNUSED
function updatePwd($PDOdbObject, $pass, $id)
{
	try
	{
            $updateSQL = "UPDATE `member` SET `password` = '$pass' WHERE `id` = '$id' ";
            //echo $id . "    " . $pass . "\n";
            $updatePwd = $PDOdbObject->prepare($updateSQL);
            $updatePwd->execute();
	}
	catch (PDOException $e)
	{
            echo $e->getMessage();
	}
}

function updateSelectedActivity($PDOdbObject, $actId, $actName, $actDesc, $actCode, $cap, $date, $pl, $open)
{// 
    try
    {
        $updateActSQL = "UPDATE `activity` SET `activity_name` = ?, `activity_desc` = ?, `activity_short_code` = ?, `capacity` = ?, `date` = ?, `project_leader` = ?, `open` = ? WHERE `activity_id` = ? ";
        $updateAct = $PDOdbObject->prepare($updateActSQL);

        $updateAct->execute(array(
				$actName,
				$actDesc,
				$actCode,
				$cap,
				$date,
				$pl,
				$open,
				$actId
		));
        
        $affected_rows = $updateAct->rowCount();
    }
    catch (PDOException $e)
    {	
        echo $e->getMessage();
    }
    return $affected_rows;
}

/************************************************ INSERTS *********************************************************************************/
// USED IN ADD-NEW-MEMBER VIA JSON-RESPONSES
/*
function insertNewMemberAndSignup($PDOdbObject, $last, $first, $org, $email, $password, $phone, $source, $comments, $region, $lang, $interests, $activities)
{
	try
	{
		//begin transaction
		$PDOdbObject->beginTransaction();

		$insertNewMemberAndSignupSQL = "INSERT INTO `member`
		(`name_last`,`name_first`, `organization`, `email`, `password`, `phone`, `active`, `source`, `comments`, `location_id`, `language_id`)
		VALUES (:name_last, :name_first, :organization, :email, :password, :phone, 1, :source, :comments, :location_id, :language_id)";
		$insert = $PDOdbObject->prepare($insertNewMemberAndSignupSQL);
		$insert->execute(array(
				':name_last' => $last,
				':name_first' => $first,
				':organization' => $org,
				':email' => $email,
				':password' => $password,
				':phone' => $phone,
				':source' => $source,
				':comments' => $comments,
				':location_id' => $region,
				':language_id' => $lang
		));
		//get new id;
		$saved = getLatestMember($PDOdbObject);

                //value to passed must be an array not a string
		$interests = explode(",", $interests);
		insertThisMemberInterests($PDOdbObject, $saved['id'], $interests);
                
                //insert member act here //ADD COMMENTS CONTROL LATER!!!!!!!!!
                if (activities === null || activities === undefined)
                {
                    insertMemberActivities($PDOdbObject, $saved['id'], $activities);
                }
		//commit
		$PDOdbObject->commit();

		$affected_rows = $insert->rowCount();
		return $affected_rows;
	}
	catch(PDOException $e)
	{
		$PDOdbObject->rollBack();
		echo "There was a problem inserting this new member.";
		echo $e->getMessage();
	}
}
*/

// USED IN ADD-NEW-MEMBER VIA JSON-RESPONSES
function insertNewMemberDetails($PDOdbObject, $last, $first, $org, $email, $password, $phone, $source, $comments, $region, $lang, $interests, $activities)
{
	try
	{
		//begin transaction
		$PDOdbObject->beginTransaction();

		$insertNewMemberSQL = "INSERT INTO `member`
		(`name_last`,`name_first`, `organization`, `email`, `password`, `phone`, `active`, `source`, `comments`, `location_id`, `language_id`)
		VALUES (:name_last, :name_first, :organization, :email, :password, :phone, 1, :source, :comments, :location_id, :language_id)";
		$insert = $PDOdbObject->prepare($insertNewMemberSQL);
		$insert->execute(array(
				':name_last' => $last,
				':name_first' => $first,
				':organization' => $org,
				':email' => $email,
				':password' => $password,
				':phone' => $phone,
				':source' => $source,
				':comments' => $comments,
				':location_id' => $region,
				':language_id' => $lang
		));
		//get new id;
		$saved = getLatestMember($PDOdbObject);

		//value to passed must be an array not a string
		$interests = explode(",", $interests);
		insertThisMemberInterests($PDOdbObject, $saved['id'], $interests);

                //insert member act here //ADD COMMENTS CONTROL LATER!!!!!!!!!
                if (!($activities === null) || (!$activities === "undefined"))
                {
                    insertMemberActivities($PDOdbObject, $saved['id'], $activities);
                }
                
		//commit
		$PDOdbObject->commit();

		$affected_rows = $insert->rowCount();
		return $affected_rows;
	}
	catch(PDOException $e)
	{
		$PDOdbObject->rollBack();
		echo "There was a problem inserting this new member.";
		echo $e->getMessage();
	}
}

/*
function insertNewMemberLoc($PDOdbObject, $mem, $loc)
{
	try
	{
		$insertNewMemberLocSQL = "INSERT INTO `member_location` (`member_id`, `location_id`) VALUES (:mem, :loc)";
		$insert = $PDOdbObject->prepare($insertNewMemberLocSQL);
		$insert->execute(array(
				':mem' => $mem,
				':loc' => $loc
		));
		$affected_rows = $insert->rowCount();
		return $affected_rows;
	}
	catch(PDOException $e)
	{
		echo "There was a problem inserting this new member's location.";
		echo $e->getMessage();
	}
}
*/

/*
function insertNewMemberLang($PDOdbObject, $mem, $lang)
{
	try
	{
		$insertNewMemberLangSQL = "INSERT INTO `member_language` (`member_id`, `language_id`) VALUES (:mem, :lang)";
		$insert = $PDOdbObject->prepare($insertNewMemberLangSQL);
		$insert->execute(array(
				':mem' => $mem,
				':lang' => $lang
		));
		$affected_rows = $insert->rowCount();
		return $affected_rows;
	}

	catch(PDOException $e)
	{
		echo "There was a problem inserting this new member's language.";
		echo $e->getMessage();
	}
}
*/
//USED ON THIS PAGE IN insertNewMemberDetails, VIA ADD-NEW-MEMBER
function insertThisMemberInterests($PDOdbObject, $memberId, $interests)
{
    if ($interests != 0)
    {
            try
            {
                    $intId = 0;
                    $ins = $PDOdbObject->prepare( "INSERT INTO member_interest (`member_id`,`interest_id`) VALUES ($memberId, :interest_id)" );
                    $ins->bindParam(':interest_id', $intId, PDO::PARAM_INT);
                    foreach($interests as $intId)
                    {
                            alertMessage($intId,SEV_DEBUG);
                            $ins->execute();
                    }
                    $affected_rows = $ins->rowCount();
                    return $affected_rows;
            }
            catch (PDOException $e)
            {
                    echo "There was a problem inserting this member's interests.";
                    echo $e->getMessage();
            }
    }
}
//////////// ADD THE EQUIV FUNCTIONS TO ADD NEW EVENT AND JSON FILES
function insertNewActivityToNewEvent($PDOdbObject, $actName, $actDesc, $actCode, $cap, $date, $pl, $open, $eventId)
{
    try
    {
        $insertActivitySQL = "INSERT INTO activity (`activity_name`,`activity_desc`,`activity_short_code`,`capacity`,`date`,`project_leader`,`open`,`event_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $insertAct=$PDOdbObject->prepare($insertActivitySQL);
        $insertAct->execute(array
        (
            $actName,
            $actDesc,
            $actCode,
            $cap,
            $date,
            $pl,
            $open,
            $eventId
        ));
        $affected_rows = $insertAct->rowCount();
	return $affected_rows;  
    }
    catch (PDOException $e) 
    {
        echo "There was a problem inserting this activity.";
	echo $e->getMessage();
    }
}

function insertMemberActivities($PDOdbObject, $memberId, $acts, $commentsIn)
{
    if ($acts != 0)
    {
        try
        {   
            $acts = explode(",", $acts);//now it is an array
            $actId = 0;
            $ins = $PDOdbObject->prepare( "INSERT INTO `member_activity` (`member_id`, `activity_id`, `comments`) VALUES ($memberId, :activity_id, :comments)" );
            $ins->bindParam(':activity_id', $actId, PDO::PARAM_INT);
            $ins->bindParam(':comments', $commentsIn, PDO::PARAM_INT);
            foreach($acts as $actId)
            {
                alertMessage($actId, SEV_DEBUG);
                $ins->execute();
            }
            $affected_rows = $ins->rowCount();
            return $affected_rows;
        }
        catch (PDOException $e)
        {
                echo "There was a problem inserting this member's activities.";
                echo $e->getMessage();
        }
    }
}

function deleteMemberFromActivitySelected($PDOdbObject, $memId, $actId)
{
    try
    {
        $deleteMemSQL = " delete from `member_activity_selected` where `member_id` = :mem and `activity_id` = :act ";
        $deleteMem = $PDOdbObject->prepare($deleteMemSQL);
        $deleteMem->execute(array(
			        ':mem' => $memId,
			        ':act' => $actId
				));
        $affected_rows = $deleteMem->rowCount();
	return $affected_rows;
    } 
    catch(PDOException $e) 
    {
        echo "There was a problem deleting this member from this activity.";
        echo $e->getMessage();
    }

}
function addMemberToActivitySelected($PDOdbObject, $memId, $actId)
{
	try
	{
		$insertMemberSQL = "insert into `member_activity_selected` (`member_id`, `activity_id`) values (:mem, :act)";
		$insMem = $PDOdbObject->prepare($insertMemberSQL);
		$insMem->execute(array(
			        ':mem' => $memId,
			        ':act' => $actId
				));
		$affected_rows = $insMem->rowCount();
		return $affected_rows;
	}
	catch(PDOException $e)
	{
		echo "There was a problem inserting this member to this activity.";
		echo $e->getMessage();
	}
}

//STUB FOR NEW EVENT ACTIVITIES
function insertMemberNewEventActivities($PDOdbObject, $acts)
{
	try {
		//begin transaction
		$PDOdbObject->beginTransaction();

		$actId = 0;

		$insert = $PDOdbObject->prepare("INSERT INTO `member_activity` (`member_id`, `activity_id`) VALUES ($memberId, :activity_id)" );
		$insert->bindParam(':activity_id', $actId, PDO::PARAM_INT);

		foreach($acts as $actId)
		{
			$insert->execute();
		}
		//commit
		$PDOdbObject->commit();
		$affected_rows = $insert->rowCount();
		return $affected_rows;
	}//end try
	catch(PDOException $e)
	{
		//rollback transaction
		$PDOdbObject->rollBack();
		echo "There was a problem signing you up to these activities; rolling back the transaction.";
		echo $e->getMessage();
	}//end catch
}

//USED ON DO-ADD-EVENT
function insertNewEvent($PDOdbObject, $name, $date)
{
	try {
			$insertEv = $PDOdbObject->prepare("INSERT INTO `event` (`name`, `date`, `open`) VALUES (:name, :date, 1)" );
			$insertEv->bindParam(':name', $name, PDO::PARAM_STR);
			$insertEv->bindParam (':date', $date, PDO::PARAM_STR);

			$insertEv->execute();
			$affected_rows = $insertEv->rowCount();
			return $affected_rows;
		}
		catch(PDOException $e)
		{
			echo "There was a problem inserting this Event.";
			echo $e->getMessage();
		}
}

//UNUSED
function insertActivitiesToNewEvent($PDOdbObject, $eventId, $acts)
{
	try {
		//begin transaction
		$PDOdbObject->beginTransaction();

		$actId = 0;

		$insert = $PDOdbObject->prepare("INSERT INTO `activity` (`activity_id`) VALUES (:activity_id)" );
		$insert->bindParam(':activity_id', $actId, PDO::PARAM_INT);

		foreach($acts as $actId)
		{
                    $insert->execute();
		}
		//commit
		$PDOdbObject->commit();
		$affected_rows = $insert->rowCount();
		return $affected_rows;
	}
	catch(PDOException $e)
	{
		//rollback transaction
		$PDOdbObject->rollBack();
		echo "There was a problem signing you up to these activities; rolling back the transaction.";
		echo $e->getMessage();
	}
}

// UNUSED
function insertNewEventActivities($PDOdbObject, $eventId, $acts)
{
	try {
		//begin transaction
		$PDOdbObject->beginTransaction();

		$actId = 0;

		$insert = $PDOdbObject->prepare("INSERT INTO `member_activity` (`member_id`, `activity_id`) VALUES ($memberId, :activity_id)" );
		$insert->bindParam(':activity_id', $actId, PDO::PARAM_INT);

		foreach($acts as $actId)
		{
			$insert->execute();
		}
		//commit
		$PDOdbObject->commit();
		$affected_rows = $insert->rowCount();
		return $affected_rows;
	}
	catch(PDOException $e)
	{
		//rollback transaction
		$PDOdbObject->rollBack();
		echo "There was a problem signing you up to these activities; rolling back the transaction.";
		echo $e->getMessage();
	}
}

//this function is in fact a DELETE followed by an INSERT
//USED ON EDITVOL5-EDIT-INTS
function updateThisMemberInts($PDOdbObject, $memberId, $interests)
{
	if ($interests != 0)
	{
		try
		{
			//begin transaction
			$PDOdbObject->beginTransaction();

			$delInts = "DELETE FROM `member_interest` WHERE `member_id` = $memberId";
			$PDOdbObject->exec($delInts);

			$intId = 0;

			$upInt = $PDOdbObject->prepare(  "INSERT INTO member_interest (`member_id`,`interest_id`) VALUES ($memberId, :interest_id)" );
			$upInt->bindParam(':interest_id', $intId, PDO::PARAM_INT);

			foreach($interests as $intId)
			{
				$upInt->execute();
			}
			//commit
			$PDOdbObject->commit();
			$affected_rows = $upInt->rowCount();
			return $affected_rows;
		}
		catch (PDOException $e)
		{
			echo "There was a problem - rolling back the Update Interests transaction.";
			//rollback transaction
			$PDOdbObject->rollBack();
			echo $e->getMessage();
		}
	}//more than zero interests changed
}

function deleteActivity($PDOdbObject, $activityId)
{
	try
	{
		$deleteAct = $PDOdbObject->prepare("DELETE FROM `activity` WHERE `activity_id` = :act_id" );
		$deleteAct->execute();
		$deleteAct->bindValue(":act_id", $activityId, PDO::PARAM_INT);
		return true;
	}
	catch (PDOException $e)
	{
		echo $e->getMessage();
		return false;
	}
}
?>