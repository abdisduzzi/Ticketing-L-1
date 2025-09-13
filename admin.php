<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['user'] ?? $_SESSION['username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
    :root {
        --bar-height: 60px;
        --slider-height: 420px;
        --primary: #dff3f9;
        --search-bar-height: 60px;
        --sidebar-width: 250px;
        --category-bar-height: 40px;
    }

    /* RESET */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    html, body {
        font-family: Arial, sans-serif;
        background: #f7f7f7;
        min-height: 100%;
        width: 100%;
    }

    .top-bar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: var(--bar-height);
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        padding: 0 20px;
        z-index: 1000;
    }
    .top-bar .logo-bar {
        height: 90px;
        max-height: 90px;
        object-fit: contain;
    }
    .top-bar .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-left: auto;
        margin-right: 10px;
    }
    .top-bar .user-info .profile-pic {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
    }
    .top-bar .user-info .username {
        font-size: 14px;
        color: #333;
        font-weight: 600;
        white-space: nowrap;
    }
    .top-bar .logout {
        text-decoration: none;
        color: #333;
        font-weight: 600;
        background: var(--primary);
        padding: 8px 12px;
        border-radius: 16px;
        white-space: nowrap;
    }
    .top-bar .logout:hover {
        filter: brightness(.95);
    }

    .slider {
        position: relative;
        margin-top: var(--bar-height);
        width: 100%;
        height: var(--slider-height);
        overflow: hidden;
        background: #000;
    }
    .slider img {
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 0.6s ease;
    }
    .slider img.active {
        opacity: 1;
    }
    .slider .nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.35);
        color: #fff;
        border: none;
        padding: 10px 14px;
        border-radius: 50%;
        font-size: 22px;
        cursor: pointer;
    }
    .slider .prev { left: 18px; }
    .slider .next { right: 18px; }

    .admin-form {
        background: #e8f9ff;
        padding: 20px;
        border-radius: 8px;
        margin-top: 5px;
        margin-bottom: 0px; /* dikurangi */
    }

    .search-bar {
        background: #8bdcf4;
        width: 100%;
        height: var(--search-bar-height);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0 20px;
        margin-top: 0px; /* hilangkan space atasnya */
        margin-bottom: 0px; /* hilangkan space bawahnya */
    }
    .search-bar input {
        width: 70%;
        height: 38px;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 14px;
    }
    .search-bar button {
    padding: 8px 16px;
    margin-left: 10px;
    margin-top: -2px; /* ini untuk menaikkan tombol */
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    background: var(--primary);
    border: 1px solid #ccc;
    cursor: pointer;
}

    .sidebar-wrapper {
        float: left;
        min-height: 100%;
    }

    .category-bar {
        background-color: #ffffff;
        width: var(--sidebar-width);
        height: var(--category-bar-height);
        display: flex;
        align-items: center;
        padding-left: 20px;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #ddd;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        box-sizing: border-box;
        margin-top: 0px; /* hilangkan space atasnya */
        margin-bottom: 0px; /* hilangkan space bawah jika ada */
    }

    .sidebar {
        width: var(--sidebar-width);
        background-color: #ffffff;
        box-shadow: 2px 0px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        box-sizing: border-box;
        overflow-y: auto;
        min-height: calc(100vh); /* minimal layar penuh */
        height: auto;
    }
    .sidebar .category-item {
        margin-bottom: 20px;
        font-size: 18px;
        font-weight: 700;
        color: #6DA9C9;
        cursor: pointer;
    }
    .sidebar .category-item:hover {
        color: #000000;
    }

    .content {
        padding: 24px;
        margin-left: var(--sidebar-width);
    }

    /* DESKRIPSI PLATFORM */
    .editable-text {
        background: #e8f9ff;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .ticket-section {
        padding: 20px;
        text-align: center;
    }
    .row {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 30px;
        margin-bottom: 20px;
    }
    .ticket-box {
        width: 30%;
        height: 320px;
        padding: 15px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        position: relative;
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
        word-wrap: break-word;
    }
    .ticket-box p {
        font-size: 14px;
        color: #777;
        margin: 5px 0;
    }
    .ticket-box .edit-btn {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: #f4e7b5;
        border: none;
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 6px;
        cursor: pointer;
    }

    .buttons-under-events {
        text-align: center;
        margin-top: 20px;
    }
    .add-event-btn {
        background: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
    }
    .view-all-btn {
        background: #2196F3;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        margin-left: 10px;
    }

    textarea {
        width: 100%;
        font-size: 16px;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }
    button {
        background: #6DA9C9;
        color: white;
        border: none;
        padding: 8px 16px;
        margin-top: 10px;
        border-radius: 6px;
        cursor: pointer;
    }
    </style>
</head>
<body>

    <div class="top-bar">
        <img src="logoBar.png" alt="Logo" class="logo-bar">
        <div class="user-info">
            <img src="pfp.png" alt="Profile Picture" class="profile-pic">
            <span class="username"><?php echo htmlspecialchars($username); ?></span>
            <a class="logout" href="logout.php">Logout</a>
        </div>
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

    <div class="admin-form">
        <h3>Ganti Gambar Promo Header</h3>
        <input type="file" accept="image/*">
        <button>Upload</button>
    </div>

    <div class="search-bar">
        <input type="text" placeholder="Cari event...">
        <button type="button">Search</button>
    </div>

    <div class="sidebar-wrapper">
        <div class="category-bar">Admin Controls</div>
        <div class="sidebar">
            <div class="category-item">Dashboard</div>
            <div class="category-item">Kelola Events</div>
            <div class="category-item">Kelola Promo</div>
            <div class="category-item">Rekapitulasi Data</div>
            <div class="category-item">Laporan</div>
        </div>
    </div>

    <div class="content">
        <div class="editable-text" id="description-section">
            <h3>Deskripsi Platform</h3>
            <p id="desc-display">My Ticket adalah platform ticketing event yang membantu anda untuk membeli ticket, segera check out ticket di event kesukaan anda sebelum kehabisan ya.</p>
            <button id="edit-btn">✏️ Edit Deskripsi</button>
            <div id="edit-area" style="display:none;">
                <textarea id="desc-input" rows="4">My Ticket adalah platform ticketing event yang membantu anda untuk membeli ticket, segera check out ticket di event kesukaan anda sebelum kehabisan ya.</textarea><br>
                <button id="save-btn">💾 Simpan</button>
                <button id="cancel-btn" style="margin-left:10px;">❌ Batal</button>
            </div>
        </div>

        <div class="ticket-section">
            <div class="row">
                <div class="ticket-box">
                    <img src="event1.jpg" alt="Ticket 1">
                    <h3><b>Seminar Nasional Ekonomi</b></h3>
                    <p>Start: 8:00 AM</p>
                    <p>From: IDR 25.000</p>
                    <button class="edit-btn">✏️ Ganti Event</button>
                </div>
                <div class="ticket-box">
                    <img src="event2.jpg" alt="Ticket 2">
                    <h3><b>Drake Concert BJM</b></h3>
                    <p>Start: 9:00 PM</p>
                    <p>From: IDR 1.000.000</p>
                    <button class="edit-btn">✏️ Ganti Event</button>
                </div>
                <div class="ticket-box">
                    <img src="event3.jpg" alt="Ticket 3">
                    <h3><b>Chaeyoung Concert</b></h3>
                    <p>Start: 7:00 PM</p>
                    <p>From: IDR 500.000</p>
                    <button class="edit-btn">✏️ Ganti Event</button>
                </div>
            </div>

            <div class="buttons-under-events">
                <button class="add-event-btn">➕ Tambah Event</button>
                <button class="view-all-btn">📄 Lihat Semua Event</button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = [...document.querySelectorAll('.slider img')];
        let current = 0, interval = null, revert = null;
        const AUTO = 5000, MANUAL = 10000;

        const show = i => {
            current = (i + slides.length) % slides.length;
            slides.forEach((s, idx) => s.classList.toggle('active', idx === current));
        };
        const next = () => show(current + 1), prev = () => show(current - 1);

        function startAuto(ms = AUTO) {
            stopAuto();
            interval = setInterval(next, ms);
        }

        function stopAuto() {
            if (interval) {
                clearInterval(interval);
                interval = null;
            }
        }

        function manual() {
            stopAuto();
            if (revert) clearTimeout(revert);
            interval = setInterval(next, MANUAL);
            revert = setTimeout(() => { stopAuto(); startAuto(); }, MANUAL);
        }

        document.getElementById('prevBtn').addEventListener('click', () => { prev(); manual(); });
        document.getElementById('nextBtn').addEventListener('click', () => { next(); manual(); });

        show(0);
        startAuto();

        document.getElementById('edit-btn').addEventListener('click', function () {
            document.getElementById('edit-area').style.display = 'block';
            document.getElementById('edit-btn').style.display = 'none';
        });
        document.getElementById('cancel-btn').addEventListener('click', function () {
            document.getElementById('edit-area').style.display = 'none';
            document.getElementById('edit-btn').style.display = 'inline-block';
        });
        document.getElementById('save-btn').addEventListener('click', function () {
            const newDesc = document.getElementById('desc-input').value;
            document.getElementById('desc-display').innerText = newDesc;
            document.getElementById('edit-area').style.display = 'none';
            document.getElementById('edit-btn').style.display = 'inline-block';
        });

        const sidebar = document.querySelector('.sidebar');
        const content = document.querySelector('.content');

        function adjustSidebarHeight() {
            const totalHeight = Math.max(
                content.offsetHeight + content.offsetTop,
                document.body.scrollHeight,
                document.documentElement.scrollHeight
            );
            sidebar.style.height = totalHeight + 'px';
        }

        window.addEventListener('load', adjustSidebarHeight);
        window.addEventListener('resize', adjustSidebarHeight);
    });
    </script>
</body>
</html>