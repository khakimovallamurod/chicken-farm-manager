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
    <style>        
        /* ❌ Olib tashlash tugmasi uchun maxsus stil */
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
            <section id="goshtsotish" class="content-section">
                <div class="section-header">
                    <h2 class="section-title">💰 Sotuvlar</h2>
                </div>
                
                <form id="goshtSotishForm" onsubmit="addGoshtSotish(event)">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Mijoz nomi:</label>
                            <select id="sotish_mijoz" required>
                                <option value="">Mijozni tanlang</option>
                                <option value="1">Rustam Abdullayev</option>
                                <option value="2">Dilshod Toshev</option>
                                <option value="3">Anvar Karimov</option>
                            </select>                                       
                        </div>
                        <div class="form-group">
                            <label>Sotish sanasi:</label>
                            <input type="date" id="sotish_sana" required>
                        </div>                                                           
                    </div>
                    <div id="sotish-mahsulotlar-wrapper">
                        <div class="product-row">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Mahsulotni tanlang:</label>
                                    <select name="kategoriya[]" required>
                                        <option value="">Kategoriya tanlang</option>
                                        <option value="tuxum">Tovuq mahsulotlari</option>
                                        <option value="yem">Yem va ozuqa</option>
                                        <option value="dori">Dori-darmon</option>
                                        <option value="boshqa">Boshqa</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Miqdori (kg):</label>
                                    <input type="number" name="miqdor[]" placeholder="Miqdori (kg)" required min="0" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label>Narxi (so'm):</label>
                                    <input type="number" name="narx[]" placeholder="Narxi (so'm)" required min="0" step="1">
                                </div>                               
                            </div>
                        </div>
                    </div>
                    
                    <!-- ➕ Qo'shish tugmasi -->
                    <button type="button" class="add-product-btn" id="addSotishProductBtn" onclick="addSotishMahsulotRow()">
                        ➕ Mahsulot qo'shish
                    </button>

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
                            <label>Mijozni tanlang:</label>
                            <select id="pul_mijoz" required>
                                <option value="">Mijozni tanlang</option>
                                <option value="1">Aziz Toshev</option>
                                <option value="2">Karim Nazarov</option>
                                <option value="3">Dilshod Abdullayev</option>
                            </select>
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

    <div id="alertContainer" style="position: fixed; top: 100px; right: 20px; z-index: 3000;"></div>

    <script src="../assets/js/dashboard_script.js"></script>
    <script>
        
        function addSotishMahsulotRow() {
            console.log('addSotishMahsulotRow chaqirildi'); // Debug uchun
            
            const wrapper = document.getElementById('sotish-mahsulotlar-wrapper');
            const newRow = document.createElement('div');
            newRow.className = 'product-row';
            newRow.innerHTML = `
                <div class="form-grid">
                    <div class="form-group">
                        <label>Mahsulotni tanlang:</label>
                        <select name="kategoriya[]" required>
                            <option value="">Kategoriya tanlang</option>
                            <option value="tuxum">Tovuq mahsulotlari</option>
                            <option value="yem">Yem va ozuqa</option>
                            <option value="dori">Dori-darmon</option>
                            <option value="boshqa">Boshqa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Miqdori (kg):</label>
                        <input type="number" name="miqdor[]" placeholder="Miqdori (kg)" required min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Narxi (so'm):</label>
                        <input type="number" name="narx[]" placeholder="Narxi (so'm)" required min="0" step="1">
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="remove-btn" onclick="removeSotishMahsulot(this)" title="Mahsulotni olib tashlash">
                            ❌
                        </button>
                    </div>
                </div>
            `;
            wrapper.appendChild(newRow);
        }
        function removeSotishMahsulot(button) {
            const productRow = button.closest('.product-row');
            const wrapper = document.getElementById('sotish-mahsulotlar-wrapper');
            
            if (!productRow || !wrapper) {
                console.error('Element topilmadi');
                return;
            }
            if (wrapper.children.length > 1) {
                productRow.remove();
            } else {
                alert('Kamida bitta mahsulot qatori bo\'lishi kerak!');
            }
        }           
    </script>
</body>
</html>
