<?php
require_once 'stcg-config.php';
require_once 'stcg-utilities.php';
require_once 'stcg-data-layer.php';
require_once 'scripts/browser.php';

session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta charset="UTF-8" />
<meta name="description" content="stcg web app"/>
<meta name="author" content="Penelope"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>

<link rel="shortcut icon" href="<?php echo DIR_BASE; ?>favicon.ico" /></link>

<!--=== LINK TAGS ===-->
<script src="scripts/jquery-1.10.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/ajaxrequest.js" type="text/javascript"  charset="utf-8"></script>
<script src="scripts/utils.js" type="text/javascript" charset="utf-8"></script>

<!-- =========== CSS ======= -->
<link rel="stylesheet" type="text/css" href="jqwidgets/styles/jqx.base.css" /></link>
<link rel="stylesheet" type="text/css" href="css/stcg-css.css" /></link>
<link rel="stylesheet" type="text/css" href="css/theme.css" /></link>
<link rel="stylesheet" type="text/css" href="css/structure.css" /></link>
<link rel="stylesheet" type="text/css" href="css/form.css" /></link>
<link rel="stylesheet" type="text/css" href="css/themes/blue/style.css" /></link>

<!-- ESSENTIAL JQWIDGETS LIBRARIES -->
<script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="jqwidgets/jqx-all.js"></script>
<script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
<script type="text/javascript" src="jqwidgets/jqxgrid.js"></script>
<script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
<script type="text/javascript" src="jqwidgets/jqxmenu.js"></script>
<script type="text/javascript" src="jqwidgets/jqxdropdownlist.js"></script>
<script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>

<script type="text/javascript" src="jqwidgets/jqxgrid.selection.js"></script>
<script type="text/javascript" src="jqwidgets/jqxgrid.filter.js"></script>
<script type="text/javascript" src="jqwidgets/jqxgrid.sort.js"></script>

<script type="text/javascript" src="jqwidgets/jqxgrid.grouping.js"></script>
<script type="text/javascript" src="jqwidgets/jqxgrid.columnsresize.js"></script>
<script type="text/javascript" src="jqwidgets/jqxgrid.edit.js"></script>

<script type="text/javascript" src="jqwidgets/jqxinput.js"></script>
<script type="text/javascript" src="jqwidgets/jqxnumberinput.js"></script>
<script type="text/javascript" src="jqwidgets/jqxdatetimeinput.js"></script>
<script type="text/javascript" src="jqwidgets/jqxcalendar.js"></script>
<script type="text/javascript" src="jqwidgets/jqxtooltip.js"></script>
<script type="text/javascript" src="jqwidgets/globalization/globalize.js"></script>

<script type="text/javascript" src="jqwidgets/jqxgrid.columnsreorder.js"></script>
<script type="text/javascript" src="jqwidgets/jqxgrid.pager.js"></script>

<script type="text/javascript" src="jqwidgets/jqxgrid.export.js"></script>

<script type="text/javascript" src="jqwidgets/jqxcheckbox.js"></script>
<script type="text/javascript" src="jqwidgets/jqxdragdrop.js"></script>
<script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
<script type="text/javascript" src="jqwidgets/jqxpanel.js"></script>
<script type="text/javascript" src="jqwidgets/jqxexpander.js"></script>
<script type="text/javascript" src="jqwidgets/jqxdata.export.js"></script>

<!--script type="text/javascript" src="widgets/scripts/gettheme.js"></script-->
