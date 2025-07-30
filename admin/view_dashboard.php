<?php
    include_once '../config.php';
    $db = new Database();
    $result = $db->dashboard_view_data();

?>
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
            <h3><?=$result['jami_mahsulot_summasi']?></h3>
            <p>Jami mahsulotlar summasi (so'm)</p>
        </div>
        <div class="stat-card">
            <h3><?=$result['taminotchilar_balans']?></h3>
            <p>Jami taminotchilar summasi (so'm)</p>
        </div>
        <div class="stat-card">
            <h3><?=$result['mijozlar_balans']?></h3>
            <p>Jami mijozlar summasi (so'm)</p>
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