<?php
require_once 'stcg-config.php';
require_once 'stcg-utilities.php';

if (isset($_GET['msg'])){
    echo alertMessage($_GET['msg'], $_GET['severity']);
}
?>