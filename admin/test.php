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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .system-title {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #3498db, #2980b9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .admin-info h4 {
            margin: 0;
            color: #2c3e50;
        }

        .admin-info p {
            margin: 0;
            color: #7f8c8d;
            font-size: 0.8rem;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        .main-container {
            display: flex;
            height: calc(100vh - 80px);
        }

        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2rem 0;
            box-shadow: 2px 0 20px rgba(0,0,0,0.1);
        }

        .nav-menu {
            list-style: none;
        }

        .nav-btn {
            width: 100%;
            background: none;
            border: none;
            padding: 1rem 2rem;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            color: #2c3e50;
            font-size: 1rem;
        }

        .nav-btn:hover {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }

        .nav-btn.active {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .nav-icon {
            font-size: 1.2rem;
        }

        .content-area {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .content-section {
            display: none;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .content-section.active {
            display: block;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #ecf0f1;
        }

        .section-title {
            color: #2c3e50;
            font-size: 1.5rem;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.4);
        }

        /* Dashboard Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #fff, #f8f9fa);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            color: #7f8c8d;
        }

        /* Kataklar Grid */
        .kataklar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .katak-card {
            background: linear-gradient(135deg, #fff, #f8f9fa);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .katak-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #3498db, #2980b9);
        }

        .katak-card.inactive::before {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
        }

        .katak-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .katak-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .katak-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .katak-status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-active {
            background: #d5e8d4;
            color: #27ae60;
        }

        .status-inactive {
            background: #fadbd8;
            color: #e74c3c;
        }

        .status-maintenance {
            background: #fdeaa7;
            color: #f39c12;
        }

        .katak-info {
            display: flex;
            justify-content: space-around;
            margin: 1rem 0;
        }

        .info-item {
            text-align: center;
        }

        .info-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .info-label {
            font-size: 0.8rem;
            color: #7f8c8d;
            margin-top: 0.2rem;
        }

        .katak-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .katak-actions .btn {
            flex: 1;
            padding: 0.5rem;
            font-size: 0.8rem;
        }

        /* Forms */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: 500;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
            margin-top: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
        }

        th {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background: #f8f9fa;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            animation: modalSlide 0.3s ease-out;
        }

        @keyframes modalSlide {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        /* Alert Container */
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            animation: slideIn 0.3s ease-out;
        }

        .alert-success {
            background: #d5e8d4;
            color: #27ae60;
            border-left: 4px solid #27ae60;
        }

        .alert-error {
            background: #fadbd8;
            color: #e74c3c;
            border-left: 4px solid #e74c3c;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .kataklar-grid {
                grid-template-columns: 1fr;
            }
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
                        Go'sht topshirish
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
                        Go'sht sotish
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-btn" onclick="showSection('pulolish')">
                        <span class="nav-icon">🏦</span>
                        Pul olish
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
            <section id="dashboard" class="content-section active">
                <div class="section-header">
                    <h2 class="section-title">📊 Dashboard</h2>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>8</h3>
                        <p>Jami kataklar</p>
                    </div>
                    <div class="stat-card">
                        <h3>2,450</h3>
                        <p>Jami jo'jalar</p>
                    </div>
                    <div class="stat-card">
                        <h3>45,000,000</h3>
                        <p>Oylik daromad (so'm)</p>
                    </div>
                    <div class="stat-card">
                        <h3>12,500,000</h3>
                        <p>Oylik xarajat (so'm)</p>
                    </div>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Katak</th>
                                <th>Jo'jalar soni</th>
                                <th>Yoshi</th>
                                <th>Holati</th>
                                <th>Oxirgi yem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Katak #1</td>
                                <td>245</td>
                                <td>25 kun</td>
                                <td><span class="katak-status status-active">Faol</span></td>
                                <td>Bugun 08:30</td>
                            </tr>
                            <tr>
                                <td>Katak #2</td>
                                <td>198</td>
                                <td>18 kun</td>
                                <td><span class="katak-status status-active">Faol</span></td>
                                <td>Bugun 08:45</td>
                            </tr>
                            <tr>
                                <td>Katak #3</td>
                                <td>0</td>
                                <td>-</td>
                                <td><span class="katak-status status-inactive">Faol emas</span></td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Kataklar -->
            <section id="kataklist" class="content-section">
                <div class="section-header">
                    <h2 class="section-title">🏠 Kataklar ro'yxati</h2>
                    <button class="btn btn-primary" onclick="showModal('katakModal')">
                        ➕ Yangi katak yaratish
                    </button>
                </div>
                
                <div class="kataklar-grid">
                    <!-- Faol kataklar -->
                    <div class="katak-card">
                        <div class="katak-header">
                            <div class="katak-title">Katak #1</div>
                            <span class="katak-status status-active">Faol</span>
                        </div>
                        <div class="katak-info">
                            <div class="info-item">
                                <div class="info-value">245</div>
                                <div class="info-label">Jo'jalar</div>
                            </div>
                            <div class="info-item">
                                <div class="info-value">25</div>
                                <div class="info-label">Kun</div>
                            </div>
                            <div class="info-item">
                                <div class="info-value">500</div>
                                <div class="info-label">Sig'im</div>
                            </div>
                        </div>
                        <p>Asosiy katak, katta o'lcham. Shimolidagi bino.</p>
                        <div class="katak-actions">
                            <button class="btn btn-primary">Ko'rish</button>
                            <button class="btn btn-warning">Tahrirlash</button>
                        </div>
                    </div>

                    <div class="katak-card">
                        <div class="katak-header">
                            <div class="katak-title">Katak #2</div>
                            <span class="katak-status status-active">Faol</span>
                        </div>
                        <div class="katak-info">
                            <div class="info-item">
                                <div class="info-value">198</div>
                                <div class="info-label">Jo'jalar</div>
                            </div>
                            <div class="info-item">
                                <div class="info-value">18</div>
                                <div class="info-label">Kun</div>
                            </div>
                            <div class="info-item">
                                <div class="info-value">300</div>
                                <div class="info-label">Sig'im</div>
                            </div>
                        </div>
                        <p>O'rta o'lchamli katak, janubiy qism.</p>
                        <div class="katak-actions">
                            <button class="btn btn-primary">Ko'rish</button>
                            <button class="btn btn-warning">Tahrirlash</button>
                        </div>
                    </div>

                    <!-- Faol emas kataklar -->
                    <div class="katak-card inactive">
                        <div class="katak-header">
                            <div class="katak-title">Katak #3</div>
                            <span class="katak-status status-inactive">Faol emas</span>
                        </div>
                        <div class="katak-info">
                            <div class="info-item">
                                <div class="info-value">0</div>
                                <div class="info-label">Jo'jalar</div>
                            </div>
                            <div class="info-item">
                                <div class="info-value">-</div>
                                <div class="info-label">Kun</div>
                            </div>
                            <div class="info-item">
                                <div class="info-value">400</div>
                                <div class="info-label">Sig'im</div>
                            </div>
                        </div>
                        <p>Bo'sh katak, yangi jo'jalar uchun tayyor.</p>
                        <div class="katak-actions">
                            <button class="btn btn-success">Faollashtirish</button>
                            <button class="btn btn-warning">Tahrirlash</button>
                        </div>
                    </div>

                    <!-- Ta'mirlash jarayonida -->
                    <div class="katak-card">
                        <div class="katak-header">
                            <div class="katak-title">Katak #4</div>
                            <span class="katak-status status-maintenance">Ta'mirlash</span>
                        </div>
                        <div class="katak-info">
                            <div class="info-item">
                                <div class="info-value">0</div>
                                <div class="info-label">Jo'jalar</div>
                            </div>
                            <div class="info-item">
                                <div class="info-value">-</div>
                                <div class="info-label">Kun</div>
                            </div>
                            <div class="info-item">
                                <div class="info-value">350</div>
                                <div class="info-label">Sig'im</div>
                            </div>
                        </div>
                        <p>Ta'mirlash jarayonida. Ventilyatsiya tizimini yangilash.</p>
                        <div class="katak-actions">
                            <button class="btn btn-primary">Ko'rish</button>
                            <button class="btn btn-warning">Tahrirlash</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Jo'ja qo'shish -->
            <section id="joja" class="content-section">
                <div class="section-header">
                    <h2 class="section-title">🐥 Jo'ja qo'shish</h2>
                </div>
                
                <form id="jojaForm" onsubmit="addJoja(event)">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Katak tanlang:</label>
                            <select id="joja_katak_id" required>
                                <option value="">Katakni tanlang</option>
                                <option value="1">Katak #1 (245/500)</option>
                                <option value="2">Katak #2 (198/300)</option>
                                <option value="3">Katak #3 (0/400)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jo'jalar soni:</label>
                            <input type="number" id="joja_soni" required min="1" placeholder="Masalan: 100">
                        </div>
                        <div class="form-group">
                            <label>Narxi (dona):</label>
                            <input type="number" id="joja_narxi" required min="0" step="0.01" placeholder="Masalan: 5000">
                        </div>
                        <div class="form-group">
                            <label>Sana:</label>
                            <input type="date" id="joja_sana" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Izoh:</label>
                        <textarea id="joja_izoh" rows="3" placeholder="Qo'shimcha ma'lumotlar..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">🐥 Jo'ja qo'shish</button>
                </form>
            </section>
            <!-- Yem berish -->
            <section id="yem" class="content-section">
                <div class="section-header">
                    <h2 class="section-title">🌾 Yem berish</h2>
                </div>
                <form id="yemForm" onsubmit="addYem(event)">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Katak tanlang:</label>
                            <select id="yem_katak_id" required>
                                <option value="">Katakni tanlang</option>
                                <option value="1">Katak #1 (245 jo'ja)</option>
                                <option value="2">Katak #2 (198 jo'ja)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Yem miqdori (kg):</label>
                            <input type="number" id="yem_miqdori" required min="0" step="0.01" placeholder="Masalan: 50">
                        </div>
                        <div class="form-group">
                            <label>Narxi (kg):</label>
                            <input type="number" id="yem_narxi" required min="0" step="0.01" placeholder="Masalan: 3500">
                        </div>
                        <div class="form-group">
                            <label>Sana:</label>
                            <input type="date" id="yem_sana" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Izoh:</label>
                        <textarea id="yem_izoh" rows="3" placeholder="Yem turi, sifati haqida..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">🌾 Yem berish</button
                
                </form>
            </section>
            <!-- O'lgan jo'ja -->
            <section id="olganjoja" class="content-section">
                <div class="section-header">
                    <h2 class="section-title">💀 O'lgan jo'jalarni ayirish</h2>
                </div>
                
                <form id="olganJojaForm" onsubmit="addOlganJoja(event)">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Katak tanlang:</label>
                            <select id="olgan_katak_id" required>
                                <option value="">Katakni tanlang</option>
                                <option value="1">Katak #1 (245 jo'ja)</option>
                                <option value="2">Katak #2 (198 jo'ja)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>O'lgan jo'jalar soni:</label>
                            <input type="number" id="olgan_soni" required min="1" placeholder="Masalan: 5">
                        </div>
                        <div class="form-group">
                            <label>Sana:</label>
                            <input type="date" id="olgan_sana" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Sabab/Izoh:</label>
                        <textarea id="olgan_izoh" rows="3" placeholder="O'lim sababi..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">💀 O'lgan jo'jani ayirish</button>
                </form>
            </section>

            <!-- Go'sht topshirish -->
            <section id="goshttopshirish" class="content-section">
                <div class="section-header">
                    <h2 class="section-title">🥩 Go'sht topshirish</h2>
                </div>
                
                <form id="goshtTopshirishForm" onsubmit="addGoshtTopshirish(event)">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Katak tanlang:</label>
                            <select id="gosht_katak_id" required>
                                <option value="">Katakni tanlang</option>
                                <option value="1">Katak #1 (245 jo'ja, 25 kun)</option>
                                <option value="2">Katak #2 (198 jo'ja, 18 kun)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Topshiriladigan jo'jalar soni:</label>
                            <input type="number" id="gosht_soni" required min="1" placeholder="Masalan: 50">
                        </div>
                        <div class="form-group">
                            <label>1 kg narxi (so'm):</label>
                            <input type="number" id="gosht_kg_narxi" required min="0" step="0.01" placeholder="Masalan: 25000">
                        </div>
                        <div class="form-group">
                            <label>Topshirish sanasi:</label>
                            <input type="date" id="gosht_sana" required>
                        </div>
                        <div class="form-group">
                            <label>Mijoz nomi:</label>
                            <input type="text" id="gosht_mijoz" required placeholder="Masalan: Anvar Karimov">
                        </div>
                        <div class="form-group">
                            <label>Mijoz telefoni:</label>
                            <input type="tel" id="gosht_telefon" placeholder="+998 90 123 45 67">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Izoh:</label>
                        <textarea id="gosht_izoh" rows="3" placeholder="Qo'shimcha ma'lumotlar..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">🥩 Go'sht topshirish</button>
                </form>

                <div class="table-container">
                    <h3>So'nggi topshirishlar</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Sana</th>
                                <th>Katak</th>
                                <th>Jo'jalar</th>
                                <th>Mijoz</th>
                                <th>Kg narxi</th>
                                <th>Holati</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024-01-15</td>
                                <td>Katak #1</td>
                                <td>25</td>
                                <td>Anvar Karimov</td>
                                <td>25,000</td>
                                <td><span class="katak-status status-active">Topshirildi</span></td>
                            </tr>
                            <tr>
                                <td>2024-01-10</td>
                                <td>Katak #2</td>
                                <td>30</td>
                                <td>Dilshod Toshev</td>
                                <td>24,500</td>
                                <td><span class="katak-status status-maintenance">Kutilmoqda</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Harajatlar -->
            <section id="harajat" class="content-section">
                <div class="section-header">
                    <h2 class="section-title">💸 Harajatlar</h2>
                </div>
                
                <form id="harajatForm" onsubmit="addHarajat(event)">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Harajat turi:</label>
                            <select id="harajat_turi" required>
                                <option value="">Harajat turini tanlang</option>
                                <option value="yem">Yem xarid qilish</option>
                                <option value="joja">Jo'ja xarid qilish</option>
                                <option value="dori">Dori-darmon</option>
                                <option value="komunal">Komunal to'lovlar</option>
                                <option value="transport">Transport</option>
                                <option value="ishchi">Ishchi maoshi</option>
                                <option value="ta'mirlash">Ta'mirlash</option>
                                <option value="boshqa">Boshqa</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Miqdor (so'm):</label>
                            <input type="number" id="harajat_miqdor" required min="0" step="0.01" placeholder="Masalan: 500000">
                        </div>
                        <div class="form-group">
                            <label>Sana:</label>
                            <input type="date" id="harajat_sana" required>
                        </div>
                        <div class="form-group">
                            <label>Qabul qiluvchi/Do'kon:</label>
                            <input type="text" id="harajat_qabulchi" placeholder="Masalan: Yem do'koni">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Izoh:</label>
                        <textarea id="harajat_izoh" rows="3" placeholder="Harajat tafsilotlari..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">💸 Harajat qo'shish</button>
                </form>

                <div class="table-container">
                    <h3>So'nggi harajatlar</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Sana</th>
                                <th>Tur</th>
                                <th>Miqdor</th>
                                <th>Qabul qiluvchi</th>
                                <th>Izoh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024-01-20</td>
                                <td>Yem</td>
                                <td>750,000</td>
                                <td>Yem do'koni</td>
                                <td>Starter yem 200kg</td>
                            </tr>
                            <tr>
                                <td>2024-01-18</td>
                                <td>Komunal</td>
                                <td>450,000</td>
                                <td>Elektr tarmog'i</td>
                                <td>Oylik elektr to'lovi</td>
                            </tr>
                            <tr>
                                <td>2024-01-15</td>
                                <td>Dori</td>
                                <td>120,000</td>
                                <td>Vet apteka</td>
                                <td>Vitamin kompleksi</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Go'sht sotish -->
            <section id="goshtsotish" class="content-section">
                <div class="section-header">
                    <h2 class="section-title">💰 Go'sht sotish</h2>
                </div>
                
                <form id="goshtSotishForm" onsubmit="addGoshtSotish(event)">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Mijoz nomi:</label>
                            <input type="text" id="sotish_mijoz" required placeholder="Masalan: Rustam Abdullayev">
                        </div>
                        <div class="form-group">
                            <label>Telefon raqami:</label>
                            <input type="tel" id="sotish_telefon" placeholder="+998 90 123 45 67">
                        </div>
                        <div class="form-group">
                            <label>Go'sht miqdori (kg):</label>
                            <input type="number" id="sotish_miqdor" required min="0" step="0.01" placeholder="Masalan: 150.5">
                        </div>
                        <div class="form-group">
                            <label>1 kg narxi (so'm):</label>
                            <input type="number" id="sotish_narxi" required min="0" step="0.01" placeholder="Masalan: 35000">
                        </div>
                        <div class="form-group">
                            <label>Sotish sanasi:</label>
                            <input type="date" id="sotish_sana" required>
                        </div>
                        <div class="form-group">
                            <label>To'lov usuli:</label>
                            <select id="sotish_tolov" required>
                                <option value="">To'lov usulini tanlang</option>
                                <option value="naqd">Naqd pul</option>
                                <option value="plastik">Plastik karta</option>
                                <option value="o'tkazma">Bank o'tkazmasi</option>
                                <option value="qarz">Qarzga</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Mijoz manzili:</label>
                        <textarea id="sotish_manzil" rows="2" placeholder="Mijoz manzili..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Izoh:</label>
                        <textarea id="sotish_izoh" rows="3" placeholder="Qo'shimcha ma'lumotlar..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">💰 Sotishni ro'yxatga olish</button>
                </form>

                <div class="table-container">
                    <h3>So'nggi sotishlar</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Sana</th>
                                <th>Mijoz</th>
                                <th>Miqdor (kg)</th>
                                <th>Narxi</th>
                                <th>Jami</th>
                                <th>To'lov</th>
                                <th>Holati</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024-01-22</td>
                                <td>Rustam Abdullayev</td>
                                <td>125.0</td>
                                <td>35,000</td>
                                <td>4,375,000</td>
                                <td>Naqd</td>
                                <td><span class="katak-status status-active">To'landi</span></td>
                            </tr>
                            <tr>
                                <td>2024-01-20</td>
                                <td>Nodir Karimov</td>
                                <td>89.5</td>
                                <td>34,500</td>
                                <td>3,087,750</td>
                                <td>Karta</td>
                                <td><span class="katak-status status-active">To'landi</span></td>
                            </tr>
                            <tr>
                                <td>2024-01-18</td>
                                <td>Aziz Toshev</td>
                                <td>200.0</td>
                                <td>33,000</td>
                                <td>6,600,000</td>
                                <td>Qarz</td>
                                <td><span class="katak-status status-maintenance">Qarzda</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Pul olish -->
            <section id="pulolish" class="content-section">
                <div class="section-header">
                    <h2 class="section-title">🏦 Mijozdan pul olish</h2>
                </div>
                
                <form id="pulOlishForm" onsubmit="addPulOlish(event)">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Mijoz nomi:</label>
                            <input type="text" id="pul_mijoz" required placeholder="Masalan: Aziz Toshev">
                        </div>
                        <div class="form-group">
                            <label>Telefon raqami:</label>
                            <input type="tel" id="pul_telefon" placeholder="+998 90 123 45 67">
                        </div>
                        <div class="form-group">
                            <label>Qarz miqdori (so'm):</label>
                            <input type="number" id="pul_qarz" required min="0" step="0.01" placeholder="Masalan: 6600000">
                        </div>
                        <div class="form-group">
                            <label>Olingan miqdor (so'm):</label>
                            <input type="number" id="pul_olingan" required min="0" step="0.01" placeholder="Masalan: 3000000">
                        </div>
                        <div class="form-group">
                            <label>Olish sanasi:</label>
                            <input type="date" id="pul_sana" required>
                        </div>
                        <div class="form-group">
                            <label>To'lov usuli:</label>
                            <select id="pul_tolov" required>
                                <option value="">To'lov usulini tanlang</option>
                                <option value="naqd">Naqd pul</option>
                                <option value="plastik">Plastik karta</option>
                                <option value="o'tkazma">Bank o'tkazmasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Izoh:</label>
                        <textarea id="pul_izoh" rows="3" placeholder="Qo'shimcha ma'lumotlar..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">🏦 Pul olishni qayd qilish</button>
                </form>

                <div class="table-container">
                    <h3>Qarzda mijozlar</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Mijoz</th>
                                <th>Telefon</th>
                                <th>Umumiy qarz</th>
                                <th>To'langan</th>
                                <th>Qolgan qarz</th>
                                <th>So'nggi to'lov</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Aziz Toshev</td>
                                <td>+998 90 123 45 67</td>
                                <td>6,600,000</td>
                                <td>3,000,000</td>
                                <td style="color: #e74c3c; font-weight: bold;">3,600,000</td>
                                <td>2024-01-20</td>
                            </tr>
                            <tr>
                                <td>Karim Nazarov</td>
                                <td>+998 91 234 56 78</td>
                                <td>2,500,000</td>
                                <td>1,000,000</td>
                                <td style="color: #e74c3c; font-weight: bold;">1,500,000</td>
                                <td>2024-01-15</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-container">
                    <h3>So'nggi to'lovlar</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Sana</th>
                                <th>Mijoz</th>
                                <th>Miqdor</th>
                                <th>To'lov usuli</th>
                                <th>Qolgan qarz</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024-01-20</td>
                                <td>Aziz Toshev</td>
                                <td>3,000,000</td>
                                <td>Bank o'tkazmasi</td>
                                <td>3,600,000</td>
                            </tr>
                            <tr>
                                <td>2024-01-15</td>
                                <td>Karim Nazarov</td>
                                <td>1,000,000</td>
                                <td>Naqd pul</td>
                                <td>1,500,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Hisobotlar -->
            <section id="hisobot" class="content-section">
                <div class="section-header">
                    <h2 class="section-title">📈 Hisobotlar</h2>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Hisobot turi:</label>
                        <select id="report_type" onchange="generateReport()">
                            <option value="">Hisobot turini tanlang</option>
                            <option value="oylik">Oylik hisobot</option>
                            <option value="kataklar">Kataklar bo'yicha</option>
                            <option value="moliyaviy">Moliyaviy hisobot</option>
                            <option value="qarzlar">Qarzlar hisoboti</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Boshlanish sanasi:</label>
                        <input type="date" id="report_start">
                    </div>
                    <div class="form-group">
                        <label>Tugash sanasi:</label>
                        <input type="date" id="report_end">
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>32,500,000</h3>
                        <p>Umumiy daromad (so'm)</p>
                    </div>
                    <div class="stat-card">
                        <h3>15,200,000</h3>
                        <p>Umumiy xarajat (so'm)</p>
                    </div>
                    <div class="stat-card">
                        <h3>17,300,000</h3>
                        <p>Sof foyda (so'm)</p>
                    </div>
                    <div class="stat-card">
                        <h3>5,100,000</h3>
                        <p>Qarzda qolgan (so'm)</p>
                    </div>
                </div>

                <div class="table-container">
                    <h3>Oylik ko'rsatkichlar</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Oy</th>
                                <th>Sotilgan go'sht (kg)</th>
                                <th>Daromad</th>
                                <th>Xarajat</th>
                                <th>Foyda</th>
                                <th>Rentabellik</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024 Yanvar</td>
                                <td>1,250</td>
                                <td>45,000,000</td>
                                <td>25,000,000</td>
                                <td>20,000,000</td>
                                <td style="color: #27ae60; font-weight: bold;">80%</td>
                            </tr>
                            <tr>
                                <td>2023 Dekabr</td>
                                <td>1,180</td>
                                <td>42,500,000</td>
                                <td>23,500,000</td>
                                <td>19,000,000</td>
                                <td style="color: #27ae60; font-weight: bold;">81%</td>
                            </tr>
                            <tr>
                                <td>2023 Noyabr</td>
                                <td>1,350</td>
                                <td>48,000,000</td>
                                <td>27,000,000</td>
                                <td>21,000,000</td>
                                <td style="color: #27ae60; font-weight: bold;">78%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal -->
    <div id="katakModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('katakModal')">&times;</span>
            <h2>🏠 Yangi katak yaratish</h2>
            <form id="katakForm" onsubmit="addKatak(event)">
                <div class="form-group">
                    <label>Katak nomi:</label>
                    <input type="text" id="katak_nomi" required placeholder="Masalan: Katak #5">
                </div>
                <div class="form-group">
                    <label>Sig'imi (maksimal jo'jalar soni):</label>
                    <input type="number" id="katak_sigimi" required min="1" placeholder="Masalan: 500">
                </div>
                <div class="form-group">
                    <label>Izoh:</label>
                    <textarea id="katak_izoh" rows="3" placeholder="Katak haqida qo'shimcha ma'lumot..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">✅ Katak yaratish</button>
            </form>
        </div>
    </div>

    <div id="alertContainer" style="position: fixed; top: 100px; right: 20px; z-index: 3000;"></div>

    <script>
        // Navigation
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => section.classList.remove('active'));
            
            // Show selected section
            document.getElementById(sectionId).classList.add('active');
            
            // Update active nav button
            const navButtons = document.querySelectorAll('.nav-btn');
            navButtons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }

        // Modal functions
        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Alert function
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            
            alertContainer.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 3000);
        }

        // Form submissions
        function addKatak(event) {
            event.preventDefault();
            showAlert('Yangi katak muvaffaqiyatli yaratildi!');
            closeModal('katakModal');
            document.getElementById('katakForm').reset();
        }

        function addJoja(event) {
            event.preventDefault();
            showAlert('Jo\'jalar muvaffaqiyatli qo\'shildi!');
            document.getElementById('jojaForm').reset();
        }

        function addYem(event) {
            event.preventDefault();
            showAlert('Yem berish muvaffaqiyatli qayd qilindi!');
            document.getElementById('yemForm').reset();
        }

        function addOlganJoja(event) {
            event.preventDefault();
            showAlert('O\'lgan jo\'jalar muvaffaqiyatli ayirildi!', 'error');
            document.getElementById('olganJojaForm').reset();
        }

        function addGoshtTopshirish(event) {
            event.preventDefault();
            showAlert('Go\'sht topshirish muvaffaqiyatli qayd qilindi!');
            document.getElementById('goshtTopshirishForm').reset();
        }

        function addHarajat(event) {
            event.preventDefault();
            showAlert('Harajat muvaffaqiyatli qo\'shildi!', 'error');
            document.getElementById('harajatForm').reset();
        }

        function addGoshtSotish(event) {
            event.preventDefault();
            showAlert('Go\'sht sotish muvaffaqiyatli qayd qilindi!');
            document.getElementById('goshtSotishForm').reset();
        }

        function addPulOlish(event) {
            event.preventDefault();
            showAlert('Pul olish muvaffaqiyatli qayd qilindi!');
            document.getElementById('pulOlishForm').reset();
        }

        function generateReport() {
            const reportType = document.getElementById('report_type').value;
            if (reportType) {
                showAlert(`${reportType.charAt(0).toUpperCase() + reportType.slice(1)} hisobot yaratilmoqda...`);
            }
        }

        function logout() {
            if (confirm('Tizimdan chiqishni xohlaysizmi?')) {
                showAlert('Tizimdan muvaffaqiyatli chiqdingiz!');
                // Redirect to login page
            }
        }

        // Set today's date as default for all date inputs
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                if (!input.value) {
                    input.value = today;
                }
            });
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>