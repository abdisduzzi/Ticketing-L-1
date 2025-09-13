<?php
session_start();
header('Content-Type: application/json');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$captcha_input = $_POST['captcha_input'] ?? '';

if (!isset($_SESSION['captcha']) || strtoupper($captcha_input) !== $_SESSION['captcha']) {
    echo json_encode(["status"=>"error","msg"=>"Captcha salah!"]);
    exit;
}
unset($_SESSION['captcha']); 

if ($username === "admin") {
    if ($password === "admin") $redirect = "admin.php";
    else { echo json_encode(["status"=>"error","msg"=>"Password salah!"]); exit; }
} elseif ($username === "user") {
    if ($password === "user") $redirect = "user.php";
    else { echo json_encode(["status"=>"error","msg"=>"Password salah!"]); exit; }
} else {
    echo json_encode(["status"=>"error","msg"=>"Username tidak ditemukan!"]);
    exit;
}

$_SESSION['user'] = $username;
$_SESSION['role'] = ($username === "admin") ? "admin" : "user";
echo json_encode(["status"=>"success","redirect"=>$redirect]);