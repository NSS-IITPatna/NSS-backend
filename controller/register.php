<?php
session_start();
header('Content-Type: application/json');	

require_once('model/user.php');
require_once('dbConfig.php');

$user = new User($mysqli);

$details = array();

$details['name'] = $_POST['name'];
$details['username'] = $_POST['username'];
$details['email'] = $_POST['email'];
$details['cell'] = $_POST['cell'];
$password = $_POST['password'];

$details['access_level'] = isset($_POST['access_level'])? (int)$_POST['access_level']:0;

$user->setAll($details);

$ret = $user->registerUser($password);

echo json_encode($ret, JSON_PRETTY_PRINT);

?>