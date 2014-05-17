<?php
require_once 'header.php';

$connectionObject = connect();
//TODO first create the Event
$event = getLatestEvent($connectionObject);
$_SESSION['eventId'] = $event['id'];
//echo $_SESSION['eventId'];
?>
<head> 
<title>
Create New Event and Activities page
</title>
<script type="text/javascript">

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
        
////////////// THIS IS THE UPDATE / ADD ROW THAT HAS TO BE CALLED /////////
        updaterow: function (rowid, rowdata, commit) 
        {
            //alert("at start of update function, here is rowid value [" + rowid + "]");
            if ($("#activity_id").val() === "" || $("#activity_id").val() === null)
            //if (rowid === "" || rowid === null)
            {
                var insData = "activity_name=" + rowdata.activity_name + "&activity_desc=" + rowdata.activity_desc + "&activity_short_code=" + rowdata.activity_short_code + "&capacity=" + rowdata.capacity + "&date=" + rowdata.date + "&project_leader=" + rowdata.project_leader + "&open=" + rowdata.open + "&evId=" + thisEvent;  
                //rowdata.open
               
                $.ajax({
                dataType: 'json',    
                url: 'stcg-json-responses.php?fct=insertNewActivityToNewEvent',
                data: insData,
                cache: false,
                success: myCallback,
                error: myCallbackError
                    }); 
            } 
            else
            {
//update table activity

                var data = "activity_id=" + rowdata.activity_id + "&activity_name=" + rowdata.activity_name + "&activity_desc=" + rowdata.activity_desc + "&activity_short_code=" + rowdata.activity_short_code + "&capacity=" + rowdata.capacity + "&date=" + rowdata.date + "&project_leader=" + rowdata.project_leader + "&open=" + rowdata.open;            
            //alert(data);    
                $.ajax({
                   dataType: 'json',
                   url: 'stcg-json-responses.php?fct=updateSelectedActivity&actId=' + rowdata.activity_id,
                   data: data,
                   cache: false,
                   success: myCallback,
                   error: myCallbackError
                   });
            }
        }
    };//end data source
//create the dataAdapter for the Grid
    var adapter = new $.jqx.dataAdapter(data);
    var editrow = -1;//editrow is merely the row num of the selected row
 // initialize jqxGrid
    $("#jqxgrid").jqxGrid(
    {
        width: 1240,
        source: data,
        sortable: true,
        pageable: false,
        editable: false,
        autoheight: true,
        autorowheight: true,
        columns:  
        [
            {text: '#', datafield: 'activity_id', width: 40},
            {text: 'Act. Name', datafield: 'activity_name', width: 260},
            {text: 'Act. Desc', datafield: 'activity_desc', width: 400},
            {text: 'Code', datafield: 'activity_short_code', width: 60},
            {text: 'Cap.', datafield: 'capacity', width: 40},
            {text: 'Date', datafield: 'date', columntype: 'datetimeinput', width: 140, cellsalign: 'right'},
            {text: 'PL', datafield: 'project_leader', width: 240},
          /*  {text: 'Open', datafield: 'open', width: 40}, */
            {text: 'Edit', datafield: 'Edit', columntype: 'button', cellsrenderer: function () 
                {
                   return "Edit";
                }, buttonclick: function (row) 
                {
               // open the popup window when the user clicks a button.
               //alert("this is the row id being called in edit click of grid construct: " + row);
                    editrow = row;
                    var offset = $("#jqxgrid").offset();
                    $("#editWindow").jqxWindow(
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
                    $("#editWindow").jqxWindow('open');
                }
            }
        ]  
    });
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
    
    $("#date").width(150);
    $("#date").height(40);

    $("#project_leader").width(200);
    $("#project_leader").height(40);
    $("#open").width(200);
    $("#open").height(40);
    
    //$("#jqxWidget").jqxDateTimeInput({ animationType: 'fade', width: '150px', height: '25px', animationType: 'fade', dropDownHorizontalAlignment: 'right'});

 // initialize the popup window and buttons.
    $("#editWindow").jqxWindow({
        width: 800, resizable: true, isModal: true, autoOpen: false, cancelButton: $("#Cancel"), modalOpacity: 0.01      
    });
    $("#editWindow").on('open', function () 
    {
        //$("#open").val(1);
    });

//button initializers
    $("#NewRow").jqxButton({ theme: 'classic' });
    $("#DeleteRow").jqxButton({ theme: 'classic' });
    $("#Cancel").jqxButton({ theme: 'classic' });
    $("#Save").jqxButton({ theme: 'classic' });
    
//row select handler
$('#jqxgrid').on('rowclick', function (event) 
{
    var args = event.args;
    del = args.rowindex;
    //alert(del);
 //same thing   
    var id = $('#jqxgrid').jqxGrid('getrowid', del);
    //alert(id);
}); 


//button handlers  
// create new row.
        $("#NewRow").bind('click', function () 
        {
            $('#jqxgrid').jqxGrid('sortby', 'activity_id', 'asc');    
            
            var allRows = $("#jqxgrid").jqxGrid('getrows').length;
            var newRow = allRows+1;
            $("#jqxgrid").jqxGrid('addrow', newRow, {});
            setInputFormToEmpty();
            editrow = newRow;
            //alert("this new row number is " + editrow);//10
        // show the popup window.
            $("#editWindow").jqxWindow('open');
         });
         
        $("#DeleteRow").on('click', function () {
           var selectedrowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
           var rowscount = $("#jqxgrid").jqxGrid('getdatainformation').rowscount;
           if (selectedrowindex >= 0 && selectedrowindex < rowscount) 
           {
               var id = $("#jqxgrid").jqxGrid('getrowid', selectedrowindex);
               var commit = $("#jqxgrid").jqxGrid('deleterow', id);
           }
       });       
 
// update the edited row when the user clicks the 'Save' button.
        // note that 'Save' is inside popup window
        $("#Save").click(function () 
        {  
            //alert ("on save button, click row num is " + editrow);    
        if (editrow >= 0) //10
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
//open: $("#open").val(),
            //var rowID = $('#jqxgrid').jqxGrid('getrowid', editrow);
            
//call the famous update row function
            $('#jqxgrid').jqxGrid('updaterow', editrow, row);//row is an object with all row values in it
            $("#editWindow").jqxWindow('close');
        }
    });
    //alert("just before end of page loading code, number of rows in grid so far is " + $("#jqxgrid").jqxGrid('getrows').length);    
});   //end document ready

function validateRowContents()
{
    var isOk = true;  

    if ($("#activity_name").val() === "")
    {
        isOk = false;
        alert("Please enter a short name for this Activity.");
        $("#activity_name").focus();
        return;
    }

   if ($("#activity_desc").val() === "")
    {
        isOk = false;
        alert("Please enter a longer description of what this Activity entails.");
        $("#activity_desc").focus();
        return;
    }

    if ($("#activity_short_code").val() === "")
    {
        isOk = false;
        alert("Please enter the Activity's Short Code.");
        $("#activity_short_code").focus();
        return;
    }

    if ($("#date").val() === "" )
    {
        isOk = false;
        alert("Please enter the Activity's start date.");
        $("#date").focus();
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

function setInputFormToEmpty()
{
    $("#activity_id").val('');
    $("#activity_name").val('');
    $("#activity_desc").val('');
    $("#activity_short_code").val('');
    $("#capacity").val('');
    $("#date").val('');
    $("#project_leader").val('');
    $("#open").val('');
}
</script>
</head>
<body>
     <h2>Add Activities to new Event: <?php echo $event['name'] ?></h2>
    <div style="padding: 10px;">
        <input type="button" id="NewRow" value="Add New Row" />
        <input type="button" id="DeleteRow" value="Delete Selected Row" /> 
    </div>
    <div id='jqxWidget'>
        <div id="jqxgrid"></div>
        <div style="margin-top: 30px;">
            <div id="cellbegineditevent"></div>
            <div style="margin-top: 10px;" id="cellendeditevent"></div>
       </div>
       <!--///////////////////// POPUP WINDOW ///////////////////////////////-->
       <div id="editWindow">
            <div>Edit</div>
            <div style="float: left; overflow: hidden;">
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
                        <td align="left"><input id="open"></td>
                    </tr>
                    <tr>
                        <td align="right"><input type="hidden" value=thisEvent></td>
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