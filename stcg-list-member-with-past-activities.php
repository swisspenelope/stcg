<?php
require_once 'header.php';

if (!$_POST['lastName']) 
{
	header("Location: stcg-input-member-to-find-history.php");
	exit;
}
else
{
	$_SESSION['last'] = $_POST['lastName'];
	if (isset ($_POST['firstName']))
            $_SESSION['first'] = $_POST['firstName'];
}
?>
<title>Member Activity History</title>
<script type="text/javascript"> 
$(document).ready(function () 
{
    $("#back").click(function () 
    {
        window.location="stcg-input-member-to-find-history.php";
    });
	
    $("#admin").click(function () 
    {
        window.location="stcg-admin-menu.php";
    });
    
    var paramString = "&nameLast=" + "<?php echo $_SESSION['last']?>" + "&nameFirst=" + "<?php echo $_SESSION['first']?>"; 
    var data =
    {
        datatype: "json",
        datafields: [
        { name: 'name_last'},
        { name: 'name_first'},
        { name: 'activity_id'},
        { name: 'activity_name'},
        { name: 'activity_short_code'}
        ],
        url: 'stcg-json-responses.php?fct=getJSONMembersAndPastActivities' + paramString,
        sortcolumn: 'name_last',
        sortdirection: 'asc',
        async: false
     }//end data source
     
     var adapter = new $.jqx.dataAdapter(data);
     
     var columns = [
        { text: 'Last Name', datafield: 'name_last', width: 200 },
        { text: 'First Name', datafield: 'name_first', width: 110 },
        { text: 'Act. #', datafield: 'activity_id', width: 50 },
        { text: 'Activity', datafield: 'activity_name', width: 400 },
        { text: 'Short Code', datafield: 'activity_short_code', width: 90 }
     ];
     
     //INITIALIZE GRID
    $("#jqxgrid").jqxGrid(
    {
        width: 850,
        //height: 800,
        source: adapter,
        sortable: true,
        theme: 'classic',
        selectionmode: 'singlerow',
        editable: true,
        //autorowheight: true,
        autoheight: true,
        columns: columns
    });
});
</script>
</head>
<body>
<h2>Member's past activities</h2>
<fieldset style = "border: solid #aaaaaa 1px; width: 90%; padding: 20px; padding-top: 20px;">
<div style="float: left;" id = "jqxgrid"></div>
</fieldset>
<input type="button" id="back" value="Back to Member Search">
<input type="button" id="admin" value="Back to Admin Menu">
</body>
<?php
include_once 'footer.php';
?>