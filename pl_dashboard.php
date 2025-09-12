<!DOCTYPE html>
<html lang="uz">
<?php
    $soni = 12;
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jo'ja Firma Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>

        <div class="content-wrapper">
            <section class="content pt-3">
                <div class="container-fluid">
                    <div class="table-header">
                        <h2 class="table-title">🐣 Jo'ja Ferma Hisoboti - 2025</h2>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center custom-table">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>№</th>
                                    <th>Katak Nomi</th>
                                    <th>Jo'ja Turi</th>
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
                                    <th>Jami (dona)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $kataklar_data = [];
                                $oy_nomlari = [
                                    1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel',
                                    5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust',
                                    9 => 'Sentabr', 10 => 'Oktabr', 11 => 'Noyabr', 12 => 'Dekabr'
                                ];

                                // Namuna ma'lumotlar
                                $sample_data = [
                                    'Katak-A1' => ['turi' => 'Broiler', 'oylik' => [150, 0, 180, 200, 0, 220, 190, 0, 210, 170, 0, 160]],
                                    'Katak-B2' => ['turi' => 'Tovuq', 'oylik' => [0, 120, 0, 140, 160, 0, 130, 150, 0, 140, 170, 0]],
                                    'Katak-C3' => ['turi' => 'O\'rdak', 'oylik' => [80, 0, 90, 0, 100, 85, 0, 95, 110, 0, 105, 90]],
                                    'Katak-D4' => ['turi' => 'G\'oz', 'oylik' => [0, 60, 0, 70, 0, 65, 75, 0, 80, 85, 0, 70]],
                                    'Katak-E5' => ['turi' => 'Broiler', 'oylik' => [200, 0, 220, 210, 0, 230, 240, 0, 220, 200, 0, 190]]
                                ];

                                $i = 1;
                                foreach ($sample_data as $katak => $data) {
                                    echo "<tr>";
                                    echo "<td>" . $i++ . "</td>";
                                    echo "<td class='font-weight-bold'>" . $katak . "</td>";
                                    echo "<td><span class='badge badge-info'>" . $data['turi'] . "</span></td>";

                                    $total = 0;
                                    for ($o = 0; $o < 12; $o++) {
                                        $joja_soni = $data['oylik'][$o];
                                        $total += $joja_soni;
                                        
                                        if ($joja_soni > 0) {
                                            $class = 'amount-positive';
                                        } else {
                                            $class = 'amount-zero';
                                        }
                                        
                                        $yem_json = json_encode([rand(500, 1500), rand(300, 800)]);
                                        $olim_json = json_encode([rand(5, 25), rand(2, 15)]);
                                        
                                        echo "<td class='clickable-cell $class' onclick='showMonthDetails(\"" . 
                                             $katak . "\", \"" . $data['turi'] . "\", \"" . 
                                             $oy_nomlari[$o+1] . "\", $joja_soni, $yem_json, $olim_json)'>" . 
                                             ($joja_soni > 0 ? number_format($joja_soni, 0, ',', ' ') : '-') . "</td>";
                                    }

                                    $totalClass = $total > 0 ? 'amount-positive' : 'amount-zero';
                                    echo "<td style='font-weight:bold' class='$totalClass'>" . number_format($total, 0, ',', ' ') . "</td>";
                                    
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Oylik ma'lumot modal -->
    <div class="modal fade" id="monthModal" tabindex="-1" role="dialog" aria-labelledby="monthModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="monthModalLabel">
                        <i class="fas fa-calendar-alt"></i> Oylik Ma'lumot
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="info-card">
                        <h4 id="monthKatakNomi" class="mb-2"></h4>
                        <p id="monthTuri" class="mb-2"></p>
                        <div class="month-info">
                            <h5 id="monthName" class="mb-3"></h5>
                            <div class="total-amount">
                                Jo'ja soni: <span id="monthAmount"></span> dona
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">🌾 Yem Sarfi</h6>
                                </div>
                                <div class="card-body">
                                    <div id="yemSarfi"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">💀 O'lim Holatlari</h6>
                                </div>
                                <div class="card-body">
                                    <div id="olimHolatlari"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-close-custom" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Yopish
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const oyNomlari = [
            '', 'Yanvar', 'Fevral', 'Mart', 'Aprel', 'May', 'Iyun',
            'Iyul', 'Avgust', 'Sentabr', 'Oktabr', 'Noyabr', 'Dekabr'
        ];

        function showMonthDetails(katakNomi, turi, oyNomi, jojaSoni, yemSarfi, olimHolatlari) {
            // Modal ma'lumotlarini to'ldirish
            document.getElementById('monthKatakNomi').textContent = katakNomi;
            document.getElementById('monthTuri').textContent = 'Turi: ' + turi;
            document.getElementById('monthName').textContent = oyNomi + ' oyi';
            document.getElementById('monthAmount').textContent = new Intl.NumberFormat('uz-UZ').format(jojaSoni);
            
            // Yem sarfini ko'rsatish
            const yemDiv = document.getElementById('yemSarfi');
            if (yemSarfi && yemSarfi.length > 0) {
                let yemHtml = '';
                yemSarfi.forEach(function(amount, index) {
                    if (amount > 0) {
                        const yemTuri = index === 0 ? 'Starter' : 'Finisher';
                        yemHtml += `<div class="mb-1">🌾 ${yemTuri}: ${new Intl.NumberFormat('uz-UZ').format(amount)} kg</div>`;
                    }
                });
                yemDiv.innerHTML = yemHtml || '<div class="text-muted">Ma\'lumot topilmadi</div>';
            } else {
                yemDiv.innerHTML = '<div class="text-muted">Ma\'lumot topilmadi</div>';
            }
            
            // O'lim holatlarini ko'rsatish
            const olimDiv = document.getElementById('olimHolatlari');
            if (olimHolatlari && olimHolatlari.length > 0) {
                let olimHtml = '';
                olimHolatlari.forEach(function(amount, index) {
                    if (amount > 0) {
                        const sabab = index === 0 ? 'Kasallik' : 'Boshqa sabablar';
                        olimHtml += `<div class="mb-1">💀 ${sabab}: ${new Intl.NumberFormat('uz-UZ').format(amount)} dona</div>`;
                    }
                });
                olimDiv.innerHTML = olimHtml || '<div class="text-success">O\'lim holatlari yo\'q</div>';
            } else {
                olimDiv.innerHTML = '<div class="text-success">O\'lim holatlari yo\'q</div>';
            }
            
            // Modalni ko'rsatish
            const modal = new bootstrap.Modal(document.getElementById('monthModal'));
            modal.show();
        }
        
        // Table hover effects
        document.querySelectorAll('.custom-table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.01)';
                this.style.transition = 'all 0.3s ease';
                this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.boxShadow = 'none';
            });
        });

        // Page load animation
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.custom-table tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>

</body>

</html>