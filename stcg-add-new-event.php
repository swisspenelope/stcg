<?php
require_once 'header.php';

//TODO first create the Event
$_SESSION['eventId'] = 10;
?>
<head> 
<title>
Create New Event and Activities page
</title>
<script type="text/javascript">
 /* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */    
$(document).ready(function ()
{
    // prepare the data
    /* Get contents of Activity table that match Event ID
     * event if there are none.
     */
var thisEvent = <?php echo $_SESSION['eventId'] ?>;

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
            {name: 'capacity'},
            {name: 'date'},
            {name: 'project_leader'},
            {name: 'open'}
        ],
        url: 'stcg-json-responses.php?fct=getJSONAllActivitiesAtEvent&eventId=<?php echo $_SESSION['eventId'] ?>',
        sortcolumn: 'activity_id',//note rowid order follows activity_id!
        sortdirection: 'asc',
        async: false,

        updaterow: function (rowid, rowdata, commit) 
        {
            var data = "activity_id=" + rowdata.activity_id + "&activity_name=" + rowdata.activity_name + "&activity_desc=" + rowdata.activity_desc + "&activity_short_code=" + rowdata.activity_short_code + "&capacity=" + rowdata.capacity + "&date=" + rowdata.date + "&project_leader=" + rowdata.project_leader + "&open=" + rowdata.open;

            if ($("#activity_id").val()!== "")
            {
                //update table activity
             
                $.ajax({
                   dataType: 'json',
                   url: 'stcg-json-responses.php?fct=updateSelectedActivity&actId=' + rowdata.activity_id,
                   data: data,
                   cache: false,
                   success: myCallback,
                   error: myCallbackError
                   });
            } 
            else
            {
                var insData = "activity_name=" + rowdata.activity_name + "&activity_desc=" + rowdata.activity_desc + "&activity_short_code=" + rowdata.activity_short_code + "&capacity=" + rowdata.capacity + "&date=" + rowdata.date + "&project_leader=" + rowdata.project_leader + "&open=" + rowdata.open + "&evId=" + thisEvent;

                $.ajax({
                    dataType: 'json',    
                    url: 'stcg-json-responses.php?fct=insertNewActivityToNewEvent',
                    data: insData,
                    cache: false,
                    success: myCallback,
                    error: myCallbackError
                    }); 
            }
        }
    };//end data source

    // initialize the input fields.
    $("#activity_id").jqxInput({ theme: 'classic' });
    $("#activity_name").jqxInput({ theme: 'classic' });
    $("#activity_desc").jqxInput({ theme: 'classic' });
    $("#activity_short_code").jqxInput({ theme: 'classic' });
    $("#capacity").jqxInput({ theme: 'classic' });
    $("#date").jqxInput({ theme: 'classic' });
    $("#project_leader").jqxInput({ theme: 'classic' });
    $("#open").jqxInput({ theme: 'classic' });

    $("#activity_id").width(200);
    $("#activity_id").height(40);
        
    $("#activity_name").width(400);
    $("#activity_name").height(40);
    $("#activity_desc").width(600);
    $("#activity_desc").height(40);
    $("#activity_short_code").width(200);
    $("#activity_short_code").height(40);
    $("#capacity").width(200);
    $("#capacity").height(40);
    $("#date").width(200);
    $("#date").height(40);
    $("#project_leader").width(200);
    $("#project_leader").height(40);
    $("#open").width(200);
    $("#open").height(40);

    var adapter = new $.jqx.dataAdapter(data);

    var editrow = -1;//editrow is merely the row num of the selected row

    // initialize jqxGrid
    $("#jqxgrid").jqxGrid(
    {
        width: 1200,
        source: data,
        sortable: true,
        pageable: false,
        editable: false,
        autoheight: true,
        columns:  
        [
            {text: '#', datafield: 'activity_id', width: 40},
            {text: 'Act. Name', datafield: 'activity_name', width: 240},
            {text: 'Act. Desc', datafield: 'activity_desc', width: 400},
            {text: 'Code', datafield: 'activity_short_code', width: 60},
            {text: 'Cap.', datafield: 'capacity', width: 40},
            {text: 'Date', datafield: 'date', width: 90},
            {text: 'PL', datafield: 'project_leader', width: 160},
            {text: 'Open', datafield: 'open', width: 40},
            {text: 'Edit', datafield: 'Edit', columntype: 'button', cellsrenderer: function () 
                {
                   return "Edit";
                }, buttonclick: function (row) 
                {
               // open the popup window when the user clicks a button.
         
                    editrow = row;
                    var offset = $("#jqxgrid").offset();
                    $("#popupWindow").jqxWindow(
                    { position: { x: parseInt(offset.left) + 60, y: parseInt(offset.top) + 60 } });
               // get the clicked row's data and initialize the input fields.
                    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', editrow);
                    $("#activity_id").val(dataRecord.activity_id);
                    $("#activity_name").val(dataRecord.activity_name);
                    $("#activity_desc").val(dataRecord.activity_desc);
                    $("#activity_short_code").val(dataRecord.activity_short_code);
                    $("#capacity").val(dataRecord.capacity);
                    $("#date").val(dataRecord.date);
                    $("#project_leader").val(dataRecord.project_leader);
                    $("#open").val(dataRecord.open);

               // show the popup window.
                    $("#popupWindow").jqxWindow('open');
                }
            }
        ]
    });

    // initialize the popup window and buttons.
    $("#popupWindow").jqxWindow({
        width: 800, resizable: true, isModal: true, autoOpen: false, cancelButton: $("#Cancel"), modalOpacity: 0.01           
    });
    $("#popupWindow").on('open', function () {
        $("#activity_name").jqxInput('selectAll');
    });

//button initializers
    $("#NewRow").jqxButton({ theme: 'classic' });
    $("#Cancel").jqxButton({ theme: 'classic' });
    $("#Save").jqxButton({ theme: 'classic' });

//button handlers  
        // create new row.
        $("#NewRow").bind('click', function () 
        {
            var id = $("#jqxgrid").jqxGrid('getdatainformation').rowscount;
            $("#jqxgrid").jqxGrid('addrow', id, {});
        });

        // update the edited row when the user clicks the 'Save' button.
        // note that 'Save' is inside popup window
        $("#Save").click(function () 
        {  
            if (editrow >= 0) 
            {
		validateRowContents();
                var row = 
                {   
                    activity_id: $("#activity_id").val(), 
                    activity_name: $("#activity_name").val(), 
                    activity_desc: $("#activity_desc").val(), 
                    activity_short_code: $("#activity_short_code").val(), 
                    capacity: $("#capacity").val(), 
                    date: $("#date").val(), 
                    project_leader: $("#project_leader").val(), 
                    open: $("#open").val(),
                    event_id: thisEvent
                };

                var rowID = $('#jqxgrid').jqxGrid('getrowid', editrow);
                $('#jqxgrid').jqxGrid('updaterow', rowID, row);//row is an object with all row values in it
                
                $("#popupWindow").jqxWindow('hide');
        }
    });
});

function validateRowContents()
{
    var isOk = true;   
    if ($("#activity_name").val() == "")
    {
        isOk = false;
        alert("Please enter a short name for this Activity.");
        $("#activity_name").val().focus();
        return;
    }

   if ($("#activity_desc").val() == "")
    {
        isOk = false;
        alert("Please enter a longer description of what this Activity entails.");
        $("#activity_desc").val().focus();
        return;
    }

    if ($("#activity_short_code").val() == "")
    {
        isOk = false;
        alert("Please enter the Activity's Short Code.");
        $("#activity_short_code").val().focus();
        return;
    }

    if ($("#date").val() == "")
    {
        isOk = false;
        alert("Please enter the Activity's start date.");
        $("#date").val().focus();
        return;
    }
}

function myCallback(response)
{
//ajax call returns one row updated

    if (response > 0)
    {
        $('#jqxgrid').jqxGrid('updatebounddata');
    }
    else {
        alert("No update could be done! Unknown error in data layer.");
    }
}
function myCallbackError(jqXHR, textStatus, errorThrown )
//ajax call returns any kind of error
{
     alert("Could not update this grid - " + errorThrown + ", status: " + textStatus);
}
</script>
</head>
<body>
     <p>Add Activities</p>
    <div style="padding: 10px; float: left;"><input type="button" id="NewRow" value="Add New Row" /></div>
    <div id='jqxWidget'>
        <div id="jqxgrid"></div>
        <div style="margin-top: 30px;">
            <div id="cellbegineditevent"></div>
            <div style="margin-top: 10px;" id="cellendeditevent"></div>
       </div>
       <!--///////////////////// POPUP WINDOW ///////////////////////////////-->
       <div id="popupWindow">
            <div>Edit</div>
            <div style="overflow: hidden;">
                <table>
                    <tr>
                        <td align="right">Activity Id:</td>
                        <td align="left"><input id="activity_id" readonly style="background-color: #e2e2e2" /></td>
                    </tr>
                    <tr>
                        <td align="right">Activity Name:</td>
                        <td align="left"><input id="activity_name" /></td>
                    </tr>
                    <tr>
                        <td align="right">Activity Desc:</td>
                        <td align="left"><input id="activity_desc" /></td>
                    </tr>
                    <tr>
                        <td align="right">Code:</td>
                        <td align="left"><input id="activity_short_code" /></td>
                    </tr>
                    <tr>
                        <td align="right">Capacity:</td>
                        <td align="left"><input id="capacity"></td>
                    </tr>
                    <tr>
                        <td align="right">Date:</td>
                        <td align="left"><input id="date"></td>
                    </tr>
                    <tr>
                        <td align="right">Project Leader:</td>
                        <td align="left"><input id="project_leader"></td>
                    </tr>
                    <tr>
                        <td align="right">Open:</td>
                        <td align="left"><input id="open" value="1"><input type="hidden" id="event_id" value=thisEvent></td>
                    </tr>
                    <tr>
                        <td align="right"></td>
                        <td style="padding-top: 10px;" align="right">
                            <input style="margin-right: 5px;" type="button" id="Save" value="Save" />
                            <input id="Cancel" type="button" value="Cancel" />
                        </td>
                    </tr>
                </table>
            </div>
       </div>
    </div>
</body>
</html>