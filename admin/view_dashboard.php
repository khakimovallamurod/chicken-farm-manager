<?php
    include_once '../config.php';
    $db = new Database();
    $result = $db->dashboard_view_data();

?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            border-radius: 20px 20px 0 0;
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        /* Har bir karta uchun alohida ranglar */
        .stat-card:nth-child(1)::before {
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
        }
        .stat-card:nth-child(1) h3 {
            color: #4facfe;
        }

        .stat-card:nth-child(2)::before {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
        }
        .stat-card:nth-child(2) h3 {
            color: #43e97b;
        }

        .stat-card:nth-child(3)::before {
            background: linear-gradient(90deg, #fa709a 0%, #fee140 100%);
        }
        .stat-card:nth-child(3) h3 {
            color: #fa709a;
        }

        .stat-card:nth-child(4)::before {
            background: linear-gradient(90deg, #a8edea 0%, #fed6e3 100%);
        }
        .stat-card:nth-child(4) h3 {
            color: #7b68ee;
        }

        .stat-card:nth-child(5)::before {
            background: linear-gradient(90deg, #ffecd2 0%, #fcb69f 100%);
        }
        .stat-card:nth-child(5) h3 {
            color: #ff8a80;
        }

        .stat-card:nth-child(6)::before {
            background: linear-gradient(90deg, #a18cd1 0%, #fbc2eb 100%);
        }
        .stat-card:nth-child(6) h3 {
            color: #a18cd1;
        }

        .stat-card:nth-child(7)::before {
            background: linear-gradient(90deg, #fad0c4 0%, #ffd1ff 100%);
        }
        .stat-card:nth-child(7) h3 {
            color: #ff6b6b;
        }

        .stat-card h3 {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .stat-card p {
            color: #666;
            font-size: 1.1rem;
            font-weight: 500;
            line-height: 1.4;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            opacity: 0.1;
        }

        .stat-card:nth-child(1)::after {
            background: #4facfe;
        }
        .stat-card:nth-child(2)::after {
            background: #43e97b;
        }
        .stat-card:nth-child(3)::after {
            background: #fa709a;
        }
        .stat-card:nth-child(4)::after {
            background: #7b68ee;
        }
        .stat-card:nth-child(5)::after {
            background: #ff8a80;
        }
        .stat-card:nth-child(6)::after {
            background: #a18cd1;
        }
        .stat-card:nth-child(7)::after {
            background: #ff6b6b;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .stat-card h3 {
                font-size: 2.2rem;
            }
            
            .stat-card p {
                font-size: 1rem;
            }
        }

        /* Loading animation */
        .stat-card {
            animation: slideInUp 0.6s ease forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
        .stat-card:nth-child(5) { animation-delay: 0.5s; }
        .stat-card:nth-child(6) { animation-delay: 0.6s; }
        .stat-card:nth-child(7) { animation-delay: 0.7s; }

        @keyframes slideInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <section id="dashboard" class="content-section active">
        <div class="section-header">
            <h2 class="section-title">📊 Dashboard</h2>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3><?=$result['kataklar_soni']?></h3>
                <p>Jami kataklar</p>
            </div>
            <div class="stat-card">
                <h3><?=$result['jami_joja_soni']?></h3>
                <p>Jami jo'jalar</p>
            </div>
            <div class="stat-card">
                <h3><?=$result['jami_joja_summa']?></h3>
                <p>Jami jo'jalar summasi</p>
            </div>
            <div class="stat-card">
                <h3><?=$result['jami_mahsulot_summasi']?></h3>
                <p>Jami mahsulotlar summasi (so'm)</p>
            </div>
            <div class="stat-card">
                <h3><?=$result['mijozlar_balans']?></h3>
                <p>Jami mijozlar summasi (so'm)</p>
            </div>
            <div class="stat-card">
                <h3><?=$result['taminotchilar_balans']?></h3>
                <p>Jami taminotchilar summasi (so'm)</p>
            </div>
            <div class="stat-card">
                <h3><?=$result['kapital_summa']?></h3>
                <p>Kapital summasi (so'm)</p>
            </div>
        </div>
    </section>
</body>
</html>
