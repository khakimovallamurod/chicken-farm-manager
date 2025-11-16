<?php
session_start();
include_once '../config.php';
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tovuq Firma CRM - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/dashboard_style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/yem_berish_style.css">
   
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="logo-section">
            <div class="logo">🐔 TOVUQ FIRMA</div>
            <div class="system-title">Boshqaruv Tizimi</div>
        </div>
        
        <div class="admin-profile">
            <div class="admin-avatar" id="adminAvatar"><?= strtoupper($_SESSION['fullname'][0] . explode(' ', $_SESSION['fullname'])[1][0]) ?></div>
            <div class="admin-info">
                <h4 id="adminName"><?=$_SESSION['fullname']?></h4>
                <p id="adminRole"><?=$_SESSION['rol']?></p>
            </div>
            <form action="../auth/logout.php" method="post">
                <button type="submit" class="logout-btn">Chiqish</button>
            </form>
        </div>
    </header>

    <div class="main-container">
        <!-- Sidebar -->
        <button class="sidebar-toggle-inside" id="sidebarToggle">
            ☰
        </button>
        <nav class="sidebar" id="sidebar">
            <!-- Sidebar Toggle Button (sidebar ichida, o'ng tomonda) -->            
            <ul class="nav-menu">
                <li class="nav-item">
                    <button class="nav-btn active" onclick="showSection('dashboard')">
                        <span class="nav-icon">📊</span>
                        Dashboard
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('kataklist')">
                        <span class="nav-icon">🏠</span>
                        Kataklar
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('joja')">
                        <span class="nav-icon">🐥</span>
                        Jo'ja qo'shish
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('yem')">
                        <span class="nav-icon">🌾</span>
                        Yem berish
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('olganjoja')">
                        <span class="nav-icon">💀</span>
                        O'lgan jo'ja
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('goshttopshirish')">
                        <span class="nav-icon">🥩</span>
                        Go'sht so'yish 
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('mijozqoshish')">
                        <span class="nav-icon">👥</span>
                        Mijozlar
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('mahsulotlar')">
                        <span class="nav-icon">📦</span>
                        Mahsulotlar
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('taminotchilar')">
                        <span class="nav-icon">🚛</span>
                        Ta'minotchilar
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('kirim')">
                        <span class="nav-icon">📥</span>
                        Kirimlar
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('harajat')">
                        <span class="nav-icon">💸</span>
                        Harajatlar
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('goshtsotish')">
                        <span class="nav-icon">💰</span>
                        Sotuvlar
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('pulolish')">
                        <span class="nav-icon">🏦</span>
                        Pul olish
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('pulberish')">
                        <span class="nav-icon">🏦</span>
                        Pul berish
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('hisobot')">
                        <span class="nav-icon">📈</span>
                        Hisobotlar
                    </button>
                </li>
            </ul>
        </nav>
        <!-- Content Area -->
        <main class="content-area">
            <!-- Dashboard -->
            <?php include_once 'view_dashboard.php' ?>

            <!-- Kataklar -->
            <?php include_once 'kataklar_view.php'; ?>
            <!-- Jo'ja qo'shish -->
            <?php include_once 'joja_qoshish.php'; ?>
            <!-- Yem berish -->
            <?php include_once 'yem_berish.php'; ?>
            <!-- Kirimlar -->
            <?php include_once 'kirimlar_qoshish.php'; ?>

            <!-- O'lgan jo'ja -->
            <?php include_once 'olgan_joja.php'; ?>

            <!-- Go'sht topshirish -->
            <?php
             include_once 'gosht_soyish.php';
            ?>

            <!-- Mijoz qo'shish -->
            <?php include_once 'mijoz_qoshish.php'; ?>
            <!-- Mahsulotlar -->
            <?php include_once 'mahsulotlar_view.php'; ?>
                        
            <!-- Ta'minotchi qo'shish modali -->
            <?php include_once 'taminotchi_qoshish.php'; ?>
            <!-- Harajatlar -->
            <?php include_once 'xarajatlar_qoshish.php'?>

            <!-- Sotuvlar -->
            <?php include_once 'sotuvlar_qoshish.php'?>

            <!-- Pul olish -->
            <?php include_once 'pul_olish_qoshish.php'?>

            <!-- Pul berish -->
            <?php include_once 'pul_berish_qoshish.php'?>
            
            <!-- Hisobotlar -->
            <?php include_once 'pl_dashboard.php'?>
            
        </main>
    </div>
    <div id="alertContainer" style="position: fixed; top: 100px; right: 20px; z-index: 3000;"></div>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- JSZip (Excel uchun kerak) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <!-- PDFMake (PDF uchun kerak) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>


    <script src="../assets/js/dashboard_script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            // Toggle button bosilganda
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                
                if (window.innerWidth <= 768) {
                    // Mobilda
                    sidebar.classList.toggle('open');
                    // Icon o'zgartirish
                    if (sidebar.classList.contains('open')) {
                        toggleBtn.innerHTML = '☰';
                    } else {
                        toggleBtn.innerHTML = '☰';
                    }
                } else {
                    // Desktopda
                    sidebar.classList.toggle('collapsed');
                    // Icon o'zgartirish
                    if (sidebar.classList.contains('collapsed')) {
                        toggleBtn.innerHTML = '☰';
                    } else {
                        toggleBtn.innerHTML = '☰';
                    }
                }
            });
            
            // Tashqariga bosilganda yopish (faqat mobilda)
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(event.target) && sidebar.classList.contains('open')) {
                        sidebar.classList.remove('open');
                        toggleBtn.innerHTML = '☰';
                    }
                }
            });
            
            // Nav buttonlarga bosilganda mobilda yopish
            const navButtons = document.querySelectorAll('.nav-btn');
            navButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('open');
                        toggleBtn.innerHTML = '☰';
                    }
                });
            });
            
            // Ekran o'lchami o'zgarganda
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('open');
                    sidebar.classList.remove('collapsed');
                    toggleBtn.innerHTML = '☰';
                }
            });
        });
        function loadKatakView() {
            $.get("get/katak_view_data.php", function(data) {
                $("#katakviewcn").html(data);
            });
        }
        loadKatakView();
        function loadMahsulotlar() {
            $.get("get/mahsulot_data.php", function(data) {
                $("#mahsulotlarcn").html(data);
            });
        }
        loadMahsulotlar();

        function loadJojaQoshish() {
            $.get("get/jojaqoshish_data.php", function(data) {
                $("#jojaqoshishcn").html(data);
            });        
        }
        loadJojaQoshish();

        function loadYemBerish() {
            $.get("get/yemberish_data.php", function(data) {
                $("#yemberishcn").html(data);
            });        
        }
        loadYemBerish();

        function loadOlganJoja() {
            $.get("get/olganjoja_data.php", function(data) {
                $("#olganjojacn").html(data);
            });        
        }
        loadOlganJoja();

        function loadGoshtSoyish() {
            $.get("get/goshtsoyish_data.php", function(data) {
                $("#goshtsoyishcn").html(data);
            });        
        }
        loadGoshtSoyish();

        function loadMijozQoshish() {
            $.get("get/mijozqoshish_data.php", function(data) {
                $("#mijozqoshishcn").html(data);
            });        
        }
        loadMijozQoshish();

        function loadTaminotchiQoshish() {
            $.get("get/taminotchiqoshish_data.php", function(data) {
                $("#taminotchiqoshishcn").html(data);
            });
        }
        loadTaminotchiQoshish();

        function loadKirimQoshish() {
            $.get("get/kirimqoshish_data.php", function(data) {
                $("#kirimqoshishcn").html(data);
            });
        }
        loadKirimQoshish();

        function loadXarajatQoshish() {
            $.get("get/xarajatqoshish_data.php", function(data) {
                $("#xarajatqoshishcn").html(data);
            });
        }
        loadXarajatQoshish();

        function loadSotuvQoshish() {
            $.get("get/sotuvqoshish_data.php", function(data) {
                $("#sotuvqoshishcn").html(data);
            });
        }
        loadSotuvQoshish();

        function loadPulOlish() {
            $.get("get/pulolish_data.php", function(data) {
                $("#pulolishcn").html(data);
            });
        }
        loadPulOlish();

        function loadPulBerish() {
            $.get("get/pulberish_data.php", function(data) {
                $("#pulberishcn").html(data);
            });
        }
        loadPulBerish();
    </script>
</body>
</html>
