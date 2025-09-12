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
    <style>        
        .remove-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-btn:hover {
            background-color: #c0392b;
        }

        /* ➕ Qo'shish tugmasi uchun stil */
        .add-product-btn {
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s;
            margin: 20px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .add-product-btn:hover {
            background-color: #229954;
        }

        /* Asosiy tugma */
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 20px;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }


        /* Mahsulot qatori uchun */
        .product-row {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #fafafa;
        }

        .product-row:hover {
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
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
        <nav class="sidebar">
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
    <script src="../assets/js/dashboard_script.js"></script>
    
</body>
</html>
