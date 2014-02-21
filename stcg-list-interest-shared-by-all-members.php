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
<HTML>
<HEAD>
<TITLE>List of Members by Interest</TITLE>
<script type="text/javascript"> 
$(document).ready(function () 
{
	$("#back").click(function () {
        window.location="stcg-input-interest-to-find-members.php";
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
</HEAD>
<BODY>
<H3>List of all Members with Interest: <?php echo interestNumToText($interest); ?></H3>
<fieldset style = "border: solid #AAAAAA 1px; width: 570px; padding: 20px; padding-top: 20px;">
<DIV id = "jqxgrid1" style="FLOAT: LEFT;" ></DIV>	
</fieldset>
<BR /><BR />
<INPUT TYPE="BUTTON" ID="back" VALUE="Back"></INPUT>
</BODY>
</HTML>