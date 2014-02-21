<?php
session_start();
$_SESSION['user']="test";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta charset="UTF-8" />
</head>
<body>
<form action="test2.php">
<button type="submit">new2</button>
<button type="submit">go</button>
</form>
</body>