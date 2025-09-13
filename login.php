<?php
session_start();

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') header("Location: admin.php");
    elseif ($_SESSION['role'] === 'user') header("Location: user.php");
    exit;
}

if(isset($_GET['action']) && $_GET['action'] === 'new_captcha'){
    $chars='ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $captcha_text=substr(str_shuffle($chars),0,5);
    $_SESSION['captcha']=$captcha_text;
    echo $captcha_text;
    exit;
}

if(!isset($_SESSION['captcha'])) {
    $chars='ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $_SESSION['captcha'] = substr(str_shuffle($chars),0,5);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Page</title>
<style>
body {
    margin:0;
    padding:0;
    height:100vh;
    background:url("backgroundLogin.gif") no-repeat center center fixed;
    background-size:cover;
    font-family:Arial,sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
}

.top-bar {
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:60px;
    background:rgba(255,255,255,0.85);
    display:flex;
    align-items:center;
    padding:0 20px;
    z-index:1000;
}
.top-bar img {
    height:90px;
    object-fit:contain;
}

.login-container {
    width:350px;
    padding:25px 30px;
    background:rgba(255,255,255,0.9);
    border-radius:15px;
    box-shadow:0px 6px 18px rgba(0,0,0,0.3);
    text-align:center;
    margin-top:80px;
}

.login-container img {
    width:160px;
    margin-bottom:-50px;
}

.login-container h2 {
    margin-bottom:15px;
}

.login-container input[type="text"],
.login-container input[type="password"] {
    width:100%;
    padding:8px 36px 8px 12px;
    margin:8px 0;
    border:1px solid #ccc;
    border-radius:6px;
    font-size:14px;
    box-sizing:border-box;
}

.password-box {
    position:relative;
    width:100%;
}

.toggle-btn {
    position:absolute;
    right:10px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:16px;
    color:gray;
    opacity:0.8;
    user-select:none;
}

.login-container button[type="submit"] {
    width:100%;
    padding:12px;
    margin-top:12px;
    background:#dff3f9;
    color:#333;
    border:none;
    border-radius:25px;
    cursor:pointer;
    font-size:15px;
    font-weight:bold;
    box-shadow:0 4px 8px rgba(0,0,0,0.2);
    transition:all 0.3s ease;
}
.login-container button[type="submit"]:hover {
    transform:scale(1.05);
    background:#c6e9f2;
    box-shadow:0 8px 16px rgba(0,0,0,0.3);
}
.login-container button[type="submit"]:active { transform:scale(0.95); }

.register-text {
    margin-top:15px;
    font-size:14px;
    color:#333;
}
.register-text span {
    color:#007BFF;
    cursor:not-allowed;
}
.register-text span:hover {
    text-decoration:underline;
    cursor:pointer;
}

.error {
    font-size:14px;
    margin-bottom:10px;
    display:none;
    color:red;
}

.captcha-box {
    margin-top:8px;
    display:flex;
    align-items:center;
    justify-content:center;
}
.captcha-text {
    padding:5px 8px;
    background:#eee;
    border-radius:5px;
    font-weight:bold;
    letter-spacing:2px;
    margin-right:5px;
    user-select:none;
}
.captcha-box input {
    width:80px;
    padding:5px;
    border-radius:5px;
    border:1px solid #ccc;
}
.captcha-box button {
    padding:5px 8px;
    font-size:12px;
    border-radius:5px;
    cursor:pointer;
    margin-left:5px;
}
</style>
</head>
<body>

<div class="top-bar">
    <img src="logoBar.png" alt="Logo Bar">
</div>

<div class="login-container">
    <img src="logo.png" alt="Logo">
    <h2>Login</h2>
    <p class="error" id="errorMsg"></p>

    <form id="loginForm" method="POST" action="auth.php">
        <input type="text" name="username" placeholder="Masukkan username" required>
        <div class="password-box">
            <input type="password" name="password" id="password" placeholder="Masukkan password" required>
            <span class="toggle-btn" onclick="togglePassword()">👁</span>
        </div>

        <div class="captcha-box">
            <div class="captcha-text" id="captchaDisplay"><?php echo $_SESSION['captcha']; ?></div>
            <input type="text" name="captcha_input" placeholder="Kode" required>
            <button type="button" onclick="reloadCaptcha()">🔄</button>
        </div>

        <button type="submit">Login</button>
    </form>

    <p class="register-text">Belum punya akun? <span>Daftar baru</span></p>
</div>

<script>
function togglePassword(){
    let pass=document.getElementById("password");
    pass.type = pass.type==='password'?'text':'password';
}

function reloadCaptcha(){
    fetch('login.php?action=new_captcha')
    .then(res => res.text())
    .then(txt => document.getElementById('captchaDisplay').textContent = txt);
}

document.getElementById("loginForm").addEventListener("submit", async function(e){
    e.preventDefault();
    const formData=new FormData(this);
    const res=await fetch('auth.php',{method:'POST',body: formData});
    const data=await res.json();
    const errorBox=document.getElementById('errorMsg');
    if(data.status==='success'){
        window.location.href=data.redirect;
    }else{
        errorBox.style.display='block';
        errorBox.textContent=data.msg;
        reloadCaptcha();
    }
});
</script>
</body>
</html>