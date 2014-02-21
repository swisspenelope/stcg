<?php 
require_once 'stcg-utilities.php';
require_once 'stcg-data-layer.php';
session_start();

/*************************** CALL INSERT NEW EVENT, GET LATEST EVENT ********************/
//$connectionObject = connect();
//$event = insertNewEvent($connectionObject, $_POST['eventName'], $_POST['eventDate']);
//$current_event_id = getLatestEvent($connectionObject);
$current_event_id=9;
/****************************************************************************************/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<HEAD>

<TITLE>
Create Activities for an Event
</TITLE>

<link rel="stylesheet" href="widgets/jqwidgets/styles/jqx.base.css" type="text/css"/>
<link rel ="stylesheet" type="text/css" href="css/theme.css"/>
<link rel ="stylesheet" type="text/css" href="css/structure.css"/>
<!-- link rel ="stylesheet" type="text/css" href="css/form.css" -->
<link rel ="stylesheet" type="text/css" href="css/themes/blue/style.css"/>


<script src="scripts/custom.js" type="text/javascript"></script>
<script src="scripts/jquery-ui-1.10.3/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/jquery.tablesorter.js" type="text/javascript"></script> 

<!-- ESSENTIAL JQWIDGETS LIBRARIES -->
<script type="text/javascript" src="widgets/jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxdragdrop.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxbuttons.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxlistbox.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxdropdownlist.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxscrollbar.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxdata.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxgrid.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxgrid.selection.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxgrid.sort.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxmenu.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxgrid.grouping.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxgrid.columnsresize.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxgrid.edit.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxgrid.columnsreorder.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxgrid.pager.js"></script>
<script type="text/javascript" src="widgets/jqwidgets/jqxexpander.js"></script>

<script type="text/javascript" src="widgets/scripts/gettheme.js"></script>
<script type="text/javascript"> 


$(document).ready(function () 
{
//SOURCE THE GRID CONTAINING ALL ACTIVITIES AT THIS EVENT:
    var src_Activities =
    {
        datatype: "json",
        datafields: [
        { name: 'activity_name'},
		{ name: 'activity_desc'},
		{ name: 'activity_short_code'},
		{ name: 'event_id'},
		{ name: 'capacity'},
		{ name: 'day_of_week'},
		{ name: 'date'},
		{ name: 'open'}
			        ],
		id: 'activity_id',
        url: 'stcg-json-responses.php?fct=getJSONAllActivitiesAtEvent&eventId=<?php echo $current_event_id?>',
        sortcolumn: 'day_of_week',
        sortdirection: 'asc',
        async: false,

        addrow: function (rowid, rowdata, position, commit) {
            // synchronize with the server - send insert command
            // call commit with parameter true if the synchronization with the server is successful 
            //and with parameter false if the synchronization failed.
            // you can pass additional argument to the commit callback which represents the new ID if it is generated from a DB.
            commit(true);
        },

        deleterow: function (rowid, commit) 
        {
            // synchronize with the server - send delete command
                var data = "delete=true&amp;" + $.param({actId:rowid});
        		$.ajax({
                    dataType: 'json',
                    url: 'stcg-activityCRUD.php',
        			cache: false,
                    data: data,
                    success: function (data, status, xhr) 
                    {
        				// delete command is executed.
        				commit(true);
        			},
        			error: function(jqXHR, textStatus, errorThrown)
        			{
        				commit(false);
        			}
        		});							
        },
        updaterow: function (rowid, rowdata, commit) 
        {
        	// synchronize with the server - send update command
                var data = "update=true&amp;" + $.param(rowdata);
      			$.ajax({
                    dataType: 'json',
                    url: 'stcg-activityCRUD.php',
        			cache: false,
                    data: data,
                    success: function (data, status, xhr) {
        				// update command is executed.
        				commit(true);
        			},
        			error: function(jqXHR, textStatus, errorThrown)
        			{
        				commit(false);alert(textStatus);
        			}			
        							
        		});		
        }
    };//end data source

    var adp_Activities = new $.jqx.dataAdapter(src_Activities);

    var columns = [
	  	            { text: 'Activity Title', datafield: 'activity_name', width: 300 },
	  	            { text: 'Description', datafield: 'activity_desc', width: 400 },
	  	            { text: 'Code', datafield: 'activity_short_code', width: 50 },
		  	        { text: 'Cap', datafield: 'capacity', width: 50 },
		  	        { text: 'Day', datafield: 'day_of_week', width: 75 }
	  	         ];

//INITIALIZE GRID
	    $("#jqxgrid_Activities").jqxGrid(
	    {
	    	width: 900,
	        height: 700,
	        source: adp_Activities,
	        sortable: true,
	        theme: 'classic',
	        selectionmode: 'singlerow',
	        columns: columns,
	        editable: true
	     });
	     
	     //$("#jqxgrid_Activities").jqxGrid('addrow', null, []);
	     
	    //var value = $('#jqxgrid_Activities').jqxGrid('addrow', null, []);

	     //$("#addrowbutton").jqxButton([]);
	    // create new row.
	     $("#addrowbutton").bind('click', function () {
    		$("#jqxgrid_Activities").jqxGrid('addrow', null, []);
	    });

        // create new row.
  /*      $("#addrowbutton").bind('click', function () {
            var id = $("#jqxgrid").jqxGrid('getdatainformation').rowscount;
            $("#jqxgrid").jqxGrid('addrow', id, []);
        });*/
                
	    // delete row.
	    $("#deleterowbutton").bind('click', function () {
	        var selectedrowindex = $("#jqxgrid_Activities").jqxGrid('getselectedrowindex');
	        var rowscount = $("#jqxgrid_Activities").jqxGrid('getdatainformation').rowscount;
	        if (selectedrowindex >= 0 && selectedrowindex < rowscount) 
		    {
	            var id = $("#jqxgrid_Activities").jqxGrid('getrowid', selectedrowindex);
	            $("#jqxgrid_Activities").jqxGrid('deleterow', id);
	        }
	    });
});
</script>                

</HEAD>
<BODY>

<DIV id="content">
	<H3>Create Activities for the new Event<?php //echo $eventJustAdded['name']; ?></H3>
 <div id='jqxWidget' style="font-size: 13px; font-family: Verdana; float: left;">
        <div style="float: left;" id="jqxgrid_Activities">
        </div>
        <div style="margin-left: 30px; float: left;">
            <div>
                <input id="addrowbutton" type="button" value="Add New Row" />
            </div>
            <div style="margin-top: 10px;">
                <input id="deleterowbutton" type="button" value="Delete Selected Row" />
            </div>
            <div style="margin-top: 10px;">
                <input id="updaterowbutton" type="button" value="Save all" />
            </div>
        </div>
    </div>
</DIV>		


</BODY>
</HTML>