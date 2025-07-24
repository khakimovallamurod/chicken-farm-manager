<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toviq - Professional Business Solutions</title>
    <link rel="stylesheet" href="assets/css/home_style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <a href="#" class="logo">TOVIQ</a>
            <nav class="nav-menu" id="navMenu">
                <li><a href="#home">Bosh sahifa</a></li>
                <li><a href="#services">Xizmatlar</a></li>
                <li><a href="#about">Biz haqimizda</a></li>
                <li><a href="#contact">Aloqa</a></li>
                <div class="auth-buttons">
                    <a href="auth/login.php" class="btn btn-login">Kirish</a>
                    <a href="auth/register.php" class="btn btn-register">Ro'yxatdan o'tish</a>
                </div>
            </nav>
            <button class="mobile-toggle" onclick="toggleMobileMenu()">☰</button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <p>Professional biznes yechimlari va innovatsion xizmatlar bilan muvaffaqiyatga erishing</p>
            <a href="#services" class="btn hero-btn">Xizmatlarni ko'rish</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="services">
        <div class="container">
            <h2 class="section-title">Bizning xizmatlarimiz</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🚀</div>
                    <h3>Biznesni rivojlantirish</h3>
                    <p>Kompaniyangizni yangi bosqichga olib chiqish uchun professional maslahat va strategiyalar</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💼</div>
                    <h3>Konsalting xizmatlari</h3>
                    <p>Tajribali mutaxassislarimiz sizning biznesingiz uchun eng yaxshi yechimlarni taklif qiladi</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Analitika va hisobotlar</h3>
                    <p>Ma'lumotlarga asoslangan qarorlar qabul qilish uchun professional tahlil va hisobotlar</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>TOVIQ haqida</h2>
                    <p>Biz professional biznes yechimlari va innovatsion xizmatlar bilan shug'ullanamiz. Bizning maqsadimiz - sizning biznesingizni yangi bosqichga olib chiqish va muvaffaqiyat sari yo'l ochish.</p>
                    <p>Tajribali jamoa va zamonaviy yondashuvlar bilan biz har bir mijozimizga individual yechimlar taklif qilamiz.</p>
                    <a href="#contact" class="btn btn-register">Biz bilan bog'laning</a>
                </div>
                <div class="about-image">
                    TOVIQ
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>TOVIQ</h3>
                    <p>Professional biznes yechimlari va innovatsion xizmatlar</p>
                </div>
                <div class="footer-section">
                    <h3>Aloqa</h3>
                    <p>📧 info@toviq.com</p>
                    <p>📞 +998 90 123 45 67</p>
                    <p>📍 Toshkent, O'zbekiston</p>
                </div>
                <div class="footer-section">
                    <h3>Xizmatlar</h3>
                    <a href="#">Biznes konsalting</a>
                    <a href="#">Strategik rejalashtirish</a>
                    <a href="#">Analitika</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 TOVIQ. Barcha huquqlar himoyalangan.</p>
            </div>
        </div>
    </footer>
    <script src="assets/js/home_script.js"></script>
    
</body>
</html>