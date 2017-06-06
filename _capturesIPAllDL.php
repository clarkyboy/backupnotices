<?php
session_start();
header('Location:  downloads/'.$_SESSION['data_cip_name']);
?>