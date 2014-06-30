<?php
include_once 'header.php';
$today = date("d M Y");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<script type="text/javascript"> 
$(document).ready(function () 
{
	$("#admin").click(function () {
        window.location="/stcg/site/stcg-admin-menu.php";
    });
});
</script>
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
		{ name: 'organization'},
		{ name: 'email'},
		{ name: 'phone', type: 'string'},
		{ name: 'comments'}
		],
        url: 'stcg-json-responses.php?fct=getJSONAllMembers',
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
                      { text: 'Comments', datafield: 'comments', width: 600 }
    	  	      ];

    //INITIALIZE GRID 1
    	    $("#jqxgrid1").jqxGrid(
    	    {
    	    	width: 1300,
    	        height: 800,
    	        source: adapter1,
    	        sortable: true,
    	        theme: 'classic',
    	        selectionmode: 'singlerow',
    	        editable: true,
    	        columns: columns
    	    });//end data source
            $("#excelExport").jqxButton(
                {
                    theme: 'energyblue'
                });

        $("#excelExport").click(function()
        {
            $("#jqxgrid1").jqxGrid('exportdata', 'xls', 'jqxGrid');
        });
});//end document ready    
</script>
</head>
<body>
<h3>List of all Members <?php echo $today; ?></h3>
<p>(Hover your mouse pointer over a column heading, then click the arrow to sort in order of that column.)</p>
<input type="button" id="admin" value="Back to Admin Menu"></input>
<input style='margin-top: 10px; margin-bottom: 10px;' type="button" value="Export to Excel" id='excelExport' />
<fieldset style = "border: solid #aaaaaa 1px; width: 90%; padding: 20px; padding-top: 20px;">
    <div style="float: left;" id = jqxgrid1></div>	
</fieldset>
</body>
</html>