<?php
date_default_timezone_set('Africa/Cairo');
ob_start();
session_start();
require_once('connection.php');
require_once('healpers.php');
if(isset($_GET['logout']) && $_GET['logout'] === 'true'  ){
  if(isset($_SESSION['id'])){
    unset($_SESSION['id']);
    unset($_SESSION['full_name']);
    unset($_SESSION['agent_code']);
    unset($_SESSION['email']);
    unset($_SESSION['status']);
    unset($_SESSION['is_approved']);
    unset($_SESSION['role_id']);
    setMessage('Logged Out!','success');
    $host = $_SERVER['HTTP_HOST'];
    $path = "http://$host/gym/login.php";
    redirectHeader($path);
  }
}
require_once('header.php');
