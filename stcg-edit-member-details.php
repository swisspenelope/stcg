<?php

/**
 * Depends on: stcg-config.php, stcg-admin-access.php, stcg-vol1-login.php,
 * stcg-vol2-authenticated.php, stcg-json-responses.php, stcg-data-layer.php,
 * stcg-utilities.php, scripts/utils.js
 * 
 *  */
require_once 'header.php';

if (!$_POST['lastName']) 
{
    header("location: stcg-admin-access.php");
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
        window.location="stcg-vol2-authenticated.php";
    });
        
    $("#logout").click(function ()
    {
        $.ajax({
        type: "GET",
        url: "stcg-json-responses.php?fct=endSession",
        //data: dataString,
        success: function(response)
        {
            //alert("session destroyed on exit");
            top.location.href="http://www.servethecitygeneva.ch";
        }
        });
    });
 
    //update initializer
    $("#reply").css("font-weight", "bold");
    $("#reply").css("font-size", "20px");
    $('#reply').text('');
       
        var paramString = "&nameLast=" + "<?php echo $_SESSION['last']?>" + "&nameFirst=" + "<?php echo $_SESSION['first']?>"; 
    var data1 =
    {
        datatype: "json",
        datafields: [
            { name: 'name_last'},
            { name: 'name_first'},
            { name: 'organization'},
            { name: 'email'},
            { name: 'phone'},
            { name: 'source'},
            { name: 'comments'},
            { name: 'location_id'},
            { name: 'language_id'},
            { name: 'id'}
        ],
        url: 'stcg-json-responses.php?fct=getJSONSelectedMembers' + paramString,
        sortcolumn: 'id',
        sortdirection: 'desc',
        async: false,
        
        ////////////// THIS IS THE UPDATE / ADD ROW THAT HAS TO BE CALLED /////////
        updaterow: function (rowid, rowdata, commit) 
        {
                var data = "name_last=" + rowdata.name_last + "&name_first=" + rowdata.name_first + "&organization=" + rowdata.organization + "&email=" + rowdata.email + "&phone=" + rowdata.phone + "&source=" + rowdata.source + "&comments=" + rowdata.comments + "&location_id=" + rowdata.location_id + "&language_id=" + rowdata.language_id;        
               
               $.ajax({
               dataType: 'json',
               url: 'stcg-json-responses.php?fct=updateThisMemberDetails&id=' + rowdata.id,
               data: data,
               cache: false,
               success: myCallback,
               error: myCallbackError
               });
            }
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
    { text: 'Source', datafield: 'source', width: 200 },
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
        editable: true,
        //autorowheight: true,
        autoheight: true,
        columns: columns
    });

    function myCallback(response)
    {
    //ajax call returns one row updated

        if (response > 0)
        {
            $('#jqxgrid1').jqxGrid('updatebounddata');
            $("#reply").css("color", "green");
            $('#reply').text('Update accepted!');
        }
        else 
        {
            $('#jqxgrid1').jqxGrid('updatebounddata');
            $("#reply").css("color", "red");
            $('#reply').text('This update to the database was not allowed!');
        }
    }
    function myCallbackError(jqXHR, textStatus, errorThrown )//ajax call returns any kind of error
    {
         alert("Could not update this grid - " + errorThrown + ", status: " + textStatus);
    }
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
<h3>Members</h3>
<input type="button" id="admin" value="Back to Change Another Member">
<input type="button" id="logout" value="Log Out">

<fieldset style = "border: solid #aaaaaa 1px; width: 90%; padding: 20px; padding-top: 20px;">
<div style="float: left;" id = "jqxgrid1">
</div>
</fieldset>
<div style="padding-top: 20px;" id="reply">&nbsp;</div>
</body>
<?php
    include_once 'footer.php';
?>