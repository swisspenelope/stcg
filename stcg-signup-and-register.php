<?php
include_once 'header.php';

/* 
 * load latest event activities, with checkboxes
 * load reg form and validation
 * save member event and member activity
 *
 */
$connectionObject = connect();
$event = getLatestEvent($connectionObject);
$_SESSION['eventId'] = $event['id'];
?>
<head> 
<title>
Signup and register for new members
</title>
<script type="text/javascript">
    
//build array of selected activities
var selectedRows = new Array();

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
            {name: 'date'},
            {name: 'open'}
        ],
        url: 'stcg-json-responses.php?fct=getJSONAllActivitiesAtEvent&eventId=<?php echo $_SESSION['eventId'] ?>',
        sortcolumn: 'activity_id',//note rowid order follows activity_id!
        sortdirection: 'asc',
        async: false
    };//end data source
//create the dataAdapter for the Grid
    var adapter = new $.jqx.dataAdapter(data);

 // initialize jqxGrid
    $("#jqxgrid").jqxGrid(
    {
        width: 1200,
        source: data,
        sortable: true,
        pageable: false,
        editable: false,
        selectionmode: 'checkbox',
        autoheight: true,
        columns:  
        [
            {text: '#', datafield: 'activity_id', width: 40},
            {text: 'Act. Name', datafield: 'activity_name', width: 250},
            {text: 'Act. Desc', datafield: 'activity_desc', width: 450},
            {text: 'Code', datafield: 'activity_short_code', width: 60},
            {text: 'Date', datafield: 'date', width: 90},
            {text: 'Status', datafield: 'open', width: 90}
         ]  
    });
        
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
    $("#Register").jqxButton({ theme: 'classic' });

    //button handlers  
    $("#Register").click(function () 
    {  
        //alert ("on register button, print array " + selectedRows);    
        window.location.href="stcg-add-new-member.php?acts=" + selectedRows;
    });
});
</script>
</head>
<body>
     <h2>Sign up for this Event: <?php echo $event['name'] ?></h2>
     <h3>You may choose several mutually exclusive activities (that are on the same day at the same time).</h3>
     <p>We will put you wherever the need is greatest. However, if you have a strong preference for one activity but don't mind doing others, check all you are interested in, and name your preferred activity in the Comments box. We will do our best to accommodate your preference!</p>
     
    <div id='jqxWidget'>
        <div id='jqxgrid' style="margin-bottom: 20px;"></div>
        <div><input type="button" id="Register" value="Register" /></div>    
    </div>
    
<?php
//include_once 'stcg-add-new-member.php';
?>
</body>
</html>
