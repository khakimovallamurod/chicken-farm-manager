<?php
include_once '../config.php';
$db = new Database();
$taminotchilar = $db->get_data_by_table_all('taminotchilar', "ORDER BY created_at DESC");
?>
<section id="taminotchilar" class="content-section">
    <div class="section-header">
        <h2 class="section-title">🚛 Ta'minotchilar</h2>
        <button class="btn btn-primary" onclick="showModal('taminotchiModal')">
            ➕ Yangi ta'minotchi qo'shish
        </button>
    </div>
    <div class="kataklar-grid">
        <?php foreach ($taminotchilar as $taminotchi): ?>
        <div class="katak-card">
            <div class="katak-header">
                <div class="katak-title">🏢 <?=$taminotchi['kompaniya_nomi']?></div>
                <span class="katak-status status-active">Faol</span>
            </div>
            <div class="katak-info">
                <div class="info-item">
                    <div class="info-value">15</div>
                    <div class="info-label">Buyurtmalar</div>
                </div>
                <div class="info-item">
                    <div class="info-value"><?=$taminotchi['balans']?></div>
                    <div class="info-label">balans</div>
                </div>
            </div>
            <p><strong>FIO:</strong> <?=$taminotchi['fio']?></p>
            <p><strong>Mahsulotlar:</strong> <?=$taminotchi['mahsulotlar']?></p>
            <p><strong>Telefon:</strong> <?=$taminotchi['telefon']?></p>
        </div>
        <?php endforeach; ?>
       
    </div>
<!-- 
    <div class="table-container" style="margin-top: 2rem;">
        <h3>So'nggi buyurtmalar</h3>
        <table>
            <thead>
                <tr>
                    <th>Buyurtma ID</th>
                    <th>Ta'minotchi</th>
                    <th>Mahsulot</th>
                    <th>Miqdor</th>
                    <th>Summa</th>
                    <th>Sana</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#ORD-001</td>
                    <td>Yem Zavodi LLC</td>
                    <td>Tovuq yemi</td>
                    <td>500 kg</td>
                    <td>1,750,000 so'm</td>
                    <td>2024-12-15</td>
                    <td><span class="katak-status status-active">Yetkazildi</span></td>
                </tr>
                <tr>
                    <td>#ORD-002</td>
                    <td>Jo'ja Export MChJ</td>
                    <td>1 kunlik jo'jalar</td>
                    <td>200 dona</td>
                    <td>1,000,000 so'm</td>
                    <td>2024-12-18</td>
                    <td><span class="katak-status status-empty">Kutilmoqda</span></td>
                </tr>
            </tbody>
        </table>
    </div> -->
</section>
<div id="taminotchiModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('taminotchiModal')">&times;</span>
        <h2>🚛 Yangi ta'minotchi qo'shish</h2>
        <form id="taminotchiForm">
            <div class="form-grid">
                <div class="form-group">
                    <label>Kompaniya nomi:</label>
                    <input type="text" id="taminotchi_nomi" required placeholder="Masalan: Yem Zavodi LLC">
                </div>
                <div class="form-group">
                    <label>Kontakt shaxs:</label>
                    <input type="text" id="taminotchi_kontakt" required placeholder="Ism-familiya">
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Telefon:</label>
                    <input type="tel" id="taminotchi_telefon" required placeholder="+998 71 123 45 67">
                </div>
            </div>
            <div class="form-group">
                <label>Manzil:</label>
                <textarea id="taminotchi_manzil" rows="2" placeholder="Kompaniya manzili..."></textarea>
            </div>
            <div class="form-group">
                <label>Ta'minlaydigan mahsulotlar:</label>
                <textarea id="taminotchi_mahsulotlar" rows="2" placeholder="Masalan: Tovuq yemi, Vitaminlar, Jo'jalar..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">✅ Ta'minotchi qo'shish</button>
        </form>
    </div>
</div>
<script src="../js/jquery-3.6.0.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script>
    $('#taminotchiForm').on('submit', function (event) {
        event.preventDefault(); 
        const nomi = $('#taminotchi_nomi').val();
        const kontakt = $('#taminotchi_kontakt').val();
        const telefon = $('#taminotchi_telefon').val();
        const manzil = $('#taminotchi_manzil').val();
        const mahsulotlar = $('#taminotchi_mahsulotlar').val();

        const data = {
            nomi: nomi,
            kontakt: kontakt,
            telefon: telefon,
            manzil: manzil,
            mahsulotlar: mahsulotlar
        };
        console.log(data);
        $.ajax({
            url: '../form_insert_data/add_taminotchi.php',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    $('#taminotchiForm')[0].reset();
                    closeModal('taminotchiModal');
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                showAlert("Xatolik yuz berdi. Iltimos qayta urinib ko'ring.", 'error');
            }
        });
    });
</script>