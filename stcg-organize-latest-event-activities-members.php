<?php
include_once 'header.php';

/*********************** CALL GET LATEST EVENT TO GET EVENT ID **********************/
/*************************************************************************************/
	$connectionObject = connect();
	$event = getLatestEvent($connectionObject);

/*************************************************************************************/

if (empty($event))
{
	echo "There were either no members or this Event ID # is wrong.";
}
else
{
	$_SESSION['eventId'] = 10;
	//$_SESSION['eventId'] = $event['id'];
	$_SESSION['eventName'] = $event['name'];
	$_SESSION['eventDate'] = $event['date'];
}
?>

<TITLE>Organize Members at all Activities</TITLE>

<script type="text/javascript">
$(document).ready(function ()
{
//SOURCE THE GRID CONTAINING ALL SIGNUPS:
    var data1 =
    {
        datatype: "json",
        datafields: [
		{ name: 'id'},
		{ name: 'name_first'},
		{ name: 'name_last'},
		{ name: 'email'},
		{ name: 'phone'},
		{ name: 'activity_short_code'},
		{ name: 'activity_name'},
		{ name: 'capacity'}
			        ],
        url: 'stcg-json-responses.php?fct=getJSONMembersAtEvent&eventId=<?php echo $_SESSION['eventId'] ?>',
        sortcolumn: 'name_last',
        sortdirection: 'asc',
        async: false
    };//end data source

//ASSIGN ADAPTER 1 TO SOURCE DATA
    var adapter1 = new $.jqx.dataAdapter(data1);

    var columns = [
	  	            { text: 'First Name', datafield: 'name_first', width: 90 },
	  	            { text: 'Last Name', datafield: 'name_last', width: 110 },
                            { text: 'Email', datafield: 'email', width: 180 },
                            { text: 'Phone', datafield: 'phone', width: 110 },
                            { text: 'Code', datafield: 'activity_short_code', width: 50 },
	  	            { text: 'Activity', datafield: 'activity_name', width: 250 },
                            { text: 'Chosen', datafield: 'chosen', columntype: 'checkbox', width: 67 },
	  	         ];

//INITIALIZE GRID 1
	    $("#jqxgrid1").jqxGrid(
	    {
	    	width: 620,
	        height: 450,
	        source: adapter1,
	        sortable: true,
	        theme: 'classic',
	        selectionmode: 'singlerow',
	        columns: columns,

//rendering for drag-drop functionality
	        rendered: function ()
	        {
                // select all grid cells.
                var gridCells = $('#jqxgrid1').find('.jqx-grid-cell');
                if ($('#jqxgrid1').jqxGrid('groups').length > 0)
                {
                    gridCells = $('#jqxgrid1').find('.jqx-grid-group-cell');
                }
                // initialize the jqxDragDrop plug-in. Set its drop target to the second Grid.
                gridCells.jqxDragDrop(
                {
                    appendTo: 'body', theme: 'classic', dragZIndex: 99999,
                    dropAction: 'none',
                    initFeedback: function (feedback)
                    {
                        feedback.height(120);
                        feedback.width(220);
                    }
                });

                // initialize the dragged object.
                gridCells.off('dragStart');
                gridCells.on('dragStart', function (event)
                {
                    var position = $.jqx.position(event.args);
                    var cell = $("#jqxgrid1").jqxGrid('getcellatposition', position.left, position.top);

                    $(this).jqxDragDrop('data', $("#jqxgrid1").jqxGrid('getrowdata', cell.row));
                    //var value = $('#jqxgrid1').jqxGrid('getcellvaluebyid', id1, "activity_short_code");

                    var groupslength = $('#jqxgrid1').jqxGrid('groups').length;

                    // update feedback's display value.

                    var feedback = $(this).jqxDragDrop('feedback');
                    var feedbackContent = $(this).parent().clone();
                    var table = '<table>';
                    $.each(feedbackContent.children(), function (index)
                    {
                        if (index < groupslength)
                            return true;
                            table += '<tr>';
                            table += '<td>';
                            table += columns[index - groupslength].text + ': ';
                            table += '</td>';
                            table += '<td>';
                            table += $(this).text();
                            table += '</td>';
                            table += '</tr>';
                    });
                    table += '</table>';
                    feedback.html(table);
                });//end drag start

                gridCells.off('dragEnd');
                gridCells.on('dragEnd', function (event)
                {
                        var value = $(this).jqxDragDrop('data');
                        var position = $.jqx.position(event.args);
                        var pageX = position.left;
                        var pageY = position.top;
                        var $destination = $("#jqxgrid2");
                        var targetX = $destination.offset().left;
                        var targetY = $destination.offset().top;
                        var width = $destination.width();
                        var height = $destination.height();

                        // fill the grid if the user dropped the dragged item over it.
                        if (pageX >= targetX && pageX <= targetX + width)
                        {
                            if (pageY >= targetY && pageY <= targetY + height)
                            {
                                $destination.jqxGrid('addrow', null, value);
                            }
                        }//end if
                });//end drag end
	        }//end rendered function
       });//end grid 1

	   $("#excelExport").jqxButton(
	    {
			theme: 'energyblue'
		});

		$("#excelExport").click(function()
		{
			$("#jqxgrid1").jqxGrid('exportdata', 'xls', 'jqxGrid');
		});

var currentActId=-1;

//SOURCE THE GRID CONTAINING ONLY SIGNUPS SELECTED FOR ONE ACTIVITY:
    var data2 =
    {
	    datatype: "json",
	    datafields: [
		{ name: 'id'},
		{ name: 'name_first'},
		{ name: 'name_last'},
		{ name: 'email'},
		{ name: 'phone'},
		{ name: 'activity_id'},
		{ name: 'activity_short_code'}
		        ],
        id: 'id',
        async: false,
        url: 'stcg-json-responses.php?fct=getJSONSelectedMembersAtActivity&eventId=<?php echo $_SESSION['eventId'] ?>&actId='+currentActId,

    };

var columnCheckBox = null;
var updatingCheckState = false;
//ASSIGN ADAPTER 2 TO SOURCE DATA
    var adapter2 = new $.jqx.dataAdapter(data2);

//INITIALIZE GRID 2
		$("#jqxgrid2").jqxGrid(
		{
			width: 600,
	        height: 200,
	        source: adapter2,
	        sortable: true,
			selectionmode: 'singlerow',
	        theme: 'classic',
	        keyboardnavigation: false,

	        columns: [
       	  	            { text: 'First Name', datafield: 'name_first', width: 90 },
       	  	            { text: 'Last Name', datafield: 'name_last', width: 130 },
						{ text: 'Email', datafield: 'email', width: 220 },
						{ text: 'Phone', datafield: 'phone', width: 110 },
       	  	            { text: 'Code', datafield: 'activity_short_code', width: 50 }
		       	  	 ]
		});//end grid 2

//EVENT HANDLERS
		$('#jqxgrid2').on('rowselect', function (event)
		{
///////DO NOT DELETE! IT'S THE ONLY COMBO THAT WORKS!!!/////////////
var row = $("#jqxgrid2").jqxGrid('getrowdata', event.args.rowindex);
			//alert(row.id);//returns member id
		});

//SOURCE THE DROP-DOWN LIST OF ACTIVITY SHORT CODES
        var actListData =
	    {
        		datatype: "json",
    	        datafields:
        	    [
        			{ name: 'activity_short_code'},
        			{ name: 'capacity'},
        			{ name: 'activity_id'},
        			{ name: 'project_leader'}
            	],
    	        url: "stcg-json-responses.php?fct=getJSONAllSCsAtEvent&eventId=<?php echo $_SESSION['eventId'] ?>",
    	        async: false
    	};//end data

//ASSIGN ADAPTER ACTLIST TO SOURCE DATA
       var adapterActList = new $.jqx.dataAdapter(actListData);

	        // Create a jqxDropDownList
            $("#jqxActList").jqxDropDownList(
            {
              source: adapterActList,
              selectedIndex: 0,
              displayMember: 'activity_short_code',
              valueMember: 'activity_id',
              width: '260',
              height: '20',
              theme: 'classic',
              renderer: function (index, label, value) {
                  var datarecord = adapterActList.records[index];
                  var txt = label+ " " + datarecord.project_leader + " (" + datarecord.capacity + ")";
                  return txt;
              }
            });

			rebindSelectedMembersGrid(adapter2,data2);

            $('#jqxActList').bind('select', function (event)
            {
                var args = event.args;
                var item = $('#jqxActList').jqxDropDownList('getItem', args.index);
            });

            $("#jqxActList").on('change', function (event)
            {
                 rebindSelectedMembersGrid(adapter2,data2);
        	});

    	    $("#submit").jqxButton(
    	    	{ theme: 'classic' }
    	    );

           	$("#submit").on('click', function (event)
            {
           		var item = $("#jqxActList").jqxDropDownList('getSelectedItem');
				updaterows(item.value, $('#jqxgrid2').jqxGrid('getrows'));

        	 });//end submit click

    	    $("#deleterowbutton").bind('click', function () {
    	        var selectedrowindex = $("#jqxgrid2").jqxGrid('getselectedrowindex');
    	        var rowscount = $("#jqxgrid2").jqxGrid('getdatainformation').rowscount;
    	        if (selectedrowindex >= 0 && selectedrowindex < rowscount) {
    	            var id = $("#jqxgrid2").jqxGrid('getrowid', selectedrowindex);
    	            $("#jqxgrid2").jqxGrid('deleterow', id);
    	        }
    	    });

});//end jquery stuff

function rebindSelectedMembersGrid(source,data)
{
   	var item = $("#jqxActList").jqxDropDownList('getSelectedItem');
   	currentActId=item.value;
   	//alert("currACTID after rebind " + currentActId);

   	data.url='stcg-json-responses.php?fct=getJSONSelectedMembersAtActivity&eventId=<?php echo $_SESSION['eventId'] ?>&actId='+currentActId;
	source.dataBind();
}

////////// SAVE TO DB USING AJAX /////////////////////////
function updaterows(currentActId, rowdata){
        // synchronize with the server - send insert command
    	var data = "actId=" + currentActId;

    	for(var i=0;i<rowdata.length;i++)
        {
    		data = data + "&memId[]=" + rowdata[i]['id'];
    	}
        $.ajax({
            dataType: 'json',
            url: 'stcg-json-responses.php?fct=updateSelectedMembersAtActivity',
            data: data,
    		cache: false
    	});
}
</script>
</HEAD>
<BODY>
<div id="content">
<H2>Organize</H2>
	<H3>Volunteers signed up for Event ID #<?php echo $event['id'] . ",&nbsp;&nbsp;&nbsp;" . $event['name']; ?></H3>
	<table style="width: 70%">
		<tr><!-- ONLY ROW OF OUTER TABLE -->

			<td style="vertical-align: top;"><!-- FIRST COLUMN OF OUTER TABLE -->
			<div><b>&nbsp;List of all Signups to all Activities&nbsp;</b><br /><br /></div>
			<!-- START GRID 1 DIV --><DIV style="FLOAT: LEFT;" id = jqxgrid1></DIV><!-- END GRID 1 DIV -->
			<br /><br />
			<input style='margin-top: 10px;' type="button" value="Export to Excel" id='excelExport' /></input>
			</td><!-- END FIRST COLUMN OF OUTER TABLE -->
			<td width="20px"><!-- dummy column --></td>
			<td style="vertical-align: top;"><!-- SECOND COLUMN OF OUTER TABLE -->
			<!--fieldset style = "border: solid #AAAAAA 1px; padding: 20px; padding-top: 20px;">
			<legend>&nbsp;<b>Choose an Activity, then drag a Signup into it&nbsp;</b></legend-->

				<div><b>&nbsp;Assign one or more Signups to each Activity&nbsp;</b><br /><br /></div>
				<div>First, select one Activity from the list of Short Codes below<br /><br /></div>
					<!-- START ACT LIST DIV --><div style="float: LEFT;" id="jqxActList"></div><!-- END ACT LIST DIV -->
					<br/><br/>
					Then, drag a Signup from the list on the left into the grid below.<br/>
					(Click <b>Save</b> before selecting a new Activity!)
					<br/><br/>
					<!-- START GRID 2 DIV --><DIV style="FLOAT: LEFT; " id = jqxgrid2></DIV><!-- END GRID 2 DIV -->
					<div style="margin-top: 10px; float: left;"><BUTTON NAME="SUBMIT" ID ="submit" TYPE="SUBMIT" VALUE="Save">Save</BUTTON></DIV>
					<div style="margin-top: 10px; float: right;"><input id="deleterowbutton" type="button" value="Delete Selected Signup" /></input></DIV>
			</td><!-- END SECOND COLUMN OF OUTER TABLE -->
			<!-- THIRD COLUMN OF OUTER TABLE -->
			<!--div><b>&nbsp;Final list&nbsp;</b><br /><br /></div-->
			<!-- START GRID 3 DIV --><!--DIV id = "jqxgrid3" style="FLOAT: LEFT;" --></DIV><!-- END GRID 3 DIV -->
			<!-- END THIRD COLUMN OF OUTER TABLE -->
		</tr><!-- END ONLY ROW OF OUTER TABLE -->
	</table>
</div>
<?php
	include_once 'footer.php';
?>