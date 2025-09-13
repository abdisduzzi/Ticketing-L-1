<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['user'] ?? $_SESSION['username'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>User Dashboard</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
:root{
    --bar-height:60px;
    --slider-height:420px;
    --primary:#dff3f9; 
    --search-bar-height:60px;
    --sidebar-width:250px; 
    --category-bar-height:40px; 
}
html, body{
    margin:0; padding:0; font-family:Arial, sans-serif; background:#f7f7f7;
}

.top-bar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 60px;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    padding: 0 20px;
    z-index: 1000;
    box-sizing: border-box; 
}

.top-bar .logo-bar {
    height: 90px;
    max-height: 90px;
    object-fit: contain;
    position: relative;
}

.top-bar .user-info {
    display: flex;
    align-items: center;
    margin-left: auto; 
}

.top-bar .user-info .profile-pic {
    width: 30px;
    height: 30px;
    border-radius: 50%; 
    object-fit: cover; 
    margin-right: 5px; 
}

.top-bar .user-info .username {
    font-size: 14px;
    color: #333;
    font-weight: 600;
}

.top-bar .logout {
    text-decoration: none;
    color: #333;
    font-weight: 600;
    background: var(--primary);
    padding: 8px 12px;
    border-radius: 16px;
    margin-left: 10px;
}

.top-bar .logout:hover {
    filter: brightness(.95);
}

.slider{
    position:relative;
    margin-top: var(--bar-height); 
    width:100%;
    height: var(--slider-height);
    overflow:hidden;
    background:#000;
}
.slider img{
    position:absolute;
    top:0; left:0;
    width:100%;
    height:100%;
    object-fit:cover;
    opacity:0;
    transition:opacity .6s ease;
}
.slider img.active{opacity:1;}
.slider .nav{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    background:rgba(0,0,0,.35);
    color:#fff;
    border:none;
    padding:10px 14px;
    border-radius:50%;
    font-size:22px;
    cursor:pointer;
    user-select:none;
}
.slider .nav:hover{background:rgba(0,0,0,.55);}
.slider .prev{left:18px;}
.slider .next{right:18px;}

.search-bar{
    position:relative;
    background:#8bdcf4;
    width:100%;
    height: var(--search-bar-height);
    display:flex;
    justify-content:center; 
    align-items:center; 
    padding:0 20px;
    box-sizing:border-box;
}
.search-bar input {
    width: 70%; 
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
}
.search-bar button {
    background: var(--primary);
    border: none;
    padding: 8px 16px;
    margin-left: 10px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
}
.search-bar button:hover{filter:brightness(.95);}

.category-bar {
    background-color: #ffffff;
    width: var(--sidebar-width); 
    height: var(--category-bar-height);
    display: flex;
    align-items: center;
    padding-left: 20px;
    font-weight: 600;
    color: #333;
    box-sizing: border-box;
    border-bottom: 2px solid #ddd; 
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
}

.sidebar {
    position: absolute; 
    top: var(--search-bar-height) + var(--category-bar-height); 
    left: 0;
    width: var(--sidebar-width); 
    min-height: 100vh; 
    background-color: #ffffff; 
    box-shadow: 2px 0px 10px rgba(0, 0, 0, 0.1); 
    padding: 20px;
    box-sizing: border-box;
    overflow-y: auto; 
}

.sidebar .category-item {
    margin-bottom: 20px;  
    font-size: 18px;  
    font-weight: 700;    
    color: #6DA9C9; 
    cursor: pointer;
    line-height: 1.5;  
    transition: all 0.3s ease;
}

.sidebar .category-item:hover {
    color: #000000; 
}

.sidebar .category-item:active {
    color: #6DA9C9; 
}

.sidebar .category-item.button {
    background-color: #6DA9C9;
    padding: 12px 20px; 
    border-radius: 8px;
    font-weight: 600;  
    text-align: center;
    display: block;
    width: 100%;  
}

.sidebar .category-item.button:hover {
    background-color: #c6e9f2; 
    color: #333;
}

.content {
    padding: 24px;
    max-width: 1200px;
    margin: 18px auto;
    margin-left: calc(var(--sidebar-width) + 20px);
}

@media(max-width:800px){
    :root{--slider-height:240px;}
    .slider .nav{font-size:18px;padding:8px 10px;}
    .sidebar{
        width: 200px;
    }
    .content{
        margin-left: 0;
    }
}
.pagination-dummy {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin: 20px 0 30px 0;
    font-size: 18px;
    font-weight: bold;
    color: #555;
}

.pagination-dummy .page-number,
.pagination-dummy .page-arrow {
    cursor: pointer;
    padding: 6px 12px;
    border-radius: 6px;
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: background-color 0.2s;
}

.pagination-dummy .page-number:hover,
.pagination-dummy .page-arrow:hover {
    background-color: #e0f2f7;
}

.pagination-dummy .active {
    background-color: #6DA9C9;
    color: #fff;
}
</style>
</head>
<body>

<div class="top-bar">
    <img src="logoBar.png" alt="Logo" class="logo-bar">
    <div class="user-info">
        <img src="pfp.png" alt="Profile Picture" class="profile-pic">
        <span class="username"><?php echo htmlspecialchars($username); ?></span>
    </div>
    <a class="logout" href="logout.php">Logout</a>
</div>

<div class="slider" id="slider">
    <img src="headerEvent1.png" alt="Event 1" class="active">
    <img src="headerEvent2.png" alt="Event 2">
    <img src="headerEvent3.png" alt="Event 3">
    <img src="headerEvent4a.png" alt="Event 4">
    <img src="headerEvent5.png" alt="Event 5">
    <button class="nav prev" id="prevBtn">&#10094;</button>
    <button class="nav next" id="nextBtn">&#10095;</button>
</div>

<div class="search-bar">
    <input type="text" placeholder="Cari event..." />
    <button type="button">Search</button>
</div>

<div class="category-bar">
    Event Category
</div>

<div class="sidebar">
    <div class="category-item">
        Sports
    </div>
    <div class="category-item">
        Entertainment
    </div>
    <div class="category-item">
        Seminars
    </div>
    <div class="category-item">
        Business
    </div>
    <div class="category-item">
        Travel
    </div>
    <div class="category-item">
        Others
    </div>
</div>

<div style="text-align: center; padding: 20px;">
    <img src="logoBar.png" alt="Logo" style="vertical-align: middle; width: 100px; margin-top: -35px; margin-right: 10px;">
    <br>
    <span style="font-size: 18px; color: #333;">
        My Ticket adalah platform ticketing event yang membantu anda untuk membeli ticket, segera check out ticket di
        event kesukaan anda sebelum kehabisan ya.
    </span>
</div>

<div class="ticket-section">
    <div class="row">
        <div class="ticket-box">
            <img src="event1.jpg" alt="Ticket 1">
            <h3><b>Seminar Nasional Ekonomi</b></h3>
            <p>Start: 8:00 AM</p>
            <p>From: IDR 25.000</p>
        </div>
        <div class="ticket-box">
            <img src="event2.jpg" alt="Ticket 2">
            <h3><b>Drake Concert BJM</b></h3>
            <p>Start: 9:00 PM</p>
            <p>From: IDR 1.000.000</p>
        </div>
        <div class="ticket-box">
            <img src="event3.jpg" alt="Ticket 3">
            <h3><b>Chaeyoung Concert</b></h3>
            <p>Start: 7:00 PM</p>
            <p>From: IDR 500.000</p>
        </div>
    </div>
    <div class="row">
        <div class="ticket-box">
            <img src="event4.jpg" alt="Ticket 4">
            <h3><b>Hindia Solo Concert</b></h3>
            <p>Start: 8:00 PM</p>
            <p>From: IDR 200.000</p>
        </div>
        <div class="ticket-box">
            <img src="event5.jpg" alt="Ticket 5">
            <h3><b>BMTH: Last Dance</b></h3>
            <p>Start: 10:00 PM</p>
            <p>From: IDR 2.500.000</p>
        </div>
        <div class="ticket-box">
            <img src="event6.jpg" alt="Ticket 6">
            <h3><b>Let's Run: City Run BJM</b></h3>
            <p>Start: 6:00 AM</p>
            <p>From: IDR 75.000</p>
        </div>
    </div>
</div>

<div class="pagination-dummy">
    <span class="page-arrow">&lt;</span>
    <span class="page-number active">1</span>
    <span class="page-number">2</span>
    <span class="page-number">3</span>
    <span class="page-number">4</span>
    <span class="page-number">5</span>
    <span class="page-arrow">&gt;</span>
</div>


<style>
.ticket-section {
    padding: 20px;
    text-align: center;
}

.row {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
    margin-right: 350px;
}

.ticket-box {
    width: 20%;  
    height: 250px;
    padding: 10px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
    text-align: center;
    overflow: hidden; 
    margin-right: 30px; 
}

.ticket-box:hover {
    transform: scale(1.05);
}

.ticket-box img {
    width: 100%;
    height: 60%;
    object-fit: cover;
    border-radius: 10px;
}

.ticket-box h3 {
    margin-top: 10px;
    font-size: 18px;
    color: #333;
}

.ticket-box p {
    margin-top: -8px;
    font-size: 14px;
    color: #777;
}
</style>

<script>
(function(){
    const slides=[...document.querySelectorAll('.slider img')];
    let current=0, interval=null, revert=null;
    const AUTO=5000, MANUAL=10000;
    const show=i=>{ 
        current=(i+slides.length)%slides.length;
        slides.forEach((s,idx)=>s.classList.toggle('active',idx===current));
    };
    const next=()=>show(current+1), prev=()=>show(current-1);
    function startAuto(ms=AUTO){ stopAuto(); interval=setInterval(next,ms); }
    function stopAuto(){ if(interval){ clearInterval(interval); interval=null; } }
    function manual(){
        stopAuto();
        if(revert){ clearTimeout(revert); revert=null; }
        interval=setInterval(next,MANUAL);
        revert=setTimeout(()=>{ stopAuto(); startAuto(); }, MANUAL);
    }
    document.getElementById('prevBtn').addEventListener('click',()=>{prev();manual();});
    document.getElementById('nextBtn').addEventListener('click',()=>{next();manual();});
    document.addEventListener('keydown',e=>{
        if(e.key==='ArrowLeft'){prev();manual();}
        if(e.key==='ArrowRight'){next();manual();}
    });
    show(0);
    startAuto();
})();
</script>
</body>
</html>