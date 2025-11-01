<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tovuq Farm</title>
    <link rel="stylesheet" href="assets/css/home_style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <a href="#" class="logo">
                <span class="logo-icon">🐔</span>
                <span>TOVUQ FARM</span>
            </a>
            <nav class="nav-menu" id="navMenu">
                <li><a href="#home">Bosh sahifa</a></li>
                <li><a href="#features">Xususiyatlar</a></li>
                <li><a href="#about">Biz haqimizda</a></li>
                <li><a href="#contact">Aloqa</a></li>
                <div class="auth-buttons">
                    <a href="auth/login.php" class="btn btn-login">Kirish</a>
                </div>                    
            </nav>
            <button class="mobile-toggle" onclick="toggleMenu()">☰</button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Zamonaviy tovuqxona boshqaruvi</h1>
            <p>Farmangizni professional darajada boshqaring. Jo'jalar, ozuqa, moliya va hisobotlarni bir joyda nazorat qiling.</p>
            <div class="hero-buttons">
                <a href="auth/login.php" class="btn btn-primary">Boshlash</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <h3>10,000+</h3>
                <p>Jo'jalar boshqarilgan</p>
            </div>
            <div class="stat-item">
                <h3>500+</h3>
                <p>Faol foydalanuvchilar</p>
            </div>
            <div class="stat-item">
                <h3>50+</h3>
                <p>Farmalar</p>
            </div>
            <div class="stat-item">
                <h3>99%</h3>
                <p>Mijozlar ehtiyotsizligi</p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header">
                <h2>Asosiy imkoniyatlar</h2>
                <p>Farmangizni samarali boshqarish uchun barcha vositalar</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🐣</div>
                    <h3>Jo'jalar boshqaruvi</h3>
                    <p>Jo'jalaringizni ro'yxatdan o'tkazing, o'sishini kuzating va sog'liqlarini nazorat qiling</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🌾</div>
                    <h3>Ozuqa nazorati</h3>
                    <p>Ozuqa zaxirasini boshqaring, iste'molni kuzating va o'z vaqtida buyurtma bering</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Hisobotlar va analitika</h3>
                    <p>Batafsil statistika va grafiklar orqali biznesingizni tahlil qiling</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3>Moliya boshqaruvi</h3>
                    <p>Daromad va xarajatlarni kuzating, moliyaviy hisobotlar oling</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🚚</div>
                    <h3>Ta'minotchilar</h3>
                    <p>Ta'minotchilarni boshqaring va buyurtmalarni nazorat qiling</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Mobil kirish</h3>
                    <p>Istalgan joydan va istalgan qurilmadan farmangizni boshqaring</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="about-content">
            <div class="about-text">
                <h2>Tovuq Farm haqida</h2>
                <p>Tovuq Farm - bu zamonaviy tovuqxonalar uchun mo'ljallangan professional boshqaruv tizimi. Biz O'zbekiston fermerlari uchun maxsus ishlab chiqilgan, foydalanish uchun qulay va to'liq funksional yechim taqdim etamiz.</p>
                <p>Bizning tizimimiz yordamida siz jo'jalar sonini kuzatish, ozuqa iste'molini nazorat qilish, moliyaviy hisoblarni yuritish va farmangizning barcha jarayonlarini bir joydan boshqarish imkoniyatiga ega bo'lasiz.</p>
                <p>Professional hisobotlar va analitika sizga to'g'ri qarorlar qabul qilish va biznesingizni rivojlantirishga yordam beradi.</p>
                <a href="#" class="btn btn-primary">Biz bilan bog'laning</a>
            </div>
            <div class="about-image">
                🐔
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="footer-bottom">
            <p>&copy; 2024 Tovuq Farm. Barcha huquqlar himoyalangan.</p>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>