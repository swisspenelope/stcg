<?php
include_once("header.php");
$login='<div id="login">';

echo "session user is " . $_SESSION['user'];

$_SESSION['user'] = $userId;
if (isset($_SESSION['user'])) 
{
   // logged in
   $login=$_SESSION['user'] . '<a href="">logout</a>';
} 
else 
{
   // not logged in
}

$login='</div>';


include_once("footer.php");
?>
