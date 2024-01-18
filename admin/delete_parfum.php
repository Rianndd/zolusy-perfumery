<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

mysqli_query($db,"DELETE FROM parfum WHERE d_id = '".$_GET['parfum_del']."'");
header("location:all_parfum.php");  

?>
