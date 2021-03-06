<?php
include_once 'header.php';

/* 
 * load latest event activities, with checkboxes
 * load reg form and validation
 * save member event and member activity
 *
 */
session_destroy();

$connectionObject = connect();
$event = getLatestEvent($connectionObject);
$_SESSION['eventId'] = $event['id'];
/*
if (isset($_GET['memId']))
{
    $_SESSION['memberId'] = $_GET['memId'];
}*/
if ($_SESSION['user'])
{
    $_SESSION['memberId'] = $_GET['memId'];
}
 else 
{
    header('Location: stcg-vol1-login.php');  
}
?>

<head> 
<title>
Signup for an Event
</title>
<script type="text/javascript">
//init array to be used when saving activity ids to db
var selectedRows = new Array();
  
$(document).ready(function ()
{
    var thisEvent = <?php echo $_SESSION['eventId'] ?>;
//alert("this member is " + <?php echo $_SESSION['memberId']?> + " and event id is " + thisEvent);
    var data =
    {
        datatype: "json",
        datafields:
        [
            {name: 'activity_id'},
            {name: 'activity_name'},
            {name: 'activity_desc'},
            {name: 'activity_short_code'},
            {name: 'event_id'},
            {name: 'date'},
            {name: 'open'}
        ],
        //url: 'stcg-json-responses.php?fct=getJSONAllActivitiesAtEvent&eventId=<?php echo $_SESSION['eventId'] ?>',
        url: 'stcg-json-responses.php?fct=getJSONAllOpenActivitiesAtEvent&eventId=' + thisEvent,
        sortcolumn: 'activity_id',//note rowid order follows activity_id!
        sortdirection: 'asc',
        async: false
    };//end data source
//create the dataAdapter for the Grid
    var adapter = new $.jqx.dataAdapter(data);
    
 // initialize jqxGrid
    $("#jqxgrid").jqxGrid(
    {
        width: 605,
        source: data,
        sortable: true,
        pageable: false,
        editable: false,
        selectionmode: 'checkbox',
        autoheight: true,
        columns:  
        [
           {text: '', datafield: 'activity_id', width: 40},
         /*   {text: 'Act. Name', datafield: 'activity_name', width: 300},*/
		{text: '', datafield: 'activity_desc', width: 450},
        /*    {text: 'Code', datafield: 'activity_short_code', width: 100},*/
            {text: 'Date', datafield: 'date', width: 100}
          /*  {text: 'Status', datafield: 'open', width: 70}*/
         ]  
    });
    
    //build array of selected activities 
    selectedRows.length = 0;
    //var arraySize = 0;    
    $('#jqxgrid').on('rowselect', function (event) 
    {
        var thisRow = event.args.rowindex;
        
        var idCell = $('#jqxgrid').jqxGrid('getcell', thisRow, 'activity_id');
        //if they click the All Rows box, null is returned
        if (idCell === null)
        {
            //select all rows' values
            for (var i = 0; i <= thisRow.length; i++)
            {
                idCell = $('#jqxgrid').jqxGrid('getcell', i, 'activity_id');
                //alert("pushing act id " + idCell.value);
                selectedRows.push(idCell.value);
            }
        }
        else
        {
            selectedRows.push(idCell.value); 
            //alert("pushing act id " + idCell.value);
        }
     });
     
      //button initializers
    $("#Send").jqxButton({ theme: 'classic' });

    //button handlers  
    $("#Send").click(function () 
    {  
        //alert ("on Send button, print array " + selectedRows); 
        insertMemberActivities(myCallback);
    });
});

function insertMemberActivities()///add sth to prevent them signing up for same event twice! (unique key error)
{
    var actString = selectedRows.toString();
    var data = "memId=" + <?php echo $_SESSION['memberId'];?> + "&acts=" + actString + "&comments=" + document.getElementById('comments').value;

    $.ajax({
        dataType: 'json',
        url: 'stcg-json-responses.php?fct=insertMemberActivities',
         data: data,
            cache: false,
            success: myCallback,
            error: myCallbackError
    });
}
////////// CALLBACK - SUCCESS /////////////////////////
function myCallback(response)
{
        //ajax call one or more rows inserted
        //alert(typeof response);
	if ( response > 0)
	{  
		//alert("Thank you for your registration! / Merci de votre inscription!");
		window.top.location="http://www.servethecitygeneva.ch/index.php?page_id=3292";
	}
}

////////// CALLBACK - FAILURE /////////////////////////
/*Error callback is called on http errors, but also if JSON parsing on the response fails.
This is what's probably happening if response code is 200 but you still are thrown to error callback.*/
function myCallbackError(response, jqXHR, settings, status) 
{
    if (isNaN(response))
    {    
        response = response.responseText;

        var detail = response.substr(55,15);

        if ( detail == "SQLSTATE[23000]")
        {
            window.top.location="http://www.servethecitygeneva.ch/index.php?page_id=3299";
        }
    }
}   
</script>
</head>
<body>
     <h2>Sign up for this Event: <?php echo $event['name'] ?></h2>
     <h2 class="fre">Journée Mandela</h2>

    <div id='jqxWidget'>
        <div id='jqxgrid' style="margin-bottom: 20px;"></div>
        <div style="width: 92%; padding: 20px; padding-top: 10px">
            <div style="clear: both; float: left;">
                <span class="eng">Comments<br />(Time constraints? Bringing friends?)</span>
                    /<br /><span class="fre">Commentaires<br />(Horaire? Vous venez avec des ami(e)s?)</span>
            </div>
            <div>
                    <textarea id="comments" name="comments" cols="32" rows="3"></textarea>
            </div>
        </div>
        <div style="padding-top: 20px;"><input type="button" id="Send" value="Send" /></div>
    </div>
    <!--h3>You may choose several mutually exclusive activities (that are on the same day at the same time).</h3>
     <p>We will put you wherever the need is greatest. However, if you have a strong preference for one activity, note your preference in the Comments box.</p>
     
     <h3 class="fre">Vous pouvez sélectionner plusieurs activités mutuellement exclusives (c'est à  dire qui ont lieu le même jour à la même heure.</h3>
     <p class="fre">Nous vous affecterons là où le besoin est le plus grand. Cependant, si vous avez une forte préférence pour une activité, inscrivez votre préférence dans la case Commentaires.</p-->
     
</body>
</html>
