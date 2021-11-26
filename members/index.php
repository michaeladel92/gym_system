<?php
ob_start();
session_start();
require_once('../inc/connection.php');
require_once('../inc/healpers.php');
if(isset($_SESSION['id'])){
  unset($_SESSION['id']);
  unset($_SESSION['full_name']);
  unset($_SESSION['agent_code']);
  unset($_SESSION['email']);
  unset($_SESSION['status']);
  unset($_SESSION['is_approved']);
  unset($_SESSION['role_id']);
  setMessage('Access Denied!','danger');
  redirectHeader('../login.php');
}else{
  setMessage('Access Denied!','danger');
  redirectHeader('../login.php');
}