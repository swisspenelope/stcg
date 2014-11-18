<?php
require_once 'header.php';
if (isset($_POST['interests']))
{
	$interest = $_POST['interests'];
	//echo $interest;
}
else
	redirect_to("stcg-input-interest-to-find-members.php");
?>
<!-- pre-->
<?php
//print_r($memsWithInterest);
?>
<!--/pre-->
<title>List of Members by Interest</title>
<script type="text/javascript"> 
$(document).ready(function () 
{
	$("#back").click(function () {
        window.location="stcg-input-interest-to-find-members.php";
	});
	
	$("#admin").click(function () 
    {
        window.location="stcg-admin-menu.php";
    });
	
//SOURCE THE GRID CONTAINING ALL SIGNUPS:
    var data1 =
    {
        datatype: "json",
        datafields: [
        { name: 'id'},
		{ name: 'name_first'},
		{ name: 'name_last'},
		{ name: 'email'}
		],
		url: 'stcg-json-responses.php?fct=getJSONMembersByInterest&intId=<?php echo $interest;?>',
        sortcolumn: 'id',
        sortdirection: 'desc',
        async: false
    };//end data source
    
//ASSIGN ADAPTER 1 TO SOURCE DATA
	var adapter1 = new $.jqx.dataAdapter(data1);

	var columns = [
    	  	      { text: 'Id', datafield: 'id', width: 75 },
    	  	      { text: 'First Name', datafield: 'name_first', width: 120 },
    	  	      { text: 'Last Name', datafield: 'name_last', width: 150 },
      	  	      { text: 'Email', datafield: 'email', width: 275 }
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
</head>
<body>
<h3>List of all Members with Interest: <?php echo interestNumToText($interest); ?></h3>
<fieldset style = "border: solid #aaaaaa 1px; width: 570px; padding: 20px; padding-top: 20px;">
<div id = "jqxgrid1" style="float: left;" ></div>	
</fieldset>
<br /><br />
<input type="button" ID="back" value="Back"></input>
<input type="button" ID="admin" value="Admin Menu"></input>
<?php
include_once 'footer.php';
?>