<?php
require_once 'header.php';

if (!$_POST['lastName']) 
{
    header("location: stcg-input-member-to-find-details.php");
    exit;
}
else
{
    $_SESSION['last'] = $_POST['lastName'];
    
    if (isset ($_POST['firstName']))
    {
        $_SESSION['first'] = $_POST['firstName'];
    }
}
?>
<title>Member Details</title>
<script type="text/javascript"> 
$(document).ready(function () 
{
    $("#admin").click(function () 
    {
        window.location="stcg-admin-menu.php";
    });
    
    var paramString = "&nameFirst=" + "<?php echo $_SESSION['first']?>" + "&nameLast=" + "<?php echo $_SESSION['last']?>";
    var data1 =
    {
        datatype: "json",
        datafields: [
        { name: 'id'},
            { name: 'name_first'},
            { name: 'name_last'},
            { name: 'organization'},
            { name: 'email'},
            { name: 'phone'},
            { name: 'comments'},
            { name: 'language_id'},
            { name: 'location_id'}
        ],
        url: 'stcg-json-responses.php?fct=getJSONSelectedMembers' + paramString,
        sortcolumn: 'id',
        sortdirection: 'desc',
        async: false
    };//end data source
    
    
//ASSIGN ADAPTER 1 TO SOURCE DATA
    var adapter1 = new $.jqx.dataAdapter(data1);
    
    var columns = [
    { text: 'Id', datafield: 'id', width: 50 },
    { text: 'First Name', datafield: 'name_first', width: 90 },
    { text: 'Last Name', datafield: 'name_last', width: 110 },
    { text: 'Organization', datafield: 'organization', width: 120 },
    { text: 'Email', datafield: 'email', width: 200 },
    { text: 'Phone', datafield: 'phone', width: 120 },
    { text: 'Comments', datafield: 'comments', width: 300 },
 /*   { text: 'Language', datafield: 'language_id', width: 160},
    { text: 'Location', datafield: 'location_id', width: 160}*/
    { text: 'Language', datafield: 'language_id', width: 160, cellsrenderer: function(row, columnfield, value, defaulthtml, columnproperties, rowdata)
        {
            return langNumToText(value);
        }
    },
    { text: 'Location', datafield: 'location_id', width: 160, cellsrenderer: function(row, columnfield, value, defaulthtml, columnproperties, rowdata)
        {
            return locationNumToText(value);
        }
    }
    ];

//INITIALIZE GRID 1
    $("#jqxgrid1").jqxGrid(
    {
        width: 1300,
        //height: 800,
        source: adapter1,
        sortable: true,
        theme: 'classic',
        selectionmode: 'singlerow',
        editable: false,
        //autorowheight: true,
        autoheight: true,
        columns: columns
    });
    
//EVENT HANDLERS
    $('#jqxgrid1').on('rowselect', function(event)
    {
///////DO NOT DELETE! IT'S THE ONLY COMBO THAT WORKS!!!/////////////
        var row = $("#jqxgrid1").jqxGrid('getrowdata', event.args.rowindex);
     });    
});//end document ready

</script>
</head>
<body>
<h2>Members</h2>
<p>Searching for: <?php echo $_SESSION['first'] . " " . $_SESSION['last']?></p>
<input type="button" id="admin" value="Back to Admin Menu">
<fieldset style = "border: solid #aaaaaa 1px; width: 90%; padding: 20px; padding-top: 20px;">
<div style="float: left;" id = "jqxgrid1">
</div>	
</fieldset>
</body>
<?php
    include_once 'footer.php';
?>