<?php
header("Content-Type: text/html;charset=utf-8");

require_once 'stcg-config.php';
require_once 'stcg-utilities.php';
require_once 'stcg-data-layer.php';

/******************** CALL GET EVENT FUNCTION TO DISPLAY LATEST EVENT ******************/
/****************************************************************************************/
$connectionObject = connect();
$event = getLatestEvent($connectionObject);

/* Only one Event may be active at any time, the latest Event by #ID in `event` table. */

/****************************************************************************************/
?>
<HTML>
<HEAD>
<TITLE>List members assigned to Activities</TITLE>
<script src="scripts/custom.js" type="text/javascript"></script>
<script src="scripts/jquery-ui-1.10.3/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>

<link rel="stylesheet" href="widgets/jqwidgets/styles/jqx.base.css" type="text/css"/>
<link rel ="stylesheet" type="text/css" href="css/theme.css">
<link rel ="stylesheet" type="text/css" href="css/structure.css">
<link rel ="stylesheet" type="text/css" href="css/form.css">

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
	$("#back").click(function () {
        window.location="";
	});
//SOURCE THE GRID CONTAINING ASSIGNED MEMBERS:
    var data1 =
    {
        datatype: "json",
        datafields: [
        { name: 'id'},
		{ name: 'name_first'},
		{ name: 'name_last'},
		{ name: 'activity_short_code'},
		{ name: 'capacity'},
		{ name: 'project_leader'},
		],
		url: 'stcg-json-responses.php?fct=getJSONMembersAssignedToActivities&eventId=<?php echo $event['id'];?>',
        sortcolumn: 'activity_short_code',
        sortdirection: 'asc',
        async: false
    };//end data source
    
//ASSIGN ADAPTER 1 TO SOURCE DATA
	var adapter1 = new $.jqx.dataAdapter(data1);

	var columns = [
    	  	      { text: 'Id', datafield: 'id', width: 35 },
    	  	      { text: 'First Name', datafield: 'name_first', width: 120 },
    	  	      { text: 'Last Name', datafield: 'name_last', width: 120 },
      	  	      { text: 'Activity', datafield: 'activity_short_code', width: 78 },
				  { text: 'Capacity', datafield: 'capacity', width: 78 },
				  { text: 'Proj. Leader', datafield: 'project_leader', width: 120 }
    	  	      ];

    //INITIALIZE GRID 1
    	    $("#jqxgrid1").jqxGrid(
    	    {
    	    	width: 600,
    	        height: 600,
    	        source: adapter1,
    	        sortable: true,
    	        theme: 'classic',
    	        selectionmode: 'singlerow',
    	        editable: true,
    	        columns: columns
    	    });//end data source
});    
</script>
</HEAD>
<BODY>
<H3>List of all Members assigned to Activities so far</H3>
<fieldset style = "border: solid #AAAAAA 1px; width: 570px; padding: 20px; padding-top: 20px;">
<DIV id = "jqxgrid1" style="FLOAT: LEFT;" ></DIV>	
</fieldset>
<BR /><BR />
<INPUT TYPE="BUTTON" ID="back" VALUE="Back"></INPUT>
</BODY>
</HTML>