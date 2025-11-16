<?php
    include_once '../config.php';
    $db = new Database();
    $gosht_list = [];
    $olgan_joja_list = [];
    $yem_list = [];
    $joja_list = [];
    $profit_list = [];

    $all_gosht_summa = 0;
    $all_olgan_joja_summa = 0;
    $all_yem_summa = 0;
    $all_joja_summa = 0;
    $all_profit = 0;

    foreach(range(1,12) as $month){

        $gosht = $db->get_gosht_soyish_summa_monthly($month) ?? 0;
        $olgan = $db->get_olgan_joja_summa_monthly($month) ?? 0;
        $yem   = $db->get_yem_berish_summa_monthly($month) ?? 0;
        $joja  = $db->get_joja_summa_monthly($month) ?? 0;

        $profit = $gosht - $olgan - $yem - $joja;

        // Oyning qiymatlarini massivga yozib boramiz
        $gosht_list[] = $gosht;
        $olgan_joja_list[] = $olgan;
        $yem_list[] = $yem;
        $joja_list[] = $joja;
        $profit_list[] = $profit;

        // Umumiy summalar
        $all_gosht_summa += $gosht;
        $all_olgan_joja_summa += $olgan;
        $all_yem_summa += $yem;
        $all_joja_summa += $joja;
        $all_profit += $profit;
    }

?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tovuq Firmasi - Daromad va Xarajatlar Hisoboti</title>
    <style>
        
        .header-section::before {
            position: absolute;
            top: 20px;
            left: 40px;
            font-size: 3rem;
            opacity: 0.3;
            animation: float 3s ease-in-out infinite;
        }

        .header-section::after {
            content: '🥚';
            position: absolute;
            top: 20px;
            right: 40px;
            font-size: 3rem;
            opacity: 0.3;
            animation: float 3s ease-in-out infinite reverse;
        }

        .header-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header-section .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        .table-container {
            padding: 30px;
            overflow-x: auto;
        }

        .financial-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .financial-table thead {
            background: linear-gradient(135deg, #2e7d32 0%, #388e3c 50%, #43a047 100%);
            color: white;
        }

        .financial-table th {
            padding: 20px 12px;
            text-align: center;
            font-weight: 600;
            font-size: 0.95rem;
            border-right: 1px solid rgba(255,255,255,0.1);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .financial-table th:last-child {
            border-right: none;
            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);
        }

        .financial-table tbody tr {
            transition: all 0.3s ease;
        }

        .financial-table tbody tr:hover {
            background: linear-gradient(90deg, #f1f8e9 0%, #e8f5e8 100%);
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .financial-table td {
            padding: 15px 12px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
            border-right: 1px solid #f0f0f0;
            font-size: 0.9rem;
            position: relative;
        }

        .financial-table td:last-child {
            border-right: none;
        }

        .financial-table td:first-child {
            text-align: left;
            font-weight: 600;
            background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);
        }

        /* Tovuq ferması uchun maxsus kategoriya ranglari */
        .revenue-row td:first-child {
            background: linear-gradient(90deg, #e8f5e8 0%, #ffffff 100%);
            border-left: 5px solid #4caf50;
        }

        .revenue-row td {
            color: #2e7d32;
            font-weight: 600;
        }

        .expense-row td:first-child {
            background: linear-gradient(90deg, #fff3e0 0%, #ffffff 100%);
            border-left: 5px solid #ff9800;
        }

        .expense-row td {
            color: #ef6c00;
            font-weight: 600;
        }

        .profit-row td:first-child {
            background: linear-gradient(90deg, #e3f2fd 0%, #ffffff 100%);
            border-left: 5px solid #2196f3;
        }

        .profit-row td {
            color: #1565c0;
            font-weight: 700;
        }

        .sub-item td:first-child {
            padding-left: 30px;
            font-weight: 500;
            font-size: 0.85rem;
            background: #fafafa;
            border-left: 3px solid #81c784;
        }

        .sub-item td {
            font-size: 0.85rem;
            color: #666;
        }

        /* Jami ustun uchun maxsus stil */
        .financial-table td:last-child,
        .financial-table th:last-child {
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            font-weight: 700;
            color: #1b5e20;
            border-left: 3px solid #4caf50;
        }

        .financial-table th:last-child {
            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);
            color: white;
        }

        /* Animatsiyalar */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Responsive design */
        @media (max-width: 1200px) {
            .financial-table {
                font-size: 0.8rem;
            }
            
            .financial-table th,
            .financial-table td {
                padding: 12px 8px;
            }
        }

        @media (max-width: 768px) {
            .header-section h2 {
                font-size: 1.8rem;
            }
            
            .header-section .subtitle {
                font-size: 1rem;
            }
            
            .table-container {
                padding: 15px;
            }
            
            .financial-table {
                font-size: 0.75rem;
            }
            
            .financial-table th,
            .financial-table td {
                padding: 10px 6px;
            }

            .header-section::before,
            .header-section::after {
                font-size: 2rem;
            }
        }

        /* Number formatting */
        .number {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        /* Loading animation */
        .financial-table tbody tr {
            animation: fadeInRow 0.6s ease forwards;
            opacity: 0;
        }

        .financial-table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .financial-table tbody tr:nth-child(2) { animation-delay: 0.2s; }
        .financial-table tbody tr:nth-child(3) { animation-delay: 0.3s; }
        .financial-table tbody tr:nth-child(4) { animation-delay: 0.4s; }
        .financial-table tbody tr:nth-child(5) { animation-delay: 0.5s; }
        .financial-table tbody tr:nth-child(6) { animation-delay: 0.6s; }
        .financial-table tbody tr:nth-child(7) { animation-delay: 0.7s; }
        .financial-table tbody tr:nth-child(8) { animation-delay: 0.8s; }
        .financial-table tbody tr:nth-child(9) { animation-delay: 0.9s; }
        .financial-table tbody tr:nth-child(10) { animation-delay: 1.0s; }
        .financial-table tbody tr:nth-child(11) { animation-delay: 1.1s; }
        .financial-table tbody tr:nth-child(12) { animation-delay: 1.2s; }

        @keyframes fadeInRow {
            to {
                opacity: 1;
            }
        }

        /* Tovuq fermasi uchun maxsus iconlar */
        .icon-chicken::before {
            content: '🐓';
            margin-right: 8px;
        }

        .icon-egg::before {
            content: '🥚';
            margin-right: 8px;
        }

        .icon-feed::before {
            content: '🌾';
            margin-right: 8px;
        }

        .icon-medicine::before {
            content: '💊';
            margin-right: 8px;
        }

        .icon-money::before {
            content: '💰';
            margin-right: 8px;
        }

        .icon-chart::before {
            content: '📊';
            margin-right: 8px;
        }

        /* Hover effect uchun qo'shimcha stil */
        .financial-table tbody tr:nth-child(even) {
            background: #f9fffe;
        }

        .financial-table tbody tr:nth-child(odd) {
            background: white;
        }

        .highlight-positive {
            background: linear-gradient(90deg, #c8e6c9 0%, #a5d6a7 100%);
            color: #1b5e20 !important;
            font-weight: bold;
        }

        .highlight-negative {
            background: linear-gradient(90deg, #ffcdd2 0%, #ef9a9a 100%);
            color: #b71c1c !important;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <section id="hisobot" class="content-section">
        <div class="header-section">
            <h2>🐓 Tovuq Firmasi - Daromad va Xarajatlar Hisoboti</h2>
        </div>
        
        <div class="table-container">
            <table class="financial-table" id="financialReportTable">
                <thead>
                    <tr>
                        <th>Ko'rsatkichlar</th>
                        <th>Yanvar</th>
                        <th>Fevral</th>
                        <th>Mart</th>
                        <th>Aprel</th>
                        <th>May</th>
                        <th>Iyun</th>
                        <th>Iyul</th>
                        <th>Avgust</th>
                        <th>Sentabr</th>
                        <th>Oktabr</th>
                        <th>Noyabr</th>
                        <th>Dekabr</th>
                        <th>📊 Jami</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="revenue-row">
                        <td><b>Go'sht so'yish summasi</b></td>
                        <?php foreach($gosht_list as $v): ?>
                            <td class="number"><?= number_format($v, 0, '.', ' ') ?></td>
                        <?php endforeach; ?>
                        <td class="number"><?= number_format($all_gosht_summa, 0, '.', ' ') ?></td>
                    </tr>
                    <tr class="sub-item">
                        <td>O'lgan jo'ja summasi</td>
                        <?php foreach($olgan_joja_list as $v): ?>
                            <td class="number"><?= number_format($v, 0, '.', ' ') ?></td>
                        <?php endforeach; ?>
                        <td class="number"><?= number_format($all_olgan_joja_summa, 0, '.', ' ') ?></td>
                    </tr>

                    <tr class="sub-item">
                        <td>Yem berish summasi</td>
                        <?php foreach($yem_list as $v): ?>
                            <td class="number"><?= number_format($v, 0, '.', ' ') ?></td>
                        <?php endforeach; ?>
                        <td class="number"><?= number_format($all_yem_summa, 0, '.', ' ') ?></td>
                    </tr>
                    <tr class="expense-row">
                        <td><b>Joja sotib olish summasi</b></td>
                        <?php foreach($joja_list as $v): ?>
                            <td class="number"><?= number_format($v, 0, '.', ' ') ?></td>
                        <?php endforeach; ?>
                        <td class="number"><?= number_format($all_joja_summa, 0, '.', ' ') ?></td>
                    </tr>
                    <tr class="profit-row">
                        <td><b>Sof foyda</b></td>
                        <?php foreach($profit_list as $v): ?>
                            <td class="number <?= $v >= 0 ? 'highlight-positive' : 'highlight-negative' ?>">
                                <?= number_format($v, 0, '.', ' ') ?>
                            </td>
                        <?php endforeach; ?>
                        <td class="number"><?= number_format($all_profit, 0, '.', ' ') ?></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </section>
    <script>
        // // 🔥 EXPORT BUTTONLAR UCHU
        $(document).ready(function() {
            $('#financialReportTable').DataTable({
                "paging": false,        
                "searching": false,     
                "ordering": false,      
                "info": false,      
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel yuklab olish',
                        title: 'Tovuq Firmasi - Daromad va Xarajatlar Hisoboti'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV yuklab olish',
                        title: 'Tovuq Firmasi - Daromad va Xarajatlar Hisoboti'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF yuklab olish',
                        title: 'Tovuq Firmasi - Daromad va Xarajatlar Hisoboti',
                        orientation: 'landscape',
                        pageSize: 'A4'
                    },
                    {
                        extend: 'print',
                        text: 'Chop etish',
                        title: 'Tovuq Firmasi - Daromad va Xarajatlar Hisoboti'
                    }
                ],
            });
        });
    </script>
</body>
</html>